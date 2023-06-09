<?php
// Database connection details
include("./db_config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            background-color: beige;
        }

        /* Styles for the first carousel */
        #carouselExampleIndicators1 {
            /* Add any custom styles if needed */
        }

        /* Styles for the second carousel */
        #carouselExampleControls2 {
            /* Add any custom styles if needed */
        }

        /* Combined CSS styles from both carousels */
        .carousel {
            max-width: 1300px;
            /* Adjust the width as desired */
            margin: 0 auto;
        }

        .carousel-item {
            margin-top: 50px;
            height: 600px;
            /* Adjust the height as desired */
            overflow: hidden;
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .carousel-item.zoomed img {
            transform: scale(1.2);
            /* Adjust the scale factor as desired */
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            z-index: 9999;
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

        .carousel-control-prev,
        .carousel-control-next {
            background-color: #e1e1e1;
            width: 5vh;
            height: 5vh;
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
        }

        @media (min-width: 768px) {
            .card img {
                height: 11em;
            }
        }
    </style>
    <title>Combined Carousels</title>
</head>

<body>
    <?php include 'client-header.php'; ?>

    <!-- First Carousel -->
    <div id="carouselExampleIndicators1" class="carousel slide" data-ride="carousel">

        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators1" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators1" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators1" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img class="d-block" src="./images/647764841ac6f.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
                <img class="d-block" src="./images/64789dbe0b092.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
                <img class="d-block" src="./images/6478a6e0917d7.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators1" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators1" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

    </div>

    <!-- Second Carousel -->
    <div id="carouselExampleControls2" class="carousel slide" data-ride="carousel">
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
                            <div class="card" >
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
        <a class="carousel-control-prev" href="#carouselExampleControls2" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls2" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            // JavaScript code for the first carousel goes here
            $('#carouselExampleIndicators1 .carousel-item').hover(function () {
                $(this).addClass('zoomed');
            }, function () {
                $(this).removeClass('zoomed');
            });
            // JavaScript code for the second carousel goes here
        });
    </script>
</body>

</html>