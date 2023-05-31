<?php
include("./db_config.php");

// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}

// Check if the form is submitted and the required fields are set
if (isset($_POST['id']) && isset($_POST['productName']) && isset($_POST['productPrice']) && isset($_POST['productSku']) && isset($_POST['productImage'])) {
    $id = escape($_POST['id']);
    $productName = escape($_POST['productName']);
    $productPrice = escape($_POST['productPrice']);
    $productSku = escape($_POST['productSku']);
    $productImage = escape($_POST['productImage']);

    // Update the product details in the database
    $updateQuery = "UPDATE products SET name = '$productName', price = '$productPrice', sku = '$productSku', image = '$productImage' WHERE id = '$id'";
    $conn->query($updateQuery);

    // Redirect back to the dashboard page after updating
    header("Location: dashboard.php");
    exit();
}

// Close the database connection
$conn->close();
?>
