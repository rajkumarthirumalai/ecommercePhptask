<?php
include("./db_config.php");
session_start();

if (isset($_POST['product_id'], $_POST['quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $clientId = $_SESSION['client_id']; // Assuming the client ID is stored in the session

    // Validate and sanitize the input parameters
    $productId = mysqli_real_escape_string($conn, $productId);
    $quantity = mysqli_real_escape_string($conn, $quantity);
    $clientId = mysqli_real_escape_string($conn, $clientId);
    $total = 40 * $quantity;

    // Insert the product into the cart table
    $insertQuery = "INSERT INTO cart (product_id, quantity, total, client_id) VALUES ('$productId', '$quantity', '$total', '$clientId')";
    $conn->query($insertQuery);

    if ($conn->affected_rows > 0) {
        // Insertion successful, redirect to view-user.php with client_id
        header("Location: view-cart.php?id=" . $clientId);
        exit();
    } else {
        // Insertion failed, redirect to client.php
        header("Location: client.php");
        exit();
    }
}

?>