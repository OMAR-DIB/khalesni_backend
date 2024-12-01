<?php

// Database configuration
$host = '127.0.0.1';      // Database host (localhost or IP)
$user = 'root';           // Database username
$password = '';           // Database password
$dbname = 'khalesni';     // Database name

// Establish conn
$conn = mysqli_connect($host, $user, $password, $dbname);
// echo'done';
// Check the conn
if (!$conn) {
    die("Database conn failed: " . mysqli_connect_error());
}

// Optional: Set the character set to UTF-8 for proper encoding
mysqli_set_charset($conn, "utf8mb4");

// conn successful message (for debugging, remove in production)
// echo "Database conn successful!";
?>
