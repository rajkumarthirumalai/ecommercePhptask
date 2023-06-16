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
        .card-img-top {
            width: 500px;
            margin: 0 auto;
        }

        body {
            background-color: beige;
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
            echo $client;
            var_dump($client);
            if (password_verify($password, $client['password'])) {
                $_SESSION['client_id'] = $client['id'];
                header("Location: view-cart.php?id=" . $_SESSION['client_id']);
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
    $conn->close();
    ?>


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

        function viewCart() {
            if ('<?php echo isset($_SESSION['client_id']) ?>') {
                console.log('<?php echo ($_SESSION['client_id']) ?>');
                window.location.href = "view-cart.php?id=" + '<?php echo ($_SESSION['client_id']) ?>';

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

    <section style="background-color: #eee;">
        <div class="container py-5">
            <div class="row">
                <div class="card text-black">
                    <img src="admin/<?php echo $product['image_path']; ?>" class="card-img-top img-fluid"
                        alt="Product Image" style="max-width: 100%;" />
                    <div class="card-body">

                        <div class="text-center">
                            <div class="p-3 mx-n3 mb-4" style="background-color: #eff1f2;">
                                <h5 class="mb-0">
                                    <?php echo $product['name']; ?>
                                </h5>
                            </div>

                            <div class="d-flex flex-column mb-4">
                                <span class="h1 mb-0">Starting at
                                    <?php echo $product['price']; ?>
                                </span>

                            </div>

                            <div class="d-flex flex-column mb-4">
                                <span class="h1 mb-0">
                                    <?php echo $product['sku'] ?>
                                </span>

                            </div>

                            <div class="d-flex flex-column mb-4 lead">
                                <label for="quantity">Quantity:</label>
                                <div class="input-group justify-content-center">
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
                            </div>
                        </div>

                        <div class="d-flex flex-row">
                            <button type="button" class="btn btn-primary flex-fill me-1" data-mdb-ripple-color="dark"
                                onclick="addToCart(<?php echo $product['id'] ?>)">
                                Add Cart
                            </button>
                            <button type="button" class="btn btn-danger flex-fill ms-1"
                                onclick="viewCart(<?php echo $product['id'] ?>)">
                                View Cart</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>


</body>

</html>