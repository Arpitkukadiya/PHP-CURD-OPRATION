<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location:login.php');
};

if (isset($_POST['add_to_cart'])) {
  $pid = $_POST['pid'];
  $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
  $p_name = $_POST['p_name'];
  $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
  $p_price = $_POST['p_price'];
  $p_price = filter_var($p_price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $p_image = $_POST['p_image'];
  $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];

  $check_cart_numbers = $conn->prepare("SELECT * FROM `rental_cart` WHERE name = ? AND user_id = ?");
  $check_cart_numbers->execute([$p_name, $user_id]);

  if ($check_cart_numbers->rowCount() > 0) {
    $message[] = 'Already added to cart!';
  } else {
    $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
    $check_wishlist_numbers->execute([$p_name, $user_id]);

    if ($check_wishlist_numbers->rowCount() > 0) {
      $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
      $delete_wishlist->execute([$p_name, $user_id]);
    }

    $insert_cart = $conn->prepare("INSERT INTO `rental_cart`(user_id, pid, name, price, start_date, end_date, image) VALUES(?,?,?,?,?,?,?)");
    $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $start_date, $end_date, $p_image]);
    $message[] = 'Added to cart!';
  }
}

$search_query = "";
if (isset($_GET['search'])) {
  $search_query = trim($_GET['search']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OCCASION WEAR</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    .footer {
      padding: 10px 0;
      background-color: #000000;
      /* Blue footer background */
      color: #fff;
      text-align: center;
    }

    /* Custom Card Styling */
    .custom-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      overflow: hidden;
      margin-bottom: 30px;
    }

    .custom-card:hover {
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    /* Card Image */
    .card-image {
      width: 100%;
      height: 200px;
      overflow: hidden;
      border-radius: 15px 15px 0 0;
    }

    img {
      width: 100%;
      height: 300px;
      transition: transform 0.3s ease;
      
    }

    .custom-card:hover .card-image img {
      transform: scale(1.05);
    }

    /* Card Content */
    .card-content {
      padding: 20px;
      text-align: center;
    }

    .card-content h5 {
      font-size: 1.2rem;
      font-weight: 600;
      color: #333;
    }

    .price {
      font-size: 1.3rem;
      font-weight: 500;
      color:rgb(0, 0, 0);
      margin-bottom: 15px;
    }

    .price span {
      font-size: 0.9rem;
      color: #555;
    }

    /* Form Controls */
    .form-control {
      border-radius: 8px;
      border: 1px solid #ccc;
      padding: 10px;
    }

    /* Custom Button */
    .btn-custom {
      background:#000000;
      color: white;
      padding: 10px 15px;
      border-radius: 30px;
      border: none;
      transition: background 0.3s ease;
      width: 100%;
    }

    .carousel-item img {
  width: 100%;
  height: auto;
}
    .btn-custom:hover {
      background:#161616;
      color: white;
    }

    p {
      text-align: justify;
      margin-bottom: 10px;
    }

    .navbar {
  background-color:rgb(0, 0, 0); /* Primary blue for navbar */
  top: 0;
  z-index: 10;
}

.navbar-brand,
.nav-link {
  color: #fff !important;
}

  </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="rental_index.php">OCCASION WEAR - RENTAL</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
     
      <ul class="navbar-nav ml-auto">
  <li class="nav-item d-flex align-items-center">
    <form class="form-inline mr-3" method="GET" action="">
      <input class="form-control mr-2" type="search" name="search" placeholder="Search Products..." value="<?php echo htmlspecialchars($search_query); ?>">
      <button class="btn btn-outline-light" type="submit">Search</button>
    </form>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="index.php">Sales Wear</a>
  </li>
  <li class="nav-item">
            <a class="nav-link" href="rental_order_history.php">Order-History</a>
          </li>
  <li class="nav-item">
    <a class="nav-link" href="rental_cart.php">Cart</a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link" id="logoutLink" href="logout.php">Logout</a>
  </li>
</ul>
      </div>
    </div>
  </nav>
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">

    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="d-block w-100" src="img/side2.jpg" alt="First slide">
        <div class="carousel-caption d-none d-md-block">
        </div>
      </div>

    </div>

  </div>
  <div class="container-fluied mx-5  mt-5">
    <h1 class="title text-center mb-4">Latest Products</h1>
    <div class="row">
    <?php
    $query = "SELECT * FROM `rental_products`";
    if (!empty($search_query)) {
      $query .= " WHERE name LIKE ? OR category LIKE ?";
      $select_products = $conn->prepare($query);
      $search_param = "%" . $search_query . "%";
      $select_products->execute([$search_param, $search_param]);
    } else {
      $select_products = $conn->prepare($query . " LIMIT 6");
      $select_products->execute();
    }

    if ($select_products->rowCount() > 0) {
      while ($fetch_rental_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
    ?>
          <div class="col-md-3">
            <div class="custom-card">
              <img src="uploaded_img/<?= $fetch_rental_products['image']; ?>" alt="<?= $fetch_rental_products['name']; ?>" padding="">
              <div class="card-content">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <h5 class="mb-0"><?= $fetch_rental_products['name']; ?></h5>
                  <p class="price mb-0">₹<?= $fetch_rental_products['price']; ?> <span>per day</span></p>

                </div>
                <!-- <p class="card-text text-muted"><?= $fetch_rental_products['details']; ?></p> -->
                <p class="category font-italic text-secondary">Category: <?= $fetch_rental_products['category']; ?></p>

                <form action="" method="POST">
                  <input type="hidden" name="pid" value="<?= $fetch_rental_products['id']; ?>">
                  <input type="hidden" name="p_name" value="<?= $fetch_rental_products['name']; ?>">
                  <input type="hidden" name="p_price" value="<?= $fetch_rental_products['price']; ?>">
                  <input type="hidden" name="p_image" value="<?= $fetch_rental_products['image']; ?>">
                  <div class="form-group d-flex align-items-center mt-3">
                    <div class="w-50 pr-2">
                      <label>Start Date:</label>
                      <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="w-50 pl-2">
                      <label>End Date:</label>
                      <input type="date" name="end_date" class="form-control" required>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-custom" name="add_to_cart"> <i class="fas fa-shopping-cart"></i> Add to Cart</button>
                </form>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo '<p class="text-center">No products available!</p>';
      }
      ?>
    </div>
  </div>

  <footer class="footer text-white">
    <div class="text-center text-white p-3 h6">
      &copy; 2025 OCCASION WEAR. All rights reserved.
    </div>
  </footer>
  <script>
  // Get today's date and format it as YYYY-MM-DD
  const today = new Date();
  today.setDate(today.getDate() + 1); // Set to tomorrow

  const day = String(today.getDate()).padStart(2, '0');
  const month = String(today.getMonth() + 1).padStart(2, '0'); // Months are zero-indexed
  const year = today.getFullYear();

  const tomorrowDate = `${year}-${month}-${day}`; // Formatted as YYYY-MM-DD

  // Set the min attribute for start_date and end_date fields to tomorrow's date
  document.querySelectorAll('input[type="date"]').forEach(input => {
    input.setAttribute('min', tomorrowDate);
  });
</script>

  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>