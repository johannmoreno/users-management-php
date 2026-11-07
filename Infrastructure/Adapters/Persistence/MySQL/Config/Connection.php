<?php

declare(strict_types=1);

final class Connection
{
    private string $host;
    private int $port;
    private string $database;
    private string $username;
    private string $password;
    private string $charset;
    private string $sslCaPath;

    public function __construct(
        string $host,
        int $port,
        string $database,
        string $username,
        string $password,
        string $charset = 'utf8mb4',
        string $sslCaPath = ''
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
        $this->sslCaPath = $sslCaPath;
    }

    public function createPdo(): PDO
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->charset
        );

        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        );

        // Bases de datos en la nube (Aiven, etc.) exigen conexión cifrada.
        // Si se indica el certificado de la autoridad (CA), se activa SSL.
        if ($this->sslCaPath !== '') {
            $options[PDO::MYSQL_ATTR_SSL_CA] = $this->sslCaPath;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }

        $pdo = new PDO($dsn, $this->username, $this->password, $options);

        return $pdo;
    }
}