<?php
// Database connection details
include("./db_config.php");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <!-- Add the MDB CSS -->
    <link href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css" rel="stylesheet" />
</head>
<body>

<!-- Add the header at the top -->
<?php include 'header.php'; ?>


<!-- Add the container -->
<div class="container-fluid align-items-center justify-content-center">
    <div class="container mt-4">
        <?php
        // Function to escape user input for security
        function escape($value)
        {
            global $conn;
            return $conn->real_escape_string($value);
        }

        // Check if the ID parameter is set in the URL
        if (isset($_GET['id'])) {
            $id = escape($_GET['id']);

            // Query to retrieve the post by its ID
            $query = "SELECT * FROM products WHERE id='$id'";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();

                // Display the product details
                echo '<div class="row justify-content-center">';

                // Display the product image on the left side
                echo '<div class="col-md-6">';
                echo '<img src="' . $product['image_path'] . '" alt="Product Image" style="max-width: 100%;">';
                echo '</div>';

                // Display the product name, SKU, and price on the right side
                echo '<div class="col-md-6">';
                echo '<h4>Product Name: ' . $product['name'] . '</h4>';
                echo '<p>SKU: ' . $product['sku'] . '</p>';
                echo '<p>Price: $' . $product['price'] . '</p>';
                echo '</div>';

                echo '</div>';
            } else {
                echo "Product not found.";
            }
        } else {
            echo "Invalid request.";
        }

        // Close the database connection
        $conn->close();
        ?>
    </div>
</div>

<!-- Add the MDB JavaScript files at the end of the body -->
<script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>
</body>
</html>
