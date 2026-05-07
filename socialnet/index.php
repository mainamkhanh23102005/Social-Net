<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}
$current_user = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'menu.php'; ?>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h2>
    <h3>Other Users:</h3>
    <ul>
        <?php
        $result = $conn->query("SELECT username, fullname FROM account WHERE username != '$current_user'");
        while ($row = $result->fetch_assoc()) {
            echo "<li>" . htmlspecialchars($row['fullname']) . " - <a href='profile.php?owner=" . urlencode($row['username']) . "'>View Profile</a></li>";
        }
        ?>
    </ul>
</body>
</html>