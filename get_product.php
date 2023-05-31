<?php
// Database connection details
include("./db_config.php");
// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}

// Retrieve the product details based on the provided ID    
if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $selectQuery = "SELECT id, name, price, sku, image FROM products WHERE id = '$productId'";
    $result = $conn->query($selectQuery);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        // Return the product details as JSON
        echo json_encode($product);
    } else {
        // No product found with the provided ID
        echo json_encode(null);
    }
} else {
    // No ID provided
    echo json_encode(null);
}

// Close the database connection
$conn->close();
?>
