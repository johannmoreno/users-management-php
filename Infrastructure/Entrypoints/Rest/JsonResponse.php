<?php

declare(strict_types=1);

/**
 * Envía respuestas JSON con el código HTTP y los encabezados indicados.
 */
final class JsonResponse
{
    /**
     * @param array<string, mixed>|null $payload
     * @param array<string, string> $headers
     */
    public static function send(int $status, ?array $payload = null, array $headers = array()): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: no-store');

        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }

        // 204 No Content no lleva cuerpo.
        if ($payload !== null && $status !== 204) {
            echo json_encode(
                $payload,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
            );
        }
    }
}