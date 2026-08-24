USE mars_estate;

CREATE TABLE IF NOT EXISTS page_title_backgrounds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(100) NOT NULL UNIQUE,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS footer_menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(150) NOT NULL,
    url VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO footer_menu_items (label, url, sort_order)
SELECT * FROM (SELECT 'Terms of Use' AS label, '#' AS url, 0 AS sort_order) t
WHERE NOT EXISTS (SELECT 1 FROM footer_menu_items);

INSERT INTO footer_menu_items (label, url, sort_order)
SELECT * FROM (SELECT 'Privacy Policy', '#', 1) t
WHERE (SELECT COUNT(*) FROM footer_menu_items) = 1;

INSERT INTO footer_menu_items (label, url, sort_order)
SELECT * FROM (SELECT 'Our Services', 'services.php', 2) t
WHERE (SELECT COUNT(*) FROM footer_menu_items) = 2;

INSERT INTO footer_menu_items (label, url, sort_order)
SELECT * FROM (SELECT 'Contact', 'contact.php', 3) t
WHERE (SELECT COUNT(*) FROM footer_menu_items) = 3;

INSERT INTO footer_menu_items (label, url, sort_order)
SELECT * FROM (SELECT 'FAQS', 'faq.php', 4) t
WHERE (SELECT COUNT(*) FROM footer_menu_items) = 4;

DELETE FROM site_settings WHERE `key` = 'page_title_bg_image';
