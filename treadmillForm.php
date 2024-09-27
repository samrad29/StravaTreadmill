<?php
session_start();  // Start the session

$timeout_duration = 1800;
// Check if the user is logged in
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit;
}

if(isset($_SESSION['login_time'])) {
    if ((time() - $_SESSION['login_time']) > $timeout_duration) {
        // If session has expired, destroy the session and redirect to login
        session_unset();
        session_destroy();
        header("Location: login.html"); //would be nice to alert the customer that their session has expired
        exit;
    } else {
        // Update the login time to extend the session
        $_SESSION['login_time'] = time();
    }
}

// If the user is logged in, display the content of the page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
    <p>This is a protected page only accessible to logged-in users.</p>
</body>
</html>