-- Base de datos para thefacebook clone
CREATE DATABASE IF NOT EXISTS thefacebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE thefacebook;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(50) DEFAULT 'Student',
    bio TEXT,
    avatar VARCHAR(255) DEFAULT 'default-avatar.jpg',
    university_domain VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de dominios universitarios permitidos (opcional, para validación)
CREATE TABLE IF NOT EXISTS allowed_domains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    domain VARCHAR(100) NOT NULL UNIQUE,
    university_name VARCHAR(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos dominios universitarios de ejemplo
INSERT INTO allowed_domains (domain, university_name) VALUES
('uvg.edu.gt', 'Universidad del Valle de Guatemala'),
('usac.edu.gt', 'Universidad de San Carlos de Guatemala'),
('url.edu.gt', 'Universidad Rafael Landívar'),
('ufm.edu', 'Universidad Francisco Marroquín'),
('harvard.edu', 'Harvard University'),
('mit.edu', 'Massachusetts Institute of Technology'),
('stanford.edu', 'Stanford University');

-- Usuario de ejemplo (contraseña: password123)
INSERT INTO users (name, email, password, status, bio, avatar, university_domain) VALUES
('Mark Zuckerberg', 'mark@harvard.edu', 'password123', 'Student', 'Founder of thefacebook', 'mark.jpg', 'harvard.edu');