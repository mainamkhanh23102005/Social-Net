CREATE DATABASE IF NOT EXISTS socialnet;
USE socialnet;

CREATE TABLE IF NOT EXISTS account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    fullname VARCHAR(200) NOT NULL,
    password VARCHAR(255) NOT NULL,
    description TEXT
);

CREATE USER IF NOT EXISTS 'admin'@'localhost' IDENTIFIED BY 'Abc123';
GRANT ALL PRIVILEGES ON socialnet.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;

-- Create friend_requests table
CREATE TABLE IF NOT EXISTS friend_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    requester_id INT NOT NULL,
    receiver_id INT NOT NULL,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (requester_id) REFERENCES account(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES account(id) ON DELETE CASCADE,
    UNIQUE KEY unique_request (requester_id, receiver_id)
);

-- Create friends table
CREATE TABLE IF NOT EXISTS friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE CASCADE,
    FOREIGN KEY (friend_id) REFERENCES account(id) ON DELETE CASCADE,
    UNIQUE KEY unique_friendship (user_id, friend_id)
);

-- Insert some test data (optional)
-- Make sure you have at least 2 users in the account table first
-- Then you can insert a test friend request:
-- INSERT INTO friend_requests (requester_id, receiver_id) VALUES (1, 2);