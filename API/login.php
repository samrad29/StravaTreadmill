<?php
session_start();
// Create connection
$db = new SQLite3('users.db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the database for the user
    $query = "SELECT password FROM users WHERE username = :email";
    $stmt = $db->prepare($query);
    $stmt->bindValue(':username', $email, SQLITE3_TEXT);
    $result = $stmt->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $stored_hashed_passwrd = $row['password'];

        // verify the input password against the stored hash
        if (password_verify($password, $stored_hashed_password)) {
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
