CREATE TABLE IF NOT EXISTS contact_faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(255) NOT NULL,
    answer TEXT,
    sort_order INT DEFAULT 0
);

INSERT INTO contact_faqs (question, answer, sort_order) VALUES
('What is the difference between a Realtor® & real estate?', 'A Realtor is a licensed agent who is also a member of the National Association of Realtors and bound by its code of ethics, while "real estate" broadly refers to the property and industry itself.', 0),
('What factors should I consider when buying a home?', 'Location, budget, lot size, resale value, proximity to schools and work, and the total cost of building or buying — including permits, financing, and closing costs — are all worth weighing before you commit.', 1),
('How much should I budget for purchasing a home?', 'Beyond the purchase or build price, plan for closing costs (roughly 2% to 5%), inspections, permits, and a contingency fund for unexpected site conditions.', 2),
('What is a home appraisal, and why is it important?', 'An appraisal is an independent estimate of a property''s market value, usually required by lenders to confirm the loan amount matches what the home is actually worth.', 3),
('What is a home inspection, and should I get one?', 'A home inspection is a detailed check of a property''s condition — structure, systems, and safety. It''s strongly recommended before buying or before final handover on a new build.', 4);
