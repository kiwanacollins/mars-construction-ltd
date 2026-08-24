CREATE TABLE IF NOT EXISTS construction_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value INT NOT NULL DEFAULT 0,
    suffix VARCHAR(10) DEFAULT '%',
    label VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO construction_stats (value, suffix, label, sort_order) VALUES
(92, '%', 'On-Time Delivery', 0),
(97, '%', 'Client Satisfaction', 1),
(88, '%', 'On-Budget Projects', 2);
