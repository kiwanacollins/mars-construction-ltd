CREATE TABLE IF NOT EXISTS testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) DEFAULT NULL,
    rating INT DEFAULT 5,
    testimonial TEXT,
    image VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO testimonials (name, role, rating, testimonial, image, sort_order) VALUES
('Leslie Alexander', 'Online Broker', 5, 'Mars Construction delivered our home exactly on schedule and on budget. Their team communicated clearly at every stage and the finished quality speaks for itself.', 'assets/images/resource/author-4.png', 0),
('Robert Fox', 'Property Owner', 5, 'From the first consultation to the final walkthrough, the Mars Construction team was professional, transparent, and genuinely invested in getting the details right.', 'assets/images/resource/author-5.png', 1);
