USE mars_estate;

CREATE TABLE IF NOT EXISTS plan_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(140) NOT NULL UNIQUE,
    sort_order INT NOT NULL DEFAULT 0
);

INSERT IGNORE INTO plan_categories (name, slug, sort_order) VALUES
('Villas', 'villas', 1),
('Apartments', 'apartments', 2),
('Residential', 'residential', 3),
('Hotels', 'hotels', 4),
('Country Homes', 'country-homes', 5);
