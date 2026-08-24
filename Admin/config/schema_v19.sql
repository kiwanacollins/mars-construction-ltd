CREATE TABLE IF NOT EXISTS pm_handles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO pm_handles (title, sort_order) VALUES
('Routine maintenance and seasonal upkeep', 0),
('Repair coordination and vendor management', 1),
('Property inspections and condition reports', 2),
('Tenant coordination for rental properties', 3),
('Emergency call-out response', 4),
('Warranty follow-up on newly built homes', 5);

CREATE TABLE IF NOT EXISTS pm_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    value INT NOT NULL DEFAULT 0,
    suffix VARCHAR(10) DEFAULT '%',
    label VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO pm_stats (value, suffix, label, sort_order) VALUES
(95, '%', 'Response Rate', 0),
(90, '%', 'Owner Retention', 1),
(93, '%', 'Issues Resolved First Visit', 2);

CREATE TABLE IF NOT EXISTS pm_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT,
    sort_order INT DEFAULT 0
);

INSERT INTO pm_faqs (question, answer, sort_order) VALUES
('Do I need to have built with you to use property management?', 'No. While it''s a natural fit for homes we''ve built, we also manage properties for owners who built or bought elsewhere.', 0),
('Can you manage a rental property for me?', 'Yes, we coordinate with tenants on maintenance requests and keep the property in good condition between move-ins and move-outs.', 1),
('How quickly do you respond to repair requests?', 'Routine requests are typically scheduled within a few business days; urgent issues get a same-day response.', 2),
('Is property management available outside your build service area?', 'Coverage depends on location — get in touch and we''ll confirm whether we service your property''s area.', 3);
