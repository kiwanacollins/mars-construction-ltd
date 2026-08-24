CREATE TABLE IF NOT EXISTS client_logos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) DEFAULT NULL,
    link_url VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO client_logos (image, link_url, sort_order) VALUES
('assets/images/clients/1.png', NULL, 0),
('assets/images/clients/2.png', NULL, 1),
('assets/images/clients/3.png', NULL, 2),
('assets/images/clients/4.png', NULL, 3),
('assets/images/clients/5.png', NULL, 4);
