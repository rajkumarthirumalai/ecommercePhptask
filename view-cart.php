<?php
// Database connection details
include("./db_config.php");
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Cart</title>
    <!-- Add the MDB CSS -->
    <link href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css" rel="stylesheet" />
    <!-- Add Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <?php include "client-header.php" ?>
    <div class="container mt-4">
        <h2>Cart Items</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Purchased</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <script>
                function formatTimestamp(unix_timestamp) {
                    var m = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    var d = new Date(unix_timestamp * 1000);
                    return [m[d.getMonth()], ' ', d.getDate(), ', ', d.getFullYear(), " ",
                    (d.getHours() % 12 || 12), ":", (d.getMinutes() < 10 ? '0' : '') + d.getMinutes(),
                        " ", d.getHours() >= 12 ? 'PM' : 'AM'].join('');
                }
            </script>
            <tbody>
                <?php
                // Retrieve the client_id from the URL
                $client_id = $_GET['id'];

                // Fetch cart items for the specified client_id
                $query = "SELECT c.id, p.name, c.quantity, p.price, p.image_path, c.total, c.created_at 
                          FROM cart c 
                          INNER JOIN products p ON c.product_id = p.id 
                          WHERE c.client_id = '$client_id'";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $totalSum = 0; // Variable to store the sum of total prices
                    while ($row = $result->fetch_assoc()) {
                        $image = $row['image_path'];
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['quantity'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['total'] . "</td>";
                        echo "<td><script>console.log('" . $row['created_at'] . "');
                              var dateString ='" . $row['created_at'] . "';
                              var unixTimestamp = Date.parse(dateString) / 1000;
                              console.log(formatTimestamp(unixTimestamp));
                              document.write(formatTimestamp(unixTimestamp));
                              </script></td>";
                        echo "<td>
                                <img src=" . $row['image_path'] . " alt='Product Image' style='width: 70px; height: 70px;'>
                              </td>";
                        echo "<td>
                                <button class='btn btn-danger btn-sm' onclick='removeFromCart(" . $row['id'] . ")'>Remove</button>
                              </td>";
                        echo "</tr>";

                        $totalSum += $row['total']; // Add the current item's total to the sum
                    }
                    // Display the total sum in the table footer
                    echo "<tfoot>
                            <tr>
                                <td colspan='6' class='text-end'><strong>Total:</strong></td>
                                <td><strong>$totalSum</strong></td>
                            </tr>
                          </tfoot>";
                } else {
                    echo "<tr><td colspan='7'>No items found in the cart.</td></tr>";
                }

                ?>
            </tbody>
        </table>
    </div>

    <!-- Add the MDB JavaScript files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/mdb@5.3.0/dist/js/mdb.min.js"></script>
    <!-- Add the jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

            // Function to handle item removal from cart
            function removeFromCart(cartId) {
                $.ajax({
                    url: "remove-from-cart.php",
                    type: "POST",
                    data: {
                        cartId: cartId
                    },
                    success: function (response) {
                        // Refresh the cart items after successful removal
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        // Display an error message if the removal fails
                        console.log(xhr.responseText);
                    }
                });
            }

            // Event handler for the remove button
            $(".remove-btn").click(function (e) {
                e.preventDefault();
                var cartId = $(this).data("cart-id");

                // Confirm removal and call the removeFromCart function
                if (confirm("Are you sure you want to remove this item from the cart?")) {
                    removeFromCart(cartId);
                }
            });
        
    </script>

</body>

</html>

<?php
$conn->close();
?>