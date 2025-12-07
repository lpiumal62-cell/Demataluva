-- Bright Future Academy Database Schema
-- Run this SQL script to create the database and tables

CREATE DATABASE IF NOT EXISTS bright_future_academy;
USE bright_future_academy;



-- Teachers table
CREATE TABLE IF NOT EXISTS teachers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subject VARCHAR(100) NOT NULL,
    image VARCHAR(255),
    bio TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    social_links JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    teacher_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admin_teacher_id FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

-- Classes table
CREATE TABLE IF NOT EXISTS classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    teacher_id INT,
    year YEAR NOT NULL,
    number_of_students INT DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL
);

-- Students table
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    class_id INT,
    year YEAR NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    parent_name VARCHAR(100),
    parent_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE SET NULL
);

-- Test scores table
CREATE TABLE IF NOT EXISTS test_scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(100) NOT NULL,
    score DECIMAL(5,2) NOT NULL,
    max_score DECIMAL(5,2) DEFAULT 100,
    year YEAR NOT NULL,
    semester VARCHAR(20),
    test_type VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Gallery table
CREATE TABLE IF NOT EXISTS gallery (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image_path VARCHAR(255) NOT NULL,
    type ENUM('image', 'video') DEFAULT 'image',
    event VARCHAR(100),
    year YEAR,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Events table
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255),
    date DATE NOT NULL,
    time TIME,
    location VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new', 'read', 'replied') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Newsletter subscribers table
CREATE TABLE IF NOT EXISTS subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    status ENUM('active', 'unsubscribed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123)
INSERT INTO admins (first_name, last_name, password, email, teacher_id) VALUES 
('Site', 'Administrator', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@brightfutureacademy.com', 1);

-- Insert sample teachers
INSERT INTO teachers (name, subject, bio, email, phone) VALUES 
('Dr. Sarah Johnson', 'Mathematics', 'PhD in Mathematics with 15 years of teaching experience', 'sarah.johnson@school.com', '+1-555-0101'),
('Mr. Michael Chen', 'Science', 'Masters in Physics, passionate about hands-on learning', 'michael.chen@school.com', '+1-555-0102'),
('Ms. Emily Davis', 'English Literature', 'BA in English Literature, specializes in creative writing', 'emily.davis@school.com', '+1-555-0103'),
('Dr. Robert Wilson', 'History', 'PhD in History, expert in world civilizations', 'robert.wilson@school.com', '+1-555-0104'),
('Ms. Lisa Brown', 'Art & Design', 'MFA in Fine Arts, encourages creative expression', 'lisa.brown@school.com', '+1-555-0105');

-- Insert sample classes
INSERT INTO classes (name, teacher_id, year, number_of_students, description) VALUES 
('Grade 1A', 1, 2024, 25, 'Foundation mathematics and basic concepts'),
('Grade 2B', 2, 2024, 28, 'Introduction to science and nature'),
('Grade 3A', 3, 2024, 22, 'English literature and creative writing'),
('Grade 4B', 4, 2024, 30, 'World history and geography'),
('Grade 5A', 5, 2024, 26, 'Art and design fundamentals');

-- Insert sample students
INSERT INTO students (name, class_id, year, email, parent_name, parent_phone) VALUES 
('Alice Smith', 1, 2024, 'alice.smith@student.com', 'John Smith', '+1-555-1001'),
('Bob Johnson', 1, 2024, 'bob.johnson@student.com', 'Mary Johnson', '+1-555-1002'),
('Charlie Brown', 2, 2024, 'charlie.brown@student.com', 'David Brown', '+1-555-1003'),
('Diana Prince', 2, 2024, 'diana.prince@student.com', 'Steve Prince', '+1-555-1004'),
('Eve Wilson', 3, 2024, 'eve.wilson@student.com', 'Robert Wilson', '+1-555-1005');

-- Insert sample test scores
INSERT INTO test_scores (student_id, subject, score, max_score, year, semester) VALUES 
(1, 'Mathematics', 95, 100, 2024, 'First'),
(1, 'English', 88, 100, 2024, 'First'),
(2, 'Mathematics', 92, 100, 2024, 'First'),
(2, 'Science', 85, 100, 2024, 'First'),
(3, 'Science', 90, 100, 2024, 'First'),
(3, 'Mathematics', 87, 100, 2024, 'First'),
(4, 'Science', 94, 100, 2024, 'First'),
(4, 'English', 91, 100, 2024, 'First'),
(5, 'English', 89, 100, 2024, 'First'),
(5, 'Art', 96, 100, 2024, 'First');

-- Insert sample events
INSERT INTO events (title, description, date, time, location) VALUES 
('Annual Science Fair', 'Students showcase their science projects and experiments', '2024-03-15', '09:00:00', 'School Auditorium'),
('Sports Day', 'Annual sports competition with various athletic events', '2024-04-20', '08:00:00', 'School Grounds'),
('Art Exhibition', 'Display of student artwork and creative projects', '2024-05-10', '14:00:00', 'Art Gallery'),
('Graduation Ceremony', 'Celebration of graduating students achievements', '2024-06-15', '10:00:00', 'Main Hall'),
('Parent-Teacher Conference', 'Meeting between parents and teachers to discuss student progress', '2024-02-28', '16:00:00', 'Classrooms');

-- Insert sample gallery items (placeholder paths)
INSERT INTO gallery (image_path, type, event, year, description) VALUES 
('assets/images/gallery/science-fair-1.jpg', 'image', 'Science Fair', 2024, 'Students presenting their science projects'),
('assets/images/gallery/sports-day-1.jpg', 'image', 'Sports Day', 2024, 'Students participating in relay race'),
('assets/images/gallery/art-exhibition-1.jpg', 'image', 'Art Exhibition', 2024, 'Student artwork on display'),
('assets/images/gallery/graduation-1.jpg', 'image', 'Graduation', 2024, 'Graduation ceremony celebration'),
('assets/images/gallery/classroom-1.jpg', 'image', 'Daily Activities', 2024, 'Students learning in classroom');

-- Create parent feedback table
CREATE TABLE IF NOT EXISTS parent_feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    student_name VARCHAR(100) NOT NULL,
    grade VARCHAR(20) NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    feedback TEXT NOT NULL,
    appreciations TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_rating (rating),
    INDEX idx_created_at (created_at)
);

-- Insert sample parent feedback
INSERT INTO parent_feedback (parent_name, email, student_name, grade, rating, feedback, appreciations) VALUES 
('Ahmed Hassan', 'ahmed.hassan@email.com', 'Ali Hassan', 'Grade 8', 5, 'Excellent school with dedicated teachers. My son has improved significantly in his studies and enjoys going to school every day.', 'Teaching Quality, School Facilities, Communication'),
('Fatima Ali', 'fatima.ali@email.com', 'Aisha Ali', 'Grade 6', 5, 'Great facilities and supportive environment. Communication with parents is excellent and the teachers are very caring.', 'Teaching Quality, Student Support, School Environment'),
('Mohamed Ibrahim', 'mohamed.ibrahim@email.com', 'Omar Ibrahim', 'Grade 10', 5, 'Perfect balance of academics and extracurricular activities. The school provides a well-rounded education. Highly recommended!', 'Teaching Quality, Extracurricular Activities, School Environment');
