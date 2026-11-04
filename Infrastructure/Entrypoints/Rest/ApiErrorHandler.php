<?php

declare(strict_types=1);

require_once __DIR__ . '/JsonResponse.php';
require_once __DIR__ . '/ApiException.php';

/**
 * Traduce cualquier excepción en una respuesta JSON uniforme:
 *
 *   { "error": { "status": 404, "code": "flight_not_found",
 *                "message": "...", "details": { ... } } }
 *
 * Los errores inesperados (500) nunca exponen el mensaje interno:
 * se registran en el log y se responde un texto genérico.
 */
final class ApiErrorHandler
{
    public static function handle(Throwable $exception): void
    {
        $status = 500;
        $code = 'internal_error';
        $message = 'Error interno del servidor.';
        $details = array();
        $headers = array();

        if ($exception instanceof ApiException) {
            $status = $exception->status();
            $code = $exception->errorCode();
            $message = $exception->getMessage();
            $details = $exception->details();
            $headers = $exception->headers();
        } elseif ($exception instanceof FlightNotFoundException) {
            $status = 404;
            $code = 'flight_not_found';
            $message = $exception->getMessage();
        } elseif ($exception instanceof FlightAlreadyExistsException) {
            $status = 409;
            $code = 'seat_already_taken';
            $message = $exception->getMessage();
        } elseif ($exception instanceof InvalidArgumentException) {
            // Todas las reglas del dominio (Value Objects, Enum) heredan de InvalidArgumentException.
            $status = 422;
            $code = 'validation_error';
            $message = $exception->getMessage();
        } else {
            error_log(sprintf(
                '[API] %s: %s en %s:%d',
                get_class($exception),
                $exception->getMessage(),
                $exception->getFile(),
                $exception->getLine()
            ));
        }

        $error = array(
            'status' => $status,
            'code' => $code,
            'message' => $message,
        );

        if ($details !== array()) {
            $error['details'] = $details;
        }

        JsonResponse::send($status, array('error' => $error), $headers);
    }
}