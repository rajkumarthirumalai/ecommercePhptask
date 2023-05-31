<?php
// Database connection details
        include("./db_config.php");

// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}

// Check if the delete button is clicked
if (isset($_POST['delete'])) {
    // Get the product ID to delete
    $productId = $_POST['id'];

    // Delete the product from the database
    $deleteQuery = "DELETE FROM products WHERE id = '$productId'";
    if ($conn->query($deleteQuery) === TRUE) {
        // Product deleted successfully, you can redirect to the dashboard or perform any other action
        header("Location: dashboard.php");
        exit();
    } else {
        // Error occurred while deleting the product
        echo "Error deleting product: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
