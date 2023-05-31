<?php
// Database connection details
include("./db_config.php");

// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}
var_dump($_POST['selectedCategory']);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = escape($_POST['productName']);
    $productPrice = escape($_POST['productPrice']);
    $productSku = escape($_POST['productSku']);
    $productImage = escape($_POST['productImage']);
    $category = escape($_POST['selectedCategory']);
    $jsoncategory = json_encode($category);
    // Insert the new product into the database
    $insertQuery = "INSERT INTO products (name, price, sku, image, category) VALUES ('$productName', '$productPrice', '$productSku', '$productImage', '$jsoncategory')";

    $conn->query($insertQuery);

    // Redirect back to the dashboard page after inserting the product
    header("Location: dashboard.php");
    exit();
}

// Close the database connection
$conn->close();
?>