<?php
require_once '../socialnet/db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $description = $_POST['description'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO account (username, fullname, password, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $fullname, $password, $description);
    
    if ($stmt->execute()) {
        $message = "User created successfully! <a href='../socialnet/signin.php'>Go to Login</a>";
    } else {
        $message = "Error: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<body>
    <h2>Admin - Create New User</h2>
    <p style="color: green;"><?php echo $message; ?></p>
    <form method="post">
        Username: <input type="text" name="username" required><br><br>
        Fullname: <input type="text" name="fullname" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Description: <textarea name="description"></textarea><br><br>
        <button type="submit">Create User</button>
    </form>
</body>
</html>