-- ============================================================
-- CRUD Usuarios (Hexagonal + DDD + CQRS) - Script de esquema
-- Motor: MySQL / MariaDB (Laragon)
-- Alineado con UserRepositoryMySQL.php (tabla users: id, name,
-- email, password, role, status, created_at, updated_at)
-- ============================================================

-- 1. Base de datos
CREATE DATABASE IF NOT EXISTS crud_usuarios
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

-- 2. Usuario de BD solicitado en la guia (opcional en local: puedes usar root)
CREATE USER IF NOT EXISTS 'Estudiante'@'%' IDENTIFIED BY 'Developer';
GRANT ALL PRIVILEGES ON crud_usuarios.* TO 'Estudiante'@'%';
FLUSH PRIVILEGES;

USE crud_usuarios;

-- 3. Tabla de usuarios
--    id        -> UUID v4 generado por la aplicacion (generateUuid4)
--    role      -> ADMIN | MEMBER | REVIEWER   (UserRoleEnum)
--    status    -> ACTIVE | INACTIVE | PENDING | BLOCKED (UserStatusEnum)
--    password  -> hash bcrypt (UserPassword::fromPlainText)
CREATE TABLE IF NOT EXISTS users (
    id         CHAR(36)     NOT NULL,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       VARCHAR(20)  NOT NULL DEFAULT 'MEMBER',
    status     VARCHAR(20)  NOT NULL DEFAULT 'PENDING',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Usuario administrador base
--    Correo: admin@crud.com   Clave: Admin12345   (hash bcrypt)
--    Debe estar ACTIVE, de lo contrario el login lo rechaza.
INSERT INTO users (id, name, email, password, role, status)
VALUES (
    '8f1c2a3e-5b7d-4c9a-9e10-1a2b3c4d5e6f',
    'Administrador',
    'admin@crud.com',
    '$2y$10$apIRuSVAAZvRxtE.PnWebODHmtw7r2gvitOlbf5MK8cHzGd4rvKPy',
    'ADMIN',
    'ACTIVE'
)
ON DUPLICATE KEY UPDATE email = email;

-- 5. Tabla de vuelos (entidad del ejercicio 18)
--    id                -> UUID v4 generado por la aplicacion
--    status            -> PROGRAMADO | RETRASADO | EN_VUELO | ATERRIZADO | CANCELADO
--    price             -> valor del vuelo, mayor que 0
--    Regla de negocio: un mismo asiento no se vende dos veces para el mismo
--    numero de vuelo y la misma fecha de salida (uq_flight_seat).
CREATE TABLE IF NOT EXISTS flights (
    id                CHAR(36)      NOT NULL,
    purchase_date     DATETIME      NOT NULL,
    departure_date    DATETIME      NOT NULL,
    arrival_date      DATETIME      NOT NULL,
    travel_agency     VARCHAR(100)  NOT NULL,
    airline           VARCHAR(100)  NOT NULL,
    flight_number     VARCHAR(10)   NOT NULL,
    status            VARCHAR(20)   NOT NULL DEFAULT 'PROGRAMADO',
    price             DECIMAL(12,2) NOT NULL,
    customer          VARCHAR(100)  NOT NULL,
    seat              VARCHAR(5)    NOT NULL,
    aircraft          VARCHAR(50)   NOT NULL,
    departure_airport VARCHAR(100)  NOT NULL,
    arrival_airport   VARCHAR(100)  NOT NULL,
    pilot             VARCHAR(100)  NOT NULL,
    created_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at        TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_flight_seat (flight_number, departure_date, seat),
    KEY idx_flights_departure (departure_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Verificacion
SELECT id, name, email, role, status, created_at FROM users;
SHOW TABLES;
