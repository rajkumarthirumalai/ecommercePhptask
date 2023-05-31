<?php
include("./db_config.php");

// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve user input from the form
    $name = escape($_POST["userName"]);
    $email = escape($_POST["userEmail"]);
    $password = escape($_POST["userPassword"]);
    $active = escape($_POST["userActive"]);

    // Insert the user into the database
    $insertQuery = "INSERT INTO users (username, email, password, active) VALUES ('$name', '$email', '$password', '$active')";

    if ($conn->query($insertQuery) === TRUE) {
        // User inserted successfully
        header("Location: index.php"); // Redirect back to the user management page
        exit();
    } else {
        // Error occurred while inserting the user
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
