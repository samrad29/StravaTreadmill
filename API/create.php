<?php
// Database file location (you can change the path)
$db_file = 'users.db';

// Create a new SQLite3 database
$db = new SQLite3($db_file);

// SQL to create a users table
$create_table_sql = "
CREATE TABLE IF NOT EXISTS users (
   id INTEGER PRIMARY KEY AUTOINCREMENT,
   username TEXT NOT NULL UNIQUE,
   password TEXT NOT NULL
);
";

// Execute the SQL
if ($db->exec($create_table_sql)) {
    echo "Database and 'users' table created successfully.";
} else {
    echo "Error creating table: " . $db->lastErrorMsg();
}

// Close the database connection
$db->close();
?>
