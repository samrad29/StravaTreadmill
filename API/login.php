<?php
session_start();
// Create connection
$db = new SQLite3('./users.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputUsername = $_POST['email'];
    $inputPassword = $_POST['password'];

    // Query the database for the user
    $query = "SELECT password FROM users WHERE username = :email";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':email', $inputUsername, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        // verify the input password
        if ($inputPassword == $row['password']) {
            $_SESSION['user'] = $row['username']; // Store user in session
            echo "Login successful";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found with this username";
    }
}

$db->close()
?>
