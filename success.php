<?php
// Start session
session_start();
echo $_SESSION['client_id'] ;
var_dump("heelo");
echo "heelo";
// Check if the client_id session variable is set
if (isset($_SESSION['client_id'])) {
    $clientId = $_SESSION['client_id'];

    // Perform any actions or display a success message
    echo "Login successful! Client ID: $clientId";
} else {
    // Redirect to the login page if the user is not logged in
    header('Location: client-signup.php');
    exit();
}
?>
