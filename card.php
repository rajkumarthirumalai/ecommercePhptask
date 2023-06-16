<!DOCTYPE html>
<html>

<head>
  <title>Responsive Bootstrap Carousel with Cards</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/mdb-ui-kit@3.11.0/css/mdb.min.css" rel="stylesheet" />
  <style>
    @media (max-width: 767.98px) {
      .border-sm-start-none {
        border-left: none !important;
      }
    }
  </style>
</head>

<body>
  <section style="background-color: #eee;">
    <div class="container py-5">
      <div class="row justify-content-center mb-3">
        <div class="col-md-12 col-xl-10">
          <div class="card shadow-0 border rounded-3">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12 col-lg-3 col-xl-3 mb-4 mb-lg-0">
                  <div class="bg-image hover-zoom ripple rounded ripple-surface">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/Horizontal/E-commerce/Products/img%20(4).webp"
                      class="w-100" />
                    <a href="#!">
                      <div class="hover-overlay">
                        <div class="mask" style="background-color: rgba(253, 253, 253, 0.15);"></div>
                      </div>
                    </a>
                  </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-6">
                  <h5>Quant trident shirts</h5>
                  <div class="d-flex flex-row">
                    <div class="text-danger mb-1 me-2">
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                      <i class="fa fa-star"></i>
                    </div>
                    <span>310</span>
                  </div>
                  <div class="mt-1 mb-0 text-muted small">
                    <span>100% cotton</span>
                    <span class="text-primary"> • </span>
                    <span>Light weight</span>
                    <span class="text-primary"> • </span>
                    <span>Best finish<br /></span>
                  </div>
                  <div class="mb-2 text-muted small">
                    <span>Unique design</span>
                    <span class="text-primary"> • </span>
                    <span>For men</span>
                    <span class="text-primary"> • </span>
                    <span>Casual<br /></span>
                  </div>
                  <p class="text-truncate mb-4 mb-md-0">
                    There are many variations of passages of Lorem Ipsum available, but the
                    majority have suffered alteration in some form, by injected humour, or
                    randomised words which don't look even slightly believable.
                  </p>
                </div>
                <div class="col-md-6 col-lg-3 col-xl-3 border-sm-start-none border-start">
                  <div class="d-flex flex-row align-items-center mb-1">
                    <h4 class="mb-1 me-1">$13.99</h4>
                    <span class="text-danger"><s>$20.99</s></span>
                  </div>
                  <h6 class="text-success">Free shipping</h6>
                  <div class="d-flex flex-column mt-4">
                    <button class="btn btn-primary btn-sm" type="button">Details</button>
                    <button class="btn btn-outline-primary btn-sm mt-2" type="button">
                      Add to wishlist
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     
    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <div class="card-body m-4">
                                        <h3 class="card-title">Product Name:
                                            <?php echo $product['name']; ?>
                                        </h3>
                                        <p>Price: $
                                            <?php echo $product['price']; ?>
                                        </p>
                                        <p> Sku :
                                            <?php echo $product['sku'] ?>
                                        </p>
                                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                        <label for="quantity">Quantity:</label>
                                        <div class="input-group">
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
                                        <br />
                                        <p id="total-price">Total Price: $
                                            <?php echo $product['price']; ?>
                                        </p>
                                        <script>
                                            function showAlert(mess) {
                                                Swal.fire({ position: 'center', icon: 'success', title: '', text: mess, showConfirmButton: false, timer: 1500 });
                                            }
                                        </script>
                                        <button onclick="addToCart(<?php echo $product['id'] ?>)"
                                            class="add-to-cart-btn btn btn-primary">Add to cart</button>
                                        <!-- <a href="view-cart.php?id=<?php echo $_SESSION['client_id']; ?>"> -->
                                        <button class="add-to-cart-btn btn btn-primary" onclick="viewCart(<?php echo $product['id'] ?>)">
                                            View Cart
                                        </button>
                                        </a>
                                    </div>

</body>


</html>