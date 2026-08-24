USE mars_estate;

CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reviewable_type ENUM('plan','project') NOT NULL,
    reviewable_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    rating TINYINT NOT NULL DEFAULT 5,
    comment TEXT,
    status ENUM('pending','approved') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX (reviewable_type, reviewable_id, status)
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(50),
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('new','processing','completed','cancelled') NOT NULL DEFAULT 'new',
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    property_id INT NOT NULL,
    plan_title VARCHAR(190) NOT NULL,
    addon_names TEXT,
    price DECIMAL(10,2) NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);
