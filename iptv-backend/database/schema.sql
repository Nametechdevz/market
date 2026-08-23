-- IPTV Player - Panel de administración
-- Módulo aislado: no comparte tablas con el marketplace (database/schema.sql)

CREATE DATABASE IF NOT EXISTS iptv_panel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iptv_panel;

-- Administradores del panel web
CREATE TABLE IF NOT EXISTS iptv_admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Servidores Xtream Codes (DNS/host del proveedor) que el admin da de alta
CREATE TABLE IF NOT EXISTS iptv_servers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    dns VARCHAR(255) NOT NULL COMMENT 'host o dominio, sin http(s)://',
    port INT NOT NULL DEFAULT 80,
    use_https TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Usuarios finales de la app Android (login propio), cada uno mapeado
-- a una línea (usuario/clave) dentro de un servidor Xtream Codes.
CREATE TABLE IF NOT EXISTS iptv_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'login en la app Android',
    password VARCHAR(255) NOT NULL COMMENT 'hash password_hash()',
    server_id INT NOT NULL,
    xtream_username VARCHAR(100) NOT NULL COMMENT 'credencial en el servidor Xtream',
    xtream_password VARCHAR(100) NOT NULL,
    max_connections INT NOT NULL DEFAULT 1,
    status ENUM('active','disabled') NOT NULL DEFAULT 'active',
    expires_at DATE NULL,
    notes TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_iptv_users_server FOREIGN KEY (server_id)
        REFERENCES iptv_servers(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Tokens de sesión de la app Android (Bearer token)
CREATE TABLE IF NOT EXISTS iptv_auth_tokens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    token VARCHAR(128) NOT NULL UNIQUE,
    device_info VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    CONSTRAINT fk_iptv_tokens_user FOREIGN KEY (user_id)
        REFERENCES iptv_users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_iptv_users_server ON iptv_users(server_id);
CREATE INDEX idx_iptv_tokens_user ON iptv_auth_tokens(user_id);
CREATE INDEX idx_iptv_tokens_expires ON iptv_auth_tokens(expires_at);
