<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT fullname, password FROM account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['fullname'] = $row['fullname'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>SocialNet - Sign In</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <?php include 'menu.php'; ?>

    <div class="container">
        <div style="text-align: center;">
            <h2 style="color: #333;">Sign In to SocialNet</h2>
            <p>Chào mừng cậu quay trở lại!</p>
        </div>

        <?php if ($error): ?>
            <p class="error" style="background-color: #fce4e4; padding: 10px; border-radius: 4px; border: 1px solid #fccacb;">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form method="post">
            <label><b>Username</b></label>
            <input type="text" name="username" placeholder="Enter Username" required>

            <label><b>Password</b></label>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit" style="width: 100%; margin-top: 10px;">Sign In</button>
        </form>

        <div style="margin-top: 20px; text-align: center;">
            <hr>
            <p>Don't have an account?</p>
            <a href="../admin/newuser.php">
                <button type="button" style="background-color: #2196F3; width: auto;">Create New User (Admin Page)</button>
            </a>
        </div>
    </div>
</body>
</html>