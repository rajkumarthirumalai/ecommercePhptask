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
    <!-- Add Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

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
                    ?>
                    <!-- Display the product details -->
                    <div class="justify-content-center mt-4">
                        <div class="card mb-3">
                            <div class="row no-gutters">
                                <div class="col-md-4">
                                    <img src="<?php echo $product['image_path']; ?>" class="img-fluid" alt="Product Image"
                                        style="max-width: 100%;">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body m-4">
                                        <h3 class="card-title">Product Name:
                                            <?php echo $product['name']; ?>
                                        </h3>
                                        <p>Price: $
                                            <?php echo $product['price']; ?>
                                        </p>
                                        <p> Sku :
                                            <?php echo $product['sku'] ?>
                                        </p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                        <label for="quantity">Quantity:</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-secondary" type="button" onclick="decreaseQuantity()"><i
                                                        class="fas fa-minus"></i></button>
                                            </div>
                                            <input type="number" id="quantity" name="quantity" min="1" value="1"
                                                onchange="updateTotalPrice(this.value, <?php echo $product['price']; ?>);">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="button" onclick="increaseQuantity()"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <br />
                                        <p id="total-price">Total Price: $
                                            <?php echo $product['price']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
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

        <script>
            // Function to update the total price based on the quantity
            function updateTotalPrice(quantity, price) {
                var totalPrice = quantity * price;
                document.getElementById('total-price').innerText = 'Total Price: $' + totalPrice.toFixed(2);
            }

            // Function to decrease the quantity
            function decreaseQuantity() {
                var quantityInput = document.getElementById('quantity');

                var currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;

                    updateTotalPrice(quantityInput.value, <?php echo $product['price']; ?>);
                }
            }

            // Function to increase the quantity
            function increaseQuantity() {
                var quantityInput = document.getElementById('quantity');


                var currentQuantity = parseInt(quantityInput.value);
                quantityInput.value = currentQuantity + 1;


                updateTotalPrice(quantityInput.value, <?php echo $product['price']; ?>);
            }
        </script>
</body>

</html>