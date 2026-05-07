<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$current_user = $_SESSION['username'];
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_description = $_POST['description'];
    $stmt = $conn->prepare("UPDATE account SET description = ? WHERE username = ?");
    $stmt->bind_param("ss", $new_description, $current_user);
    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
    }
    $stmt->close();
}

$result = $conn->query("SELECT description FROM account WHERE username = '$current_user'");
$row = $result->fetch_assoc();
$current_desc = $row['description'];
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'menu.php'; ?>
    <h2>Settings - Update Profile</h2>
    <p style="color: green;"><?php echo $message; ?></p>
    <form method="post">
        Update Description:<br>
        <textarea name="description" rows="5" cols="40"><?php echo htmlspecialchars($current_desc); ?></textarea><br><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>