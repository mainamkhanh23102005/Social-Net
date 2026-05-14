<?php
session_start();
require_once 'db.php';
require_once 'csrf.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];

// Get current user's ID
$stmt = $conn->prepare("SELECT id FROM account WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$current_user = $result->fetch_assoc();
$current_user_id = $current_user['id'];

// Fetch pending friend requests
$stmt = $conn->prepare("
    SELECT a.username, a.fullname 
    FROM friend_requests fr
    JOIN account a ON fr.requester_id = a.id
    WHERE fr.receiver_id = ? 
    AND fr.status = 'pending'
");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$requests = $stmt->get_result();

// Fetch friends list
$stmt = $conn->prepare("
    SELECT a.username, a.fullname 
    FROM friends f
    JOIN account a ON f.friend_id = a.id
    WHERE f.user_id = ?
");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$friends = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>SocialNet - Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'menu.php'; ?>
    
    <div class="container">
        <h2>Hello <?php echo htmlspecialchars($fullname); ?></h2>
        <p>Welcome to my new home page.</p>
        
        <!-- Friend Requests Section -->
        <div class="friend-requests-section">
            <h3>Requesting friends</h3>
            <?php if ($requests->num_rows > 0): ?>
                <ul class="friend-requests-list">
                <?php while($request = $requests->fetch_assoc()): ?>
                    <li>
                        <span><?php echo htmlspecialchars($request['fullname']); ?> (<?php echo htmlspecialchars($request['username']); ?>)</span>
                        
                        <div class="button-group">
                            <!-- Accept button -->
                            <form method="POST" action="accept_friend.php" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <input type="hidden" name="requester_username" value="<?php echo htmlspecialchars($request['username']); ?>">
                                <button type="submit" class="accept-btn">Make friend</button>
                            </form>
                            
                            <!-- Reject button -->
                            <form method="POST" action="reject_friend.php" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                                <input type="hidden" name="requester_username" value="<?php echo htmlspecialchars($request['username']); ?>">
                                <button type="submit" class="reject-btn">Deny</button>
                            </form>
                        </div>
                    </li>
                <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No pending friend requests.</p>
            <?php endif; ?>
        </div>

        <!-- Friends List Section -->
        <div class="friends-list-section">
            <h3>Friend list</h3>
            <?php if ($friends->num_rows > 0): ?>
                <ul class="friends-list">
                <?php while($friend = $friends->fetch_assoc()): ?>
                    <li>
                        <span><?php echo htmlspecialchars($friend['fullname']); ?></span>
                        <a href="profile.php?owner=<?php echo urlencode($friend['username']); ?>&csrf=<?php echo generate_url_token($current_user_id, $friend['username']); ?>" class="view-profile-link">View profile</a>
                    </li>
                <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No friends yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>