<?php
// Database connection details
include("./db_config.php");

// Function to escape user input for security
function escape($value)
{
    global $conn;
    return $conn->real_escape_string($value);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = escape($_POST['productId']);
    $productName = escape($_POST['productName']);
    $productPrice = escape($_POST['productPrice']);
    $productSku = escape($_POST['productSku']);
    $category = escape(json_encode($_POST['selectedCategory']));

    // Check if an image file was uploaded
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['productImage']['tmp_name'];
        $imageFileName = $_FILES['productImage']['name'];
        $imageFileType = strtolower(pathinfo($imageFileName, PATHINFO_EXTENSION));

        // Validate the file type (allow only image files)
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($imageFileType, $allowedTypes)) {
            // Define the upload directory and file paths
            $uploadDirectory = 'images/';
            $uploadFilePath = $uploadDirectory . uniqid() . '.' . $imageFileType;

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($imageTmpName, $uploadFilePath)) {
                echo "Image uploaded successfully.\n";

                // Prepare the update query with only the provided values
                $updateQuery = "UPDATE products SET";
                $updateParams = array();
                if (!empty($productName)) {
                    $updateParams[] = "name='$productName'";
                }
                if (!empty($productPrice)) {
                    $updateParams[] = "price='$productPrice'";
                }
                if (!empty($productSku)) {
                    $updateParams[] = "sku='$productSku'";
                }
                if (!empty($uploadFilePath)) {
                    $updateParams[] = "image_path='$uploadFilePath'";
                    $thumbnailFilePath = str_replace('images/', 'images/thumbnails/', $uploadFilePath);
                    $updateParams[] = "thumbnail_path='$thumbnailFilePath'";
                }
                if (!empty($category)) {
                    $updateParams[] = "category='$category'";
                }

                if (!empty($updateParams)) {
                    $updateQuery .= " " . implode(", ", $updateParams);
                    $updateQuery .= " WHERE id='$id'";

                    if ($conn->query($updateQuery)) {
                        echo "Product updated successfully.\n";

                        // Redirect back to the dashboard page after updating the product
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        echo "Failed to update product in the database: " . $conn->error;
                    }
                } else {
                    echo "No fields provided to update.\n";
                }
            } else {
                echo "Failed to move the uploaded image file to the desired directory.\n";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.\n";
        }
    } else {
        // Prepare the update query with only the provided values
        $updateQuery = "UPDATE products SET";
        $updateParams = array();
        if (!empty($productName)) {
            $updateParams[] = "name='$productName'";
        }
        if (!empty($productPrice)) {
            $updateParams[] = "price='$productPrice'";
        }
        if (!empty($productSku)) {
            $updateParams[] = "sku='$productSku'";
        }
        if (!empty($category)) {
            $updateParams[] = "category='$category'";
        }

        if (!empty($updateParams)) {
            $updateQuery .= " " . implode(", ", $updateParams);
            $updateQuery .= " WHERE id='$id'";

            if ($conn->query($updateQuery)) {
                echo "Product updated successfully.\n";

                // Redirect back to the dashboard page after updating the product
                // header("Location: dashboard.php");
                exit();
            } else {
                echo "Failed to update product in the database: " . $conn->error;
            }
        } else {
            echo "No fields provided to update.\n";
        }
    }
}

// Close the database connection
$conn->close();
?>
