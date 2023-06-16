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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
        integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
        crossorigin="anonymous" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
        crossorigin="anonymous" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            /* background: hsl(0, 0, 94%); */
            background-color: beige;
        }

        img {
            height: 280px;
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

        /* .card {
            margin: 0 0.5em;
            box-shadow: 2px 6px 8px 0 rgba(22, 22, 26, 0.18);
            border: none;
            border-radius: 0;
            width: 30%;
        } */

        .card.full-size {
            flex-grow: 1;
        }


    </style>
    <title>Client Dashboard</title>
</head>

<body>
    <?php include 'client-header.php'; ?>

    <!-- First Carousel -->
    <div class="container">
        <div class="row">
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
        <div class="row mt-5">
            <div class="container-fluid mt-5">
                <div class="row">
                    <div class="col-12 ">
                        <div class="owl-carousel owl-theme">
                            <?php
                            $selectQuery = "SELECT id, image_path,category, name, price, sku FROM products";
                            $result = $conn->query($selectQuery);
                            // var_dump($result->fetch_assoc());
                            while ($row = $result->fetch_assoc()) {

                                $productId = $row['id'];
                                $productName = $row['name'];
                                $productPrice = $row['price'];
                                $productSKU = $row['sku'];
                                $productImagePath = $row['image_path'];
                                ?>
                                <div class="item mb-4">
                                    <div class="card border-0 shadow">
                                        <div class="card">
                                            <img src="admin/<?php echo $productImagePath; ?>" class="card-img-top"
                                                alt="Product Image">
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
                                                <a href="product-details.php?id=<?php echo $productId; ?>"
                                                    class="btn btn-primary">View
                                                    Product</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
            integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
            crossorigin="anonymous"></script>
        <script>
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 15,
                nav: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    1000: {
                        items: 3
                    }
                }
            })
        </script>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>