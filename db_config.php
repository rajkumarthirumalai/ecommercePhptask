<?php
// Database configuration
$hostname = "localhost"; // Replace with your database hostname
$database = "cms"; // Replace with your database name
$db_username = "root"; // Replace with your database username
$db_password = "blaze.ws"; // Replace with your database password

// Create a connection to the database
$conn = new mysqli($hostname, $db_username, $db_password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
