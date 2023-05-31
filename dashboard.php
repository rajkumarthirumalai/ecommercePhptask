<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirect to the dashboard
    header("Location: index.php");
    exit();
}
// var_dump(isset($_SESSION['user_id']));
?>
<?php
// Dummy breadcrumb data
$breadcrumbData = array("Home", "Category 1", "Category 2", "Category 3");

// Generate Breadcrumb HTML
$breadcrumbHtml = '<nav aria-label="breadcrumb">
    <ol class="breadcrumb">';

foreach ($breadcrumbData as $index => $category) {
    $breadcrumbHtml .= '<li class="breadcrumb-item';

    // Add "active" class to the last breadcrumb item
    if ($index === count($breadcrumbData) - 1) {
        $breadcrumbHtml .= ' active';
    }

    $breadcrumbHtml .= '">' . $category . '</li>';
}

$breadcrumbHtml .= '</ol></nav>';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <!-- CSS and other head elements -->
    <link href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css" rel="stylesheet" />
    <style>
        @import './dist/treeselectjs.css';


        @media screen and (max-width: 850px) {
            .section__select {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php echo $breadcrumbHtml; ?>
    <script src="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/js/mdb.min.js"></script>
    <?php include 'header.php'; ?>
    <script type="module">
        import Treeselect from './dist/treeselectjs.mjs'

        const options = [
            {
                name: 'Male',
                value: 1,
                children: [
                    {
                        name: 'Top',
                        value: 2,
                        children: [
                            {
                                name: 'Tshirt',
                                value: 3,
                                children: []
                            },
                            {
                                name: 'shirt',
                                value: 4,
                                children: []
                            }
                        ]
                    },
                    {
                        name: 'Bottom',
                        value: 5,
                        children: []
                    }
                ]
            },
            {
                name: 'Female',
                value: 6,
                children: [
                    {
                        name: 'saree',
                        value: 7,
                        children: []
                    },
                    {
                        name: 'chudi',
                        value: 8,
                        children: []
                    }
                ]
            }
        ]

        const className = '.treeselect-demo-default'

        const runDefaultExample = (Treeselect) => {
            const domElement = document.querySelector(className);
            const treeselect = new Treeselect({
                parentHtmlContainer: domElement,
                value: [],
                options: options
            });

            treeselect.srcElement.addEventListener('input', (e) => {
                const selectedValue = e.detail;
                document.getElementById("selectedCategory").value = selectedValue;
                console.log('default: Selected value ', selectedValue);
            });
        };

        runDefaultExample(Treeselect);
    </script>


    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="sortSelect" class="form-label">Sort by:</label>
                <select id="sortSelect" class="form-select" onchange="sortProducts(this)">
                    <option value="">Default</option>
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="sku">SKU</option>
                </select>
            </div>
        </div>

        <button type="button" class="btn btn-primary mb-3" data-mdb-toggle="modal"
            data-mdb-target="#addProductModal">Add Product</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>price</th>
                    <th>image</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include("./db_config.php");

                // Function to escape user input for security
                function escape($value)
                {
                    global $conn;
                    return $conn->real_escape_string($value);
                }

                // Retrieve the product details with optional sorting
                function getProducts($sortCriteria = "")
                {
                    global $conn;

                    $selectQuery = "SELECT id, image, name, price, sku FROM products";
                    if (!empty($sortCriteria)) {
                        $allowedCriteria = array("name", "price", "sku");
                        if (in_array($sortCriteria, $allowedCriteria)) {
                            $selectQuery .= " ORDER BY " . $sortCriteria;
                        }
                    }

                    $result = $conn->query($selectQuery);
                    return $result;
                }

                // Sort and retrieve the product details
                $sortCriteria = isset($_GET['sort']) ? $_GET['sort'] : "";
                $result = getProducts($sortCriteria);

                // Loop through the products and display rows in the table
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $image = $row['image'];
                    $name = $row['name'];
                    $price = $row['price'];
                    $sku = $row['sku'];
                    ?>
                    <tr>
                        <td>
                            <?php echo $id; ?>
                        </td>
                        <td>
                            <?php echo $name; ?>
                        </td>
                        <td>
                            <?php echo $price; ?>
                        </td>
                        <td>
                            <img src="<?php echo $image; ?>" alt="Product Image" style="width: 70px; height: 70px;">
                        </td>
                        <td>
                            <div class="d-flex">
                                <button class="btn btn-primary me-2" onclick="editProduct(<?php echo $id; ?>)">Edit</button>
                                <form method="post" action="delete_product.php"
                                    onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php
                }

                // Close the database connection
                $conn->close();
                ?>
            </tbody>
        </table>

        <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your form elements for adding a new product -->
                        <form method="POST" action="add_product.php">
                            <div class="mb-3">
                                <label for="productImage" class="form-label">category</label>
                                <div class="section__select treeselect-demo-default">
                                    <input type="hidden" id="selectedCategory" name="selectedCategory">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="productName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="productName" name="productName" required>
                            </div>
                            <div class="mb-3">
                                <label for="productPrice" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="productPrice"
                                    name="productPrice" required>
                            </div>
                            <div class="mb-3">
                                <label for="productSku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="productSku" name="productSku" required>
                            </div>
                            <div class="mb-3">
                                <label for="productImage" class="form-label">Image URL</label>
                                <input type="text" class="form-control" id="productImage" name="productImage" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add your form elements for editing a product -->
                        <form method="POST" action="update_product.php">
                            <input type="hidden" id="editProductId" name="id">
                            <div class="mb-3">
                                <label for="editProductName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="editProductName" name="productName"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductPrice" class="form-label">Price</label>
                                <input type="number" step="0.01" class="form-control" id="editProductPrice"
                                    name="productPrice" required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductSku" class="form-label">SKU</label>
                                <input type="text" class="form-control" id="editProductSku" name="productSku" required>
                            </div>
                            <div class="mb-3">
                                <label for="editProductImage" class="form-label">Image URL</label>
                                <input type="text" class="form-control" id="editProductImage" name="productImage"
                                    required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

        function sortProducts(select) {
            var sortCriteria = select.value;
            window.location.href = "dashboard.php?sort=" + sortCriteria;
        }

        function editProduct(productId) {
            // Fetch the product details from the server and populate the edit product modal fields
            fetch("get_product.php?id=" + productId)
                .then(response => response.json())
                .then(product => {
                    document.getElementById("editProductId").value = product.id;
                    document.getElementById("editProductName").value = product.name;
                    document.getElementById("editProductPrice").value = product.price;
                    document.getElementById("editProductSku").value = product.sku;
                    document.getElementById("editProductImage").value = product.image;

                    // Show the edit product modal
                    var modal = new mdb.Modal(document.getElementById("editProductModal"));
                    modal.show()
                })
        }
    </script>
</body>

</html>