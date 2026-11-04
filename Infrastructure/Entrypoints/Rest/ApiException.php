<?php

declare(strict_types=1);

/**
 * Error controlado de la API. Lleva el código HTTP, un código corto
 * para máquinas y un mensaje para personas.
 */
final class ApiException extends RuntimeException
{
    private int $status;
    private string $errorCode;

    /** @var array<string, mixed> */
    private array $details;

    /** @var array<string, string> */
    private array $headers;

    /**
     * @param array<string, mixed> $details
     * @param array<string, string> $headers
     */
    public function __construct(
        int $status,
        string $errorCode,
        string $message,
        array $details = array(),
        array $headers = array()
    ) {
        parent::__construct($message);

        $this->status = $status;
        $this->errorCode = $errorCode;
        $this->details = $details;
        $this->headers = $headers;
    }

    public static function badRequest(string $message): self
    {
        return new self(400, 'bad_request', $message);
    }

    public static function unauthorized(string $message): self
    {
        return new self(401, 'unauthorized', $message);
    }

    public static function notFound(string $message): self
    {
        return new self(404, 'route_not_found', $message);
    }

    /**
     * @param string[] $allowedMethods
     */
    public static function methodNotAllowed(array $allowedMethods): self
    {
        $allow = implode(', ', $allowedMethods);

        return new self(
            405,
            'method_not_allowed',
            'Método HTTP no permitido. Métodos válidos: ' . $allow . '.',
            array(),
            array('Allow' => $allow)
        );
    }

    /**
     * @param array<string, string> $details
     */
    public static function validation(string $message, array $details = array()): self
    {
        return new self(422, 'validation_error', $message, $details);
    }

    public function status(): int
    {
        return $this->status;
    }

    public function errorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * @return array<string, mixed>
     */
    public function details(): array
    {
        return $this->details;
    }

    /**
     * @return array<string, string>
     */
    public function headers(): array
    {
        return $this->headers;
    }
}