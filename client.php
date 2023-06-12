<?php
// Database connection details
include("./db_config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: beige;
        }

        .slider {
            max-width: 1300px;
            margin: 0 auto;
            height: 600px;
        }

        .slider img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .carousel-item {
            margin-top: 50px;
            height: 600px;
            overflow: hidden;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .cards-wrapper {
            display: flex;
            justify-content: center;
        }

        .card img {
            max-width: 100%;
            max-height: 100%;
        }

        .card {
            margin: 0 0.5em;
            box-shadow: 2px 6px 8px 0 rgba(22, 22, 26, 0.18);
            border: none;
            border-radius: 0;
            height: 400px;
            width: 30%;
        }

        .card.full-size {
            flex-grow: 1;
        }

        .carousel-inner {
            padding: 1em;
        }
    </style>
    <title>Combined Carousels</title>
</head>

<body>
    <?php include 'client-header.php'; ?>

    <!-- First Carousel -->
    <div class="container">
        <div class="slider">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    // Array of image URLs
                    $images = [
                        'images/6478a6c09876d.jpg',
                        'images/6478a6c09876d.jpg',
                        'images/6478a6c09876d.jpg'
                    ];

                    $active = true;
                    foreach ($images as $image) {
                        echo '<div class="carousel-item' . ($active ? ' active' : '') . '">';
                        echo '<img src="' . $image . '" class="d-block w-100" alt="Slider Image">';
                        echo '</div>';
                        $active = false;
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Second Carousel -->
    <div id="carouselExampleControls2" class="carousel slide m-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            // Fetch data from the database
            $sql = "SELECT id, name, price, sku, image_path FROM products";
            $result = mysqli_query($conn, $sql);

            $cardsPerSlide = 3; // Number of cards to display per slide
            $numCards = mysqli_num_rows($result);
            $numSlides = ceil($numCards / $cardsPerSlide);

            $active = true;
            $cardIndex = 0;
            for ($i = 0; $i < $numSlides; $i++) {
                ?>
                <div class="carousel-item <?php echo $active ? 'active' : ''; ?>">
                    <div class="cards-wrapper d-flex">
                        <?php
                        for ($j = 0; $j < $cardsPerSlide; $j++) {
                            if ($cardIndex >= $numCards) {
                                break;
                            }
                            mysqli_data_seek($result, $cardIndex);
                            $row = mysqli_fetch_assoc($result);

                            $productId = $row['id'];
                            $productName = $row['name'];
                            $productPrice = $row['price'];
                            $productSKU = $row['sku'];
                            $productImagePath = $row['image_path'];
                            ?>
                            <div class="card">
                                <img src="<?php echo $productImagePath; ?>" class="card-img-top" alt="Product Image">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $productName; ?>
                                    </h5>
                                    <p class="card-text">Price: $
                                        <?php echo $productPrice; ?>
                                    </p>
                                    <p class="card-text">SKU:
                                        <?php echo $productSKU; ?>
                                    </p>
                                    <a href="product-details.php?id=<?php echo $productId; ?>" class="btn btn-primary">View
                                        Product</a>
                                </div>
                            </div>
                            <?php
                            $cardIndex++;
                        }
                        ?>
                    </div>
                </div>
                <?php
                $active = false;
            }
            ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls2"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls2"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>