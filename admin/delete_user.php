<?php
// Assuming you have already established a database connection
// Database configuration
include("./db_config.php");
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}

// Check if the delete button is clicked
if (isset($_POST['delete'])) {
    // Get the product ID to delete
    $userId = $_POST['id'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM users WHERE id = '$userId'";
    if ($conn->query($deleteQuery) === TRUE) {
        // Product deleted successfully, you can redirect to the dashboard or perform any other action
        header("Location: user_management.php");
        exit();
    } else {
        // Error occurred while deleting the product
        echo "Error deleting product: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

