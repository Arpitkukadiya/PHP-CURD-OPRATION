CREATE DATABASE student_db;

USE student_db;

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO students (username, password, email, full_name) 
VALUES ('john_doe', '$2y$10$wT9U4hYwmlV5LqVnZLg5wO8sV5wmSxOdo92E8cW55UMc9R.mNfRpe', 'john.doe@example.com', 'John Doe');

$hashedPassword = password_hash('user_password', PASSWORD_DEFAULT);
echo $hashedPassword; // Save this hashed password in the database
