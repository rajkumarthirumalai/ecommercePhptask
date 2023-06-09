<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve user input
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $email = $_POST["email"];

    // Validate password and confirm password
    if ($password !== $confirmPassword) {
        echo "Passwords do not match";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Database connection details
    $hostname = "localhost";
    $database = "cms";
    $db_username = "root";
    $db_password = "blaze.ws";

    // Create a connection to the database
    $conn = new mysqli($hostname, $db_username, $db_password);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create the database if it doesn't exist
    $createDbQuery = "CREATE DATABASE IF NOT EXISTS $database";
    $conn->query($createDbQuery);

    // Select the database
    $conn->select_db($database);

    // Create the users table if it doesn't exist
    $createTableQuery = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        active INT(1) NOT NULL,
        added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $conn->query($createTableQuery);

    // Check if the email already exists
    $checkEmailQuery = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Email already exists";
        exit();
    }

    // Prepare and execute the SQL query to insert a new user
    $insertUserQuery = "INSERT INTO users (username, email, password, active) VALUES (?, ?, ?, 1)";
    $stmt = $conn->prepare($insertUserQuery);
    $stmt->bind_param("sss", $username, $email, $hashedPassword);
    $stmt->execute();

    echo "User registration successful!";

    // Close the database connection
    $conn->close();

    // Redirect to login.php
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up Page</title>
    <!-- Material Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mt-4">
                <h2>Sign Up</h2>
                <form action="signup.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Reenter Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Material Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
