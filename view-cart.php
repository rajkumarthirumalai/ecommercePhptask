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

    <div class="container mt-4">
        <h2>Cart Items</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve the client_id from the URL
                $client_id = $_GET['id'];

                // Fetch cart items for the specified client_id
                $query = "SELECT c.id, p.name, c.quantity, p.price, c.total 
                          FROM cart c 
                          INNER JOIN products p ON c.product_id = p.id 
                          WHERE c.client_id = '$client_id'";
                 var_dump( $_GET['id']);   
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['quantity'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['total'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No items found in the cart.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add the MDB JavaScript files at the end of the body -->
    <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>
