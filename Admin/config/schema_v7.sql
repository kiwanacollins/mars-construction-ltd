USE mars_estate;

CREATE TABLE IF NOT EXISTS hero_slides (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heading VARCHAR(255) NOT NULL,
    subheading VARCHAR(150) DEFAULT NULL,
    description TEXT,
    button_text VARCHAR(100) DEFAULT NULL,
    button_link VARCHAR(255) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO hero_slides (heading, subheading, description, button_text, button_link, image, sort_order)
SELECT 'Let''s Unlock Dream Home here', 'real estate', 'Lorem ipsum dolor sit amet consectetur adipiscing elit Ut et massa mi. Aliquam in hendrerit urna. Pellentesque sit amet sapien fringilla, mattis ligula consectetur, ultrices mauris.', NULL, NULL, 'assets/images/main-slider/2.jpg', 0
WHERE NOT EXISTS (SELECT 1 FROM hero_slides);
