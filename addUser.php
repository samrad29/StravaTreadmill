<?php
// Database file location (you can change the path)
$db_file = 'users.db';

// Create a new SQLite3 database
$db = new SQLite3($db_file);


//if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$newUsername = 'sdradtke'; //$_POST['email'];
$newPassword = 'packersFan'; //$_POST['password'];

    // Query the database for the user
$insert = "INSERT INTO users (username, password) VALUES ( :newUsername, :newPassword)";
$stmt = $db->prepare($insert);
$stmt->bindValue(':newUsername', $newUsername, SQLITE3_TEXT);
$stmt->bindValue(':newPassword', $newPassword, SQLITE3_TEXT);
$addUserResult = $stmt->execute();
//}

if ($addUserResult) {
    echo "User added successfully";
} else {
    echo "Error adding user: " . $db->lastErrorMsg();
}

// Close the database connection
$db->close();
?>