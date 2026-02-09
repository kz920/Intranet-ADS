CREATE DATABASE IF NOT EXISTS intranet_ads CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE intranet_ads;

CREATE TABLE IF NOT EXISTS employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    position VARCHAR(100),
    department VARCHAR(100),
    phone VARCHAR(20),
    avatar_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    summary TEXT,
    content TEXT,
    category VARCHAR(50),
    published_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Données de test
INSERT INTO employees (first_name, last_name, email, position, department, phone, avatar_url) VALUES 
('Jean', 'Dupont', 'jean.dupont@ads.com', 'Directeur Commercial', 'Ventes', '+33 6 12 34 56 78', 'https://picsum.photos/100/100?random=1'),
('Marie', 'Curie', 'marie.curie@ads.com', 'Lead Developer', 'IT', '+33 6 98 76 54 32', 'https://picsum.photos/100/100?random=2');
