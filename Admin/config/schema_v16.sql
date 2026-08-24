CREATE TABLE IF NOT EXISTS construction_handles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO construction_handles (title, sort_order) VALUES
('Site preparation and foundation work', 0),
('Framing and structural construction', 1),
('Electrical, plumbing, and HVAC installation', 2),
('Interior and exterior finishing', 3),
('Permits, inspections, and code compliance', 4),
('On-site project management from start to finish', 5);
