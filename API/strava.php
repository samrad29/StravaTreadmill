<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}

echo "Welcome to the strava treadmill " . $_SESSION['user'] . "!";
?>

<a href="logout.php">Logout</a>
