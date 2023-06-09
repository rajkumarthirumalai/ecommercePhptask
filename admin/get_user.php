<?php
// Assuming you have a database connection established
// Database connection details
include("./db_config.php");
// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}
// Check if the user ID parameter is provided
if(isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Prepare and execute the SQL query to fetch the user details
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found with the given ID
    if($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        echo json_encode($user); // Return the user details as JSON
    } else {
        // User not found with the given ID
        echo json_encode(null);
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
}
?>
