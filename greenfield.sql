-- Full Greenfield Academy schema with demo data
CREATE DATABASE IF NOT EXISTS greenfield_academy;
USE greenfield_academy;

-- Users table (admin & students)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') DEFAULT 'student',
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    grade_id INT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Grades table
CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grade_name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Subjects table
CREATE TABLE IF NOT EXISTS subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Grade_subjects mapping table
CREATE TABLE IF NOT EXISTS grade_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    grade_id INT NOT NULL,
    subject_id INT NOT NULL,
    FOREIGN KEY (grade_id) REFERENCES grades(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- Applications table (extended fields)
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    id_number VARCHAR(50) DEFAULT NULL,
    date_of_birth DATE,
    nationality VARCHAR(100),
    race VARCHAR(100),
    email VARCHAR(255) NOT NULL,
    applicant_id VARCHAR(100) UNIQUE,
    grade_id INT NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    parent_name VARCHAR(255),
    parent_surname VARCHAR(255),
    parent_id_number VARCHAR(50),
    relationship VARCHAR(100),
    parent_phone VARCHAR(20),
    applicant_id_file VARCHAR(255),
    parent_id_file VARCHAR(255),
    school_report_file VARCHAR(255),
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (grade_id) REFERENCES grades(id)
);

-- Application subjects (many-to-many relationship)
CREATE TABLE IF NOT EXISTS application_subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id INT,
    subject_id INT,
    FOREIGN KEY (application_id) REFERENCES applications(id),
    FOREIGN KEY (subject_id) REFERENCES subjects(id)
);

-- News table
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    author_id INT,
    status ENUM('published', 'draft') DEFAULT 'published',
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Gallery table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    description TEXT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Messages table (contact form)
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('unread', 'read') DEFAULT 'unread'
);

-- Insert default grades
INSERT INTO grades (grade_name, description) VALUES
('Grade 8', 'Building strong foundational knowledge'),
('Grade 9', 'Developing critical thinking skills'),
('Grade 10', 'Preparing for important examinations'),
('Grade 11', 'Specializing in areas of interest'),
('Grade 12', 'Final preparation for university');

-- Insert default subjects
INSERT INTO subjects (subject_name) VALUES
('Mathematics'), ('English'), ('Science'), ('Social Studies'), ('Art'),
('Music'), ('Physical Education'), ('Computer Science'), ('Foreign Language'),
('Physics'), ('Chemistry'), ('Biology'), ('Geography'), ('History'),
('Economics'), ('Advanced Mathematics'), ('Literature');

-- Map all subjects to all grades (simple mapping)
INSERT INTO grade_subjects (grade_id, subject_id)
SELECT g.id, s.id FROM grades g CROSS JOIN subjects s;

-- Insert admin user (demo credentials)
INSERT INTO users (username, password, role, full_name, email) VALUES
('admin@greenfield.edu', 'admin123', 'admin', 'Administrator', 'admin@greenfield.edu');

-- Sample news
INSERT INTO news (title, content, image_url) VALUES
('Annual Science Fair 2023', 'Our students showcased innovative projects at the annual science fair...', 'science-fair.jpg'),
('Basketball Team Wins Championship', 'Our senior basketball team secured the regional championship title...', 'basketball.jpg'),
('New Library Opening', 'We''re excited to announce the opening of our newly expanded library facility...', 'library.jpg');

-- Sample gallery
INSERT INTO gallery (title, image_url, description) VALUES
('Science Fair', 'gallery/sample1.jpg', 'Highlights from the science fair'),
('Sports Day', 'gallery/sample2.jpg', 'Sports and athletics day'),
('Library Opening', 'gallery/sample3.jpg', 'New library facility');
