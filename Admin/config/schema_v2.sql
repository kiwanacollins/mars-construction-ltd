USE mars_estate;

CREATE TABLE IF NOT EXISTS site_settings (
    `key` VARCHAR(100) PRIMARY KEY,
    `value` TEXT
);

CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(120) NOT NULL,
    url VARCHAR(255) NOT NULL DEFAULT '#',
    parent_id INT DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE
);
