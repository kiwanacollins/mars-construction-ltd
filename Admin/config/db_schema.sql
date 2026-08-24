-- Mars real estate schema
CREATE DATABASE IF NOT EXISTS mars_estate CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mars_estate;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    role VARCHAR(80) DEFAULT 'Agent',
    email VARCHAR(190),
    phone VARCHAR(40),
    address VARCHAR(255),
    bio TEXT,
    photo VARCHAR(255),
    facebook VARCHAR(255),
    twitter VARCHAR(255),
    linkedin VARCHAR(255),
    instagram VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    status ENUM('sale','rent') NOT NULL DEFAULT 'sale',
    price DECIMAL(14,2) NOT NULL DEFAULT 0,
    price_period VARCHAR(30) DEFAULT NULL,
    property_type VARCHAR(80) DEFAULT NULL,
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    area_sqft DECIMAL(10,2) DEFAULT 0,
    address VARCHAR(255),
    zip_code VARCHAR(20),
    category VARCHAR(80),
    video_url VARCHAR(255),
    featured TINYINT(1) NOT NULL DEFAULT 0,
    description TEXT,
    features TEXT COMMENT 'JSON array of amenity strings',
    agent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS property_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_type ENUM('image','document') NOT NULL DEFAULT 'image',
    is_cover TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS blog_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    excerpt VARCHAR(500),
    body LONGTEXT,
    featured_image VARCHAR(255),
    category_id INT DEFAULT NULL,
    author_id INT DEFAULT NULL,
    status ENUM('published','draft','pending') NOT NULL DEFAULT 'draft',
    published_at DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES blog_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(40),
    subject VARCHAR(190),
    services VARCHAR(120),
    property_id INT DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL
);
