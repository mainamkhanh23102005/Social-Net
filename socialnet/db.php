<?php
$conn = new mysqli("localhost", "admin", "Abc123", "socialnet");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
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

-- Also create a friends table to store accepted friendships
CREATE TABLE IF NOT EXISTS friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES account(id) ON DELETE CASCADE,
    FOREIGN KEY (friend_id) REFERENCES account(id) ON DELETE CASCADE,
    UNIQUE KEY unique_friendship (user_id, friend_id)
);