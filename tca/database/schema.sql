-- Tech Career Academy Database Schema
-- Import this file via Hostinger hPanel > Databases > phpMyAdmin

CREATE DATABASE IF NOT EXISTS tca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tca_db;

-- ==========================
-- USERS (students + admins)
-- ==========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student','admin') NOT NULL DEFAULT 'student',
    phone VARCHAR(30) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================
-- CATEGORIES
-- ==========================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'terminal',
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO categories (name, slug, icon, sort_order) VALUES
('IT Support', 'it-support', 'headset', 1),
('IT Support Advanced / Networking', 'networking', 'network', 2),
('Basic Computer Skills', 'computer-skills', 'monitor', 3);

-- ==========================
-- COURSES
-- ==========================
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    short_description VARCHAR(300),
    description TEXT,
    teacher_name VARCHAR(150) DEFAULT NULL,
    level ENUM('beginner','intermediate','advanced') DEFAULT 'beginner',
    duration_weeks INT DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    thumbnail VARCHAR(255) DEFAULT NULL,
    is_published TINYINT(1) DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- ENROLLMENTS
-- ==========================
CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_enrollment (user_id, course_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- LIVE SESSIONS (Microsoft Teams)
-- ==========================
CREATE TABLE live_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    teams_link VARCHAR(500) NOT NULL,
    session_date DATETIME NOT NULL,
    duration_minutes INT DEFAULT 60,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- RECORDED SESSIONS (self-hosted video files)
-- ==========================
CREATE TABLE recordings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    file_path VARCHAR(500) NOT NULL,
    duration_seconds INT DEFAULT NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- TESTIMONIALS
-- ==========================
CREATE TABLE testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(150) NOT NULL,
    role_text VARCHAR(200) DEFAULT NULL,
    quote TEXT NOT NULL,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO testimonials (student_name, role_text, quote, sort_order) VALUES
('Sarah Khalil', 'IT Support Graduate', 'The hands-on labs made all the difference. I went from zero tech background to landing a help desk role in three months.', 1),
('Omar Fares', 'Networking Track', 'Live sessions with real instructors meant I could ask questions on the spot. Best decision I made for my career switch.', 2),
('Layla Haddad', 'Basic Computer Skills', 'I was intimidated by computers before this course. Now I feel confident using them for work every single day.', 3);

-- ==========================
-- FAQ
-- ==========================
CREATE TABLE faqs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question VARCHAR(300) NOT NULL,
    answer TEXT NOT NULL,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO faqs (question, answer, sort_order) VALUES
('Do I need any prior experience to enroll?', 'No. Our Basic Computer Skills and IT Support tracks are designed for absolute beginners. Advanced Networking assumes completion of IT Support fundamentals.', 1),
('How do live sessions work?', 'Live sessions are hosted on Microsoft Teams. Once enrolled, you will see scheduled sessions on your dashboard with a direct join link.', 2),
('Can I access recorded sessions after class ends?', 'Yes. All recorded sessions remain available in your student dashboard for as long as your enrollment is active.', 3),
('Do I get a certificate after completing a course?', 'Yes, students who complete all sessions in a course receive a certificate of completion.', 4);

-- ==========================
-- ADMIN ACCOUNT
-- Do NOT insert an admin row here with a hardcoded password.
-- After importing this file, visit /setup.php on your live site ONCE
-- to create your admin account securely, then delete setup.php.
-- ==========================
