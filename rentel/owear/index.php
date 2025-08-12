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
  $p_qty = $_POST['p_qty'];
  // $p_qty = filter_var($p_qty, FILTER_SANITIZE_NUMBER_INT);

  $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
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

    $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
    $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
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
  <title>OCCASION WEAR Website</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
   body {
  font-family: "Arial", sans-serif;
  background-color: #e8f1fc; /* Light blue background */
}


.card {
  border-radius: 30px;
  background-color: #ffffff; /* White card background */
  box-shadow: 0 0 15px 0 rgba(0, 0, 0, 0.1), 0 0 0 0 rgba(0, 0, 0, 0.15); 
  transition: all 0.3s ease-in-out;
}

.card img {
  width: 100%;
  height: 350px;
  border-radius: 30px;
  padding: -20px;
}

.products {
  margin-top: 10px;
}

.title {
  font-size: 34px;
  color:#000000; /* Blue title color */
  margin-bottom: 25px;
  text-align: center;
  font-weight: 700;
}

.card:hover {
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
}

.footer {
  padding: 10px 0;
  background-color:#000000; /* Blue footer background */
  color: #fff;
  text-align: center;
}

.list-inline-item a {
  margin-right: 15px;
  color:#000000; /* Blue color for links */
}

.text-center {
  margin-top: 10px;
  color: #0056b3; /* Blue text center */
}


.carousel-item img {
  width: 100%;
  height: auto;
}

.card .btn {
  background-color:rgb(0, 4, 9); /* Blue buttons */
  color: #fff;
  border-radius: 20px;
  width: 100%;
  padding: 10px;
  border: none;
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
      <a class="navbar-brand" href="index.php">OCCASION WEAR - SALES</a>
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
    <a class="nav-link" href="rental_index.php">Rental Wear</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="cart.php">Cart</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="order_history.php">Order History</a>
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
        <img class="d-block w-100" src="img/side1.jpg" alt="First slide">
        <div class="carousel-caption d-none d-md-block">
        </div>
      </div>
     
    </div>
   
  </div>

  <section class="products container-fluied p-3 mx-5">
    <h1 class="title">Latest Products</h1>
    <div class="row">
    <?php
$search_query = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
  $search_query = trim($_GET['search']);
  $query = "SELECT * FROM `products` WHERE name LIKE ? OR category LIKE ?";
  $select_products = $conn->prepare($query);
  $search_param = "%" . $search_query . "%";
  $select_products->execute([$search_param, $search_param]);
} else {
  $query = "SELECT * FROM `products` LIMIT 6";
  $select_products = $conn->prepare($query);
  $select_products->execute();
}

   
      if ($select_products->rowCount() > 0) {
        while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
      ?>
          <div class="col-md-3 mb-4">
  <div class="card h-100 shadow-lg border-0">
    <div class="position-relative">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" class="card-img-top p-3 rounded" alt="<?= $fetch_products['name']; ?>">
      <span class="badge badge-pill badge-warning position-absolute p-2 shadow" style="top: 10px; right: 10px; font-size: 1rem;">
        ₹<?= $fetch_products['price']; ?>
      </span>
    </div>
    <div class="card-body">
      <h5 class="card-title font-weight-bold text-dark"><?= $fetch_products['name']; ?></h5>
      <p class="card-text text-muted"><?= $fetch_products['details']; ?></p>
      <p class="category font-italic text-secondary">Category: <?= $fetch_products['category']; ?></p>
      <form action="" method="POST">
        <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
        <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
        <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
        <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
        <input type="number" name="p_qty" value="1" min="1" class="form-control mb-2 d-none">
        <button type="submit" class="btn btn-dark btn-block rounded-pill shadow-sm" name="add_to_cart">
          <i class="fas fa-shopping-cart"></i> Add to Cart
        </button>
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
  </section>

  
  <footer class="footer text-white">
    <div class="text-center text-white p-3 h6">
      &copy; 2025 OCCASION WEAR. All rights reserved.
    </div>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
      var loginLink = document.getElementById("loginLink");
      var logoutLink = document.getElementById("logoutLink");

      if (isLoggedIn) {
        loginLink.style.display = "none";
        logoutLink.style.display = "block";
      } else {
        loginLink.style.display = "block";
        logoutLink.style.display = "none";
      }
    });

    function logout() {
      if (confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
      }
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>