<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: signin.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<body>
    <?php include 'menu.php'; ?>
    <h2>About the Developer</h2>
    <p><strong>Student Name:</strong> Mai Nam Khánh</p>
    <p><strong>Student Number:</strong> 1695177</p>
    <p><strong>University:</strong> Hanoi University of Science and Technology (HUST)</p>
    <p><strong>Cohort:</strong> TROY CS22A-K68</p>
</body>
</html>