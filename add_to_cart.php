<?php
include("./db_config.php");
session_start();

if (isset($_POST['product_id'], $_POST['quantity'])) {

    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $clientId = $_SESSION['client_id']; // Assuming the client ID is stored in the session
    var_dump(
        $productId,
        $quantity,
        $clientId,
    );
    // Validate and sanitize the input parameters
    $productId = mysqli_real_escape_string($conn, $productId);
    $quantity = mysqli_real_escape_string($conn, $quantity);
    $clientId = mysqli_real_escape_string($conn, $clientId);
    $total = 40 * $quantity;

    // Insert the product into the cart table
    $insertQuery = "INSERT INTO cart (product_id, quantity, total, client_id) VALUES ('$productId', '$quantity', '$total', '$clientId')";
    var_dump($insertQuery);
    $conn->query($insertQuery);
}
?>