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
            $some = $uploadDirectory . uniqid();
            $uploadFilePath =  $some. '.' . $imageFileType;
            $thumbnailDirectory = 'images/thumbnails/';
            $thumbnailFilePath = $some. '_thumbnail.' . $imageFileType;

            // Move the uploaded file to the desired directories
            if (move_uploaded_file($imageTmpName, $uploadFilePath)) {
                echo "Image uploaded successfully.\n";

                // Create a thumbnail of the uploaded image
                $thumbnail = imagecreatetruecolor(100, 100);
                $source = imagecreatefromstring(file_get_contents($uploadFilePath));
                imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, 100, 100, imagesx($source), imagesy($source));

                // Save the thumbnail image
                if (imagejpeg($thumbnail, $thumbnailFilePath)) {
                    echo "Thumbnail image created and saved.\n";

                    // Update the product in the database with the new uploaded file paths
                    $updateQuery = "UPDATE products SET name='$productName', price='$productPrice', sku='$productSku', image_path='$uploadFilePath', thumbnail_path='$thumbnailFilePath', category='$category' WHERE id='$id'";
                    var_dump ($updateQuery);

                    if ($conn->query($updateQuery)) {
                        echo "Product updated successfully.\n";

                        // Redirect back to the dashboard page after updating the product
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        echo "Failed to update product in the database: " . $conn->error;
                    }
                } else {
                    echo "Failed to create and save the thumbnail image.\n";
                }

                // Free up memory
                imagedestroy($thumbnail);
                imagedestroy($source);
            } else {
                echo "Failed to move the uploaded image file to the desired directory.\n";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.\n";
        }
    } else {
        // Update the product in the database without changing the image paths
        $updateQuery = "UPDATE products SET name='$productName', price='$productPrice', sku='$productSku', category='$category' WHERE id='$id'";

        if ($conn->query($updateQuery)) {
            echo "Product updated successfully.\n";

            // Redirect back to the dashboard page after updating the product
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Failed to update product in the database: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
