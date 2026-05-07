CREATE DATABASE IF NOT EXISTS university_library
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE university_library;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    status ENUM('Available', 'Checked Out') NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO books (title, author, category, status) VALUES
('Introduction to Algorithms', 'Thomas H. Cormen', 'Computer Science', 'Available'),
('Clean Code', 'Robert C. Martin', 'Computer Science', 'Checked Out'),
('Database System Concepts', 'Abraham Silberschatz', 'Computer Science', 'Available'),
('A Brief History of Time', 'Stephen Hawking', 'Science', 'Available');
