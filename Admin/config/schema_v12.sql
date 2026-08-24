CREATE TABLE IF NOT EXISTS about_story_tabs (
    id INT PRIMARY KEY DEFAULT 1,
    badge_text VARCHAR(255) DEFAULT NULL,
    mission_text TEXT,
    mission_check1 VARCHAR(255) DEFAULT NULL,
    mission_check2 VARCHAR(255) DEFAULT NULL,
    vission_text TEXT,
    vission_check1 VARCHAR(255) DEFAULT NULL,
    vission_check2 VARCHAR(255) DEFAULT NULL,
    goal_text TEXT,
    goal_check1 VARCHAR(255) DEFAULT NULL,
    goal_check2 VARCHAR(255) DEFAULT NULL
);

INSERT IGNORE INTO about_story_tabs (id, badge_text, mission_text, mission_check1, mission_check2, vission_text, vission_check1, vission_check2, goal_text, goal_check1, goal_check2)
VALUES (
    1,
    'Client Centric Approach',
    'Our mission is to deliver reliable, high-quality construction and property management services that turn our clients'' vision into lasting, functional homes. We combine skilled craftsmanship with honest project management from the first plan to the final handover.',
    'Transparent pricing on every project.',
    'Skilled teams committed to quality workmanship.',
    'To be the most trusted name in home construction and property management, known for building communities that stand the test of time and exceed the expectations of every homeowner we serve.',
    'Sustainable building practices on every site.',
    'Long-term partnerships built on trust.',
    'We aim to make quality construction and downloadable house plans accessible to more families, while growing a property management portfolio that protects and grows our clients'' investments.',
    'On-time delivery, project after project.',
    'Dedicated support from design through construction.'
);
