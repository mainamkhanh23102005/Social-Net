<?php
$conn = new mysqli("localhost", "admin", "Abc123", "socialnet");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>