<?php
// reject_friend.php
session_start();
require_once 'db.php';
require_once 'csrf.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

// Verify CSRF token
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        die('CSRF token validation failed!');
    }

    $current_username = $_SESSION['username'];
    $requester_username = $_POST['requester_username'] ?? null;

    if (!$requester_username) {
        die('Invalid request');
    }

    try {
        // Get current user's ID
        $stmt = $conn->prepare("SELECT id FROM account WHERE username = ?");
        $stmt->bind_param("s", $current_username);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_user = $result->fetch_assoc();
        $current_user_id = $current_user['id'];

        // Get requester's ID
        $stmt = $conn->prepare("SELECT id FROM account WHERE username = ?");
        $stmt->bind_param("s", $requester_username);
        $stmt->execute();
        $result = $stmt->get_result();
        $requester = $result->fetch_assoc();
        $requester_id = $requester['id'];

        // Delete the friend request
        $stmt = $conn->prepare("
            DELETE FROM friend_requests 
            WHERE requester_id = ? 
            AND receiver_id = ? 
            AND status = 'pending'
        ");
        $stmt->bind_param("ii", $requester_id, $current_user_id);
        $stmt->execute();

        header("Location: index.php?message=request_rejected");
        exit();

    } catch(Exception $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit();
}
?>