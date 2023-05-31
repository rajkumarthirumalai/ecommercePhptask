<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect to the dashboard
    header("Location: dashboard.php");
    exit();
}
var_dump($_SESSION['user_id']);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve user input
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Database connection details
    $hostname = "localhost";
    $database = "cms";
    $db_username = "root";
    $db_password = "blaze.ws";

    // Create a connection to the database
    $conn = new mysqli($hostname, $db_username, $db_password, $database);

    // Check if the connection was successful
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute the SQL query to retrieve the user by email
    $selectUserQuery = "SELECT id, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($selectUserQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user with the given email exists
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $storedPassword = $row['password'];

        // Verify the password
        if (password_verify($password, $storedPassword)) {
            // Set the user_id in the session
            $_SESSION['user_id'] = $row['id'];

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        }
    }

    // If the login is unsuccessful, display an error message
    $error = "Invalid email or password";

    // Close the database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <!-- Material Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css">
    <style>
        @import 'dist/treeselectjs.css';

        .section__select {
            width: 50%;
            max-width: 400px;
        }

        @media screen and (max-width: 850px) {
            .section__select {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Login</h2>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <form action="index.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p>Not registered? <a href="signup.php">Sign up here</a></p>
            </div>
        </div>
    </div>

    <!-- Material Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>
</body>

</html>