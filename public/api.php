<?php

declare(strict_types=1);

// Front controller de la API REST de vuelos.
//
//   GET    /flights        lista los vuelos
//   POST   /flights        crea un vuelo
//   GET    /flights/{id}   consulta un vuelo
//   PUT    /flights/{id}   actualiza un vuelo
//   DELETE /flights/{id}   elimina un vuelo
//   GET    /health         estado del servicio (sin clave)
//
// Todas las rutas, salvo /health, exigen el encabezado X-API-Key.

ini_set('display_errors', '0');

require_once __DIR__ . '/../Common/ClassLoader.php';
require_once __DIR__ . '/../Common/DependencyInjection.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Rest/JsonResponse.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Rest/ApiException.php';
require_once __DIR__ . '/../Infrastructure/Entrypoints/Rest/ApiErrorHandler.php';

try {
    DependencyInjection::boot();

    $method = strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    $segments = apiPathSegments();

    // Ruta pública: sirve para comprobar que el servicio está vivo.
    if ($segments === array('health')) {
        assertMethod($method, array('GET'));
        JsonResponse::send(200, array('status' => 'ok'));
        exit;
    }

    assertApiKey();

    if ($segments === array('flights')) {
        assertMethod($method, array('GET', 'POST'));
        $controller = DependencyInjection::getFlightRestController();

        if ($method === 'GET') {
            $flights = $controller->index();
            JsonResponse::send(200, array('data' => $flights, 'count' => count($flights)));
            exit;
        }

        $flight = $controller->store(readJsonBody());
        JsonResponse::send(
            201,
            array('data' => $flight->toArray()),
            array('Location' => (string) ($_SERVER['SCRIPT_NAME'] ?? '') . '/flights/' . $flight->getId())
        );
        exit;
    }

    if (count($segments) === 2 && $segments[0] === 'flights') {
        $id = $segments[1];
        assertMethod($method, array('GET', 'PUT', 'DELETE'));
        $controller = DependencyInjection::getFlightRestController();

        if ($method === 'GET') {
            JsonResponse::send(200, array('data' => $controller->show($id)->toArray()));
            exit;
        }

        if ($method === 'PUT') {
            JsonResponse::send(200, array('data' => $controller->update($id, readJsonBody())->toArray()));
            exit;
        }

        $controller->delete($id);
        JsonResponse::send(204);
        exit;
    }

    throw ApiException::notFound('La ruta solicitada no existe.');
} catch (Throwable $exception) {
    ApiErrorHandler::handle($exception);
}

// ──────────────────────────────────────────────────────────────
// Funciones de apoyo
// ──────────────────────────────────────────────────────────────

/**
 * Parte de la URL que viene después de api.php, dividida por "/".
 *
 * @return string[]
 */
function apiPathSegments(): array
{
    $uriPath = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    $scriptName = (string) ($_SERVER['SCRIPT_NAME'] ?? '');

    $path = str_starts_with($uriPath, $scriptName)
        ? substr($uriPath, strlen($scriptName))
        : '';

    $segments = array();

    foreach (explode('/', trim($path, '/')) as $segment) {
        if ($segment !== '') {
            $segments[] = rawurldecode($segment);
        }
    }

    return $segments;
}

/**
 * @param string[] $allowedMethods
 */
function assertMethod(string $method, array $allowedMethods): void
{
    if (!in_array($method, $allowedMethods, true)) {
        throw ApiException::methodNotAllowed($allowedMethods);
    }
}

function assertApiKey(): void
{
    $expected = EnvLoader::get('API_KEY', '');

    // Valor seguro por defecto: sin clave configurada, nadie entra.
    if ($expected === '') {
        throw new ApiException(
            503,
            'api_key_not_configured',
            'La API no tiene una clave configurada. Define API_KEY en el entorno.'
        );
    }

    $provided = (string) ($_SERVER['HTTP_X_API_KEY'] ?? '');

    if ($provided === '' || !hash_equals($expected, $provided)) {
        throw ApiException::unauthorized('API key ausente o inválida. Envía el encabezado X-API-Key.');
    }
}

/**
 * @return array<string, mixed>
 */
function readJsonBody(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === false || trim($raw) === '') {
        throw ApiException::badRequest('El cuerpo de la petición está vacío.');
    }

    try {
        $data = json_decode($raw, true, 16, JSON_THROW_ON_ERROR);
    } catch (JsonException $exception) {
        throw ApiException::badRequest('El cuerpo no es un JSON válido.');
    }

    if (!is_array($data) || ($data === array() || array_keys($data) === range(0, count($data) - 1))) {
        throw ApiException::badRequest('El cuerpo debe ser un objeto JSON.');
    }

    return $data;
}