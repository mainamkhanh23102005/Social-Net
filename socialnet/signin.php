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
<body>
    <h2>Sign In to SocialNet</h2>
    <p style="color: red;"><?php echo $error; ?></p>
    <form method="post">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        <button type="submit">Sign In</button>
    </form>
</body>
</html>