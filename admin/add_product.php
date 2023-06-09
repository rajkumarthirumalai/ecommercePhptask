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

                    // Insert the new product into the database with the uploaded file paths
                    $insertQuery = "INSERT INTO products (name, price, sku, image_path, thumbnail_path, category) VALUES ('$productName', '$productPrice', '$productSku', '$uploadFilePath', '$thumbnailFilePath', '$category')";
                    var_dump($insertQuery);

                    if ($conn->query($insertQuery)) {
                        echo "Product inserted successfully.\n";

                        // Redirect back to the dashboard page after inserting the product
                        header("Location: dashboard.php");
                        exit();
                    } else {
                        echo "Failed to insert product into the database: " . $conn->error;
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
        echo "No image file was uploaded.\n";
    }
}

// Close the database connection
$conn->close();
?>
