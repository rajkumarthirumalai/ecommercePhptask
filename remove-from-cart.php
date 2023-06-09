<?php
// Database connection details
include("./db_config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the cart ID from the AJAX request
    $cartId = $_POST['cartId'];

    // Remove the item from the cart
    $query = "DELETE FROM cart WHERE id = '$cartId'";
    if ($conn->query($query) === TRUE) {
        // Deletion successful
        echo "Item removed from the cart";
    } else {
        // Error occurred during deletion
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
