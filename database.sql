-- Portfolio Database Structure
CREATE DATABASE IF NOT EXISTS portfolio_db;
USE portfolio_db;

-- Personal Information Table
CREATE TABLE personal_info (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    location VARCHAR(100),
    linkedin VARCHAR(200),
    github VARCHAR(200),
    twitter VARCHAR(200),
    instagram VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Skills Table
CREATE TABLE skills (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    proficiency INT NOT NULL CHECK (proficiency >= 0 AND proficiency <= 100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Education Table
CREATE TABLE education (
    id INT PRIMARY KEY AUTO_INCREMENT,
    degree VARCHAR(200) NOT NULL,
    institution VARCHAR(200) NOT NULL,
    start_year INT NOT NULL,
    end_year INT,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Experience Table
CREATE TABLE experience (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    company VARCHAR(200) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    is_current BOOLEAN DEFAULT FALSE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Projects Table
CREATE TABLE projects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image_url VARCHAR(500),
    github_url VARCHAR(500),
    live_url VARCHAR(500),
    technologies TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Applications/Tools Table
CREATE TABLE applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    icon_class VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact Messages Table
CREATE TABLE contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data

-- Personal Information
INSERT INTO personal_info (name, title, description, email, phone, location, linkedin, github, twitter, instagram) VALUES
('Your Name', 'Web Developer & Designer', 'Passionate about creating beautiful and functional web experiences', 'your.email@example.com', '+1 (555) 123-4567', 'City, State, Country', 'linkedin.com/in/yourprofile', 'github.com/yourusername', 'twitter.com/yourusername', 'instagram.com/yourusername');

-- Skills
INSERT INTO skills (name, category, proficiency) VALUES
-- Frontend Development
('HTML5', 'Frontend Development', 95),
('CSS3', 'Frontend Development', 90),
('JavaScript', 'Frontend Development', 85),
('React', 'Frontend Development', 80),
-- Backend Development
('Node.js', 'Backend Development', 75),
('Python', 'Backend Development', 70),
('PHP', 'Backend Development', 65),
-- Tools & Others
('Git', 'Tools & Others', 85),
('Docker', 'Tools & Others', 60),
('AWS', 'Tools & Others', 55);

-- Education
INSERT INTO education (degree, institution, start_year, end_year, description) VALUES
('Bachelor of Science in Computer Science', 'University Name', 2020, 2024, 'Graduated with honors. Specialized in web development and software engineering. Completed capstone project on e-commerce platform development.'),
('Web Development Bootcamp', 'Coding Bootcamp Institute', 2023, 2023, 'Intensive 12-week program covering full-stack web development including React, Node.js, and database management.'),
('High School Diploma', 'High School Name', 2016, 2020, 'Graduated with distinction. Active member of the computer science club and participated in various coding competitions.');

-- Experience
INSERT INTO experience (title, company, start_date, end_date, is_current, description) VALUES
('Senior Web Developer', 'Tech Company Inc.', '2023-01-01', NULL, TRUE, 'Led development of responsive web applications using React and Node.js. Collaborated with cross-functional teams to deliver high-quality products. Mentored junior developers and conducted code reviews. Implemented CI/CD pipelines and automated testing.'),
('Frontend Developer', 'Startup Solutions', '2022-01-01', '2022-12-31', FALSE, 'Developed user interfaces using modern JavaScript frameworks. Optimized website performance and user experience. Worked with REST APIs and integrated third-party services. Participated in agile development processes.'),
('Web Development Intern', 'Digital Agency', '2021-06-01', '2021-12-31', FALSE, 'Assisted in developing client websites using HTML, CSS, and JavaScript. Learned modern development tools and version control systems. Gained experience with responsive design principles. Contributed to team projects and client presentations.');

-- Projects
INSERT INTO projects (title, description, image_url, github_url, live_url, technologies) VALUES
('E-Commerce Platform', 'A full-stack e-commerce website built with React, Node.js, and MongoDB. Features include user authentication, product management, and payment integration.', 'project1.jpg', 'https://github.com/yourusername/ecommerce', 'https://ecommerce-demo.com', 'React,Node.js,MongoDB'),
('Task Management App', 'A responsive task management application with drag-and-drop functionality, real-time updates, and team collaboration features.', 'project2.jpg', 'https://github.com/yourusername/taskapp', 'https://taskapp-demo.com', 'Vue.js,Firebase,CSS3'),
('Data Visualization Dashboard', 'An interactive dashboard for data visualization using D3.js and Chart.js. Displays real-time analytics with customizable charts and filters.', 'project3.jpg', 'https://github.com/yourusername/dashboard', 'https://dashboard-demo.com', 'D3.js,Chart.js,Python');

-- Applications/Tools
INSERT INTO applications (name, category, icon_class) VALUES
-- Development Tools
('VS Code', 'Development Tools', 'fas fa-code'),
('GitHub', 'Development Tools', 'fab fa-github'),
('Terminal', 'Development Tools', 'fas fa-terminal'),
('Docker', 'Development Tools', 'fab fa-docker'),
-- Design Tools
('Figma', 'Design Tools', 'fab fa-figma'),
('Adobe Creative Suite', 'Design Tools', 'fab fa-adobe'),
('Sketch', 'Design Tools', 'fas fa-palette'),
-- Cloud & Deployment
('AWS', 'Cloud & Deployment', 'fab fa-aws'),
('Google Cloud', 'Cloud & Deployment', 'fab fa-google'),
('Heroku', 'Cloud & Deployment', 'fab fa-heroku'),
('DigitalOcean', 'Cloud & Deployment', 'fab fa-digital-ocean'),
-- Project Management
('Trello', 'Project Management', 'fab fa-trello'),
('Slack', 'Project Management', 'fab fa-slack'),
('Jira', 'Project Management', 'fab fa-jira'),
('Asana', 'Project Management', 'fas fa-tasks');
