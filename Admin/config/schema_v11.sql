USE mars_estate;

CREATE TABLE IF NOT EXISTS home_service_cards (
    id INT AUTO_INCREMENT PRIMARY KEY,
    icon_class VARCHAR(100) DEFAULT NULL,
    title VARCHAR(190) NOT NULL,
    description TEXT,
    link_url VARCHAR(255) DEFAULT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO home_service_cards (icon_class, title, description, link_url, sort_order)
SELECT * FROM (SELECT 'flaticon-building' AS icon_class, 'Building Construction' AS title, 'From foundation to final walkthrough, our in-house teams manage every stage of your build.' AS description, 'construction.php' AS link_url, 0 AS sort_order) t
WHERE NOT EXISTS (SELECT 1 FROM home_service_cards);

INSERT INTO home_service_cards (icon_class, title, description, link_url, sort_order)
SELECT * FROM (SELECT 'flaticon-interior-design', 'Interior Designing', 'Interior finishes and layouts tailored to how you actually live in the space.', 'construction.php', 1) t
WHERE (SELECT COUNT(*) FROM home_service_cards) = 1;

INSERT INTO home_service_cards (icon_class, title, description, link_url, sort_order)
SELECT * FROM (SELECT 'flaticon-building-1', 'Property Management', 'Once your home is built, we stay on to handle upkeep, repairs, and maintenance.', 'property-management.php', 2) t
WHERE (SELECT COUNT(*) FROM home_service_cards) = 2;
