USE mars_estate;

CREATE TABLE IF NOT EXISTS page_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(50) NOT NULL,
    section_key VARCHAR(50) NOT NULL,
    heading VARCHAR(255) DEFAULT NULL,
    subheading VARCHAR(150) DEFAULT NULL,
    body TEXT,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY page_section_unique (page_key, section_key)
);
