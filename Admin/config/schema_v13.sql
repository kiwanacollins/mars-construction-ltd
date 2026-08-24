CREATE TABLE IF NOT EXISTS team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    designation VARCHAR(255) DEFAULT NULL,
    image VARCHAR(255) DEFAULT NULL,
    facebook_url VARCHAR(255) DEFAULT NULL,
    instagram_url VARCHAR(255) DEFAULT NULL,
    twitter_url VARCHAR(255) DEFAULT NULL,
    youtube_url VARCHAR(255) DEFAULT NULL,
    sort_order INT DEFAULT 0
);

INSERT INTO team_members (name, designation, image, sort_order) VALUES
('Leslie Alexander', 'Sr. Director', 'assets/images/resource/team-1.png', 0),
('Jenny Wilson', 'Sr. Manager', 'assets/images/resource/team-2.png', 1),
('Arlene McCoy', 'Sr. HRM', 'assets/images/resource/team-3.png', 2),
('Theresa Webb', 'Sr. Marketing', 'assets/images/resource/team-4.png', 3);
