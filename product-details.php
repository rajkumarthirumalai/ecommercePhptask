<?php
// Database connection details
include("./db_config.php");
// var_dump("client", $_SESSION['client_id']);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Product Details</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css">
    <!-- Add the MDB CSS -->
    <link href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css" rel="stylesheet" />
    <!-- Add Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>


    <style>

        .add-to-cart-btn {
            font-family: "Poppins";

            transition: 0.3s ease;
            background: #4787e9;
            border: 1px solid #c1c1c1;
            border-radius: 6px;
            font-family: "Poppins";
            font-size: 12px;
        }

        .add-to-cart-btn:hover,
        .add-to-wl-btn:hover {
            cursor: pointer;
            transform: scale(0.95);
        }

        .add-to-cart-btn:active,
        .add-to-wl-btn:active {
            transform: scale(0.92);
        }
    </style>
</head>

<body>

    <?php
    include("./db_config.php");
    include 'client-header.php';
    session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        echo "<script>console.log('Email:', '$email');</script>";
        echo "<script>console.log('Password:', '$password');</script>";
        $query = "SELECT id, password  FROM client WHERE email='$email'";

        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // echo "this is client['password'] ",$client['password'],"and this is pass",$password;
            // echo $password;
            $client = $result->fetch_assoc();
            echo  $client;
            var_dump($client);
            if (password_verify($password, $client['password'])) {
                $_SESSION['client_id'] = $client['id'];
                header("Location: view-user.php?id=" . $_SESSION['client_id']);
                exit();
            } else {
                echo "Invalid email or password. Please try again.";
                echo "password problem";

            }
        } else {
            echo "Invalid email or password. Please try again.";
        }
    }
    function escape($value)
    {
        global $conn;
        return $conn->real_escape_string($value);
    }
    if (isset($_GET['id'])) {
        $id = escape($_GET['id']);
        $query = "SELECT * FROM products WHERE id='$id'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $product = $result->fetch_assoc();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_SESSION['client_id'])) {
                    $quantity = escape($_POST['quantity']);
                    $clientId = $_SESSION['client_id'];

                    // Insert the product into the cart table
                    $insertQuery = "INSERT INTO cart (client_id, product_id, quantity) VALUES ('$clientId', '$id', '$quantity')";
                    if ($conn->query($insertQuery) === true) {
                        echo "Product added to cart!";
                    } else {
                        echo "Error adding product to cart. Please try again later.";
                    }
                } else {
                    echo "User not logged in. Please log in to add the product to the cart.";
                }
            }
            ?>
            <div class="container-fluid align-items-center justify-content-center">
                <div class="container mt-4">
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
                                                <button class="btn btn-danger m-2" type="button" onclick="decreaseQuantity()"><i
                                                        class="fas fa-minus"></i></button>
                                            </div>
                                            <input type="number" id="quantity" name="quantity" min="1" value="1"
                                                onchange="updateTotalPrice(this.value, <?php echo $product['price']; ?>);">
                                            <div class="input-group-append">
                                                <button class="btn btn-danger m-2" type="button" onclick="increaseQuantity()"><i
                                                        class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <br />
                                        <p id="total-price">Total Price: $
                                            <?php echo $product['price']; ?>
                                        </p>
                                        <script>
                                            function showAlert(mess) {
                                                Swal.fire({ position: 'center', icon: 'success', title: '', text: mess, showConfirmButton: false, timer: 1500 });
                                            }
                                        </script>
                                        <button onclick="addToCart(<?php echo $product['id'] ?>)"
                                            class="add-to-cart-btn btn btn-primary">Add to cart</button>
                                        <a href="view-cart.php?id=<?php echo $_SESSION['client_id']; ?>">
                                            <button class="add-to-cart-btn btn btn-primary">
                                                View Cart
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Login Modal -->
                        <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="loginModalLabel">Login</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input type="password" class="form-control" id="password" name="password"
                                                    required>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Login</button>
                                        </form>
                                        <p>Not registered? <a id="signup-link"
                                                href="client-signup.php?id=<?php echo $product['id']; ?>">Sign up here</a></p>

                                        <?php
                                        // Display error message if login failed
                                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $result->num_rows === 0) {
                                            echo "<p class='text-danger'>Invalid email or password. Please try again.</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script
                            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


                    </div>
                </div>
                <?php
        } else {
            echo "Product not found.";
        }
    } else {
        echo "Invalid request.";
    }
    $conn->close();
    ?>

        <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>

        <script>
            function addToCart(pid) {
                if ('<?php echo isset($_SESSION['client_id']) ?>') {
                    console.log("if part workss");
                    // User is logged in, show success message
                    var quantityInput = document.getElementById('quantity');
                    var quantity = parseInt(quantityInput.value);
                    var productId = pid;
                    var userId = "<?php echo $_SESSION['client_id'] ?>";
                    var url = 'add_to_cart.php';

                    // Create a form element
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    // Create hidden input fields for the data
                    var productIdInput = document.createElement('input');
                    productIdInput.type = 'hidden';
                    productIdInput.name = 'product_id';
                    productIdInput.value = productId;

                    var quantityInput = document.createElement('input');
                    quantityInput.type = 'hidden';
                    quantityInput.name = 'quantity';
                    quantityInput.value = quantity;

                    var userIdInput = document.createElement('input');
                    userIdInput.type = 'hidden';
                    userIdInput.name = 'client_id';
                    userIdInput.value = userId;

                    // Append the input fields to the form
                    form.appendChild(productIdInput);
                    form.appendChild(quantityInput);
                    form.appendChild(userIdInput);

                    // Append the form to the document and submit it
                    document.body.appendChild(form);
                    form.submit();
                } else {
                    console.log("else part workss");

                    // User is not logged in, redirect to the signup page with quantity parameter
                    var quantityInput = document.getElementById('quantity');
                    var quantity = parseInt(quantityInput.value);
                    var signupLink = document.getElementById('signup-link');
                    signupLink.href = "client-signup.php?id=<?php echo $product['id']; ?>&quantity=" + quantity;
                    showLoginModal();
                }
            }




            function showLoginModal() {
                new bootstrap.Modal(document.getElementById('loginModal')).show();
            }
            function updateTotalPrice(quantity, price) {
                var totalPrice = quantity * price;
                document.getElementById('total-price').innerText = 'Total Price: $' + totalPrice.toFixed(2);
            }
            function decreaseQuantity() {
                var quantityInput = document.getElementById('quantity');

                var currentQuantity = parseInt(quantityInput.value);
                if (currentQuantity > 1) {
                    quantityInput.value = currentQuantity - 1;

                    updateTotalPrice(quantityInput.value, <?php echo $product['price']; ?>);
                }
            }
            function increaseQuantity() {
                var quantityInput = document.getElementById('quantity');


                var currentQuantity = parseInt(quantityInput.value);
                quantityInput.value = currentQuantity + 1;


                updateTotalPrice(quantityInput.value, <?php echo $product['price']; ?>);
            }
        </script>
        <!-- Add the MDB JavaScript files at the end of the body -->
        <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>


</body>

</html>