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

-- 2. Usuario de BD solicitado en la guia
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

-- 5. Verificacion
SELECT id, name, email, role, status, created_at FROM users;