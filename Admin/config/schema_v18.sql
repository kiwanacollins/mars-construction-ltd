CREATE TABLE IF NOT EXISTS construction_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT,
    sort_order INT DEFAULT 0
);

INSERT INTO construction_faqs (question, answer, sort_order) VALUES
('Can you build from a plan I purchase on this site?', 'Yes. Once you purchase a plan and its file set, our team can quote and manage the full build for you, or work alongside a contractor of your choice.', 0),
('Do you handle permits and inspections?', 'Yes, we manage the permitting process and coordinate all required inspections throughout the build.', 1),
('How long does a typical build take?', 'Timelines vary by plan size and site conditions, but most single-family builds run between 6 and 10 months from groundbreaking to move-in.', 2),
('Can I make changes to a plan during construction?', 'Minor modifications are usually possible. We''ll review any requested changes against the plan and site conditions before construction begins.', 3);
