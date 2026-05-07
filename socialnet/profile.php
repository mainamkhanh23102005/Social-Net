<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}

$owner = isset($_GET['owner']) ? $_GET['owner'] : $_SESSION['username'];

$stmt = $conn->prepare("SELECT fullname, description FROM account WHERE username = ?");
$stmt->bind_param("s", $owner);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $fullname = $row['fullname'];
    $description = $row['description'];
} else {
    $fullname = "User not found";
    $description = "This user does not exist.";
}
$stmt->close();
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'menu.php'; ?>
    <h2>Profile of: <?php echo htmlspecialchars($fullname); ?> (@<?php echo htmlspecialchars($owner); ?>)</h2>
    <div style="border: 1px solid #ccc; padding: 15px;">
        <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
    </div>
</body>
</html>