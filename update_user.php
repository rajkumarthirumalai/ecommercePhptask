<?php
include("./db_config.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user details from the form
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $active = $_POST['active'];

    // Update the user in the database
    $updateQuery = "UPDATE users SET username = '$username', email = '$email', password = '$password', active = '$active' WHERE id = '$id'";
    if ($conn->query($updateQuery) === TRUE) {
        // User updated successfully
        echo "User updated successfully."; header("Location: user_management.php");
        exit();
    }else {
        // Error occurred while updating user
        echo "Error updating user: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
