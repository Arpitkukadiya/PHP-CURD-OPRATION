<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
  header('location:login.php');
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $conn->prepare("DELETE FROM `rental_cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:rental_cart.php');
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `rental_cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:rental_cart.php');
}

if (isset($_POST['add_to_cart'])) {
  $pid = $_POST['pid'];
  $p_name = filter_var($_POST['p_name'], FILTER_SANITIZE_STRING);
  $p_price = filter_var($_POST['p_price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $p_image = filter_var($_POST['p_image'], FILTER_SANITIZE_STRING);
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  
  $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
  $total_price = $p_price * $days;
  
  $check_cart_numbers = $conn->prepare("SELECT * FROM `rental_cart` WHERE name = ? AND user_id = ?");
  $check_cart_numbers->execute([$p_name, $user_id]);

  if ($check_cart_numbers->rowCount() > 0) {
    $message[] = 'Already added to cart!';
  } else {
    $insert_cart = $conn->prepare("INSERT INTO `rental_cart`(user_id, pid, name, price, start_date, end_date, image) VALUES(?,?,?,?,?,?,?)");
    $insert_cart->execute([$user_id, $pid, $p_name, $total_price, $start_date, $end_date, $p_image]);
    $message[] = 'Added to cart!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OCCASION WEAR</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Arial', sans-serif;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
    .product img {
      border-radius: 10px;
      transition: transform 0.3s ease;
    }
    .product img:hover {
      transform: scale(1.1);
    }
    .btn-custom {
      background-color:rgb(0, 0, 0);
      color: white;
      transition: background-color 0.3s ease;
    }
    .btn-custom:hover {
      background-color:rgb(41, 41, 41);
      color: white;
    }
    .btn-danger:hover {
      background-color: #e60000;
    }
    .title {
      font-size: 2rem;
      font-weight: 600;
      color: #343a40;
      margin-bottom: 30px;
    }
    .card-header {
      background-color:rgb(0, 0, 0);
      color: white;
      font-size: 1.25rem;
      border-radius: 30px;
    }
    .card-body p {
      font-size: 1.2rem;
    }
    .product {
      background-color: #ffffff;
      border-radius: 30px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
   
    .product .details {
      padding-left: 20px;
    }
    .product .details h5 {
      font-size: 1.2rem;
      font-weight: 500;
    }
    .product .details p {
      margin-bottom: 10px;
    }
    .product .remove-btn {
      margin-top: 10px;
      background-color: #ff6f61;
      border: none;
      color: white;
      border-radius: 5px;
      padding: 8px 15px;
      font-size: 0.9rem;
    }
    .product .remove-btn:hover {
      background-color: #e60000;
    }
  </style>
</head>
<body>

<?php include 'rental_navbar.php' ?>

<div class="container mt-3">
  <h1 class="title text-center">Rental Cart</h1>
  <div class="row">
    <div class="col-lg-8">
      <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `rental_cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if ($select_cart->rowCount() > 0) {
        while ($fetch_rental_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
          $days = (strtotime($fetch_rental_cart['end_date']) - strtotime($fetch_rental_cart['start_date'])) / (60 * 60 * 24);
          $sub_total = $fetch_rental_cart['price'] * $days;
          $grand_total += $sub_total;
      ?>
      <div class="product d-flex align-items-center mb-4 p-4 shadow-sm rounded">
        <img src="uploaded_img/<?= $fetch_rental_cart['image']; ?>" width="120" alt="<?= $fetch_rental_cart['name']; ?>">
        <div class="details">
          <h5 class="mb-2"><?= $fetch_rental_cart['name']; ?></h5>
          <p class="mb-2">₹<?= $fetch_rental_cart['price']; ?>/- per day</p>
          <p class="mb-2">Rental Period: <?= $fetch_rental_cart['start_date']; ?> to <?= $fetch_rental_cart['end_date']; ?> (<?= $days; ?> days)</p>
          <p class="fw-bold text-success mb-3">Total: ₹<?= $sub_total; ?>/-</p>
          <a href="rental_cart.php?delete=<?= $fetch_rental_cart['id']; ?>" class="remove-btn">Remove</a>
        </div>
      </div>
      <?php
        }
      } else {
        echo '<p class="text-center">Your rental cart is empty</p>';
      }
      ?>
    </div>
    
    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-header text-center">
          <h4>Bill Summary</h4>
        </div>
        <div class="card-body">
          <?php
          $select_cart = $conn->prepare("SELECT * FROM `rental_cart` WHERE user_id = ?");
          $select_cart->execute([$user_id]);
          if ($select_cart->rowCount() > 0) {
            $grand_total = 0;
            while ($fetch_rental_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
              $days = (strtotime($fetch_rental_cart['end_date']) - strtotime($fetch_rental_cart['start_date'])) / (60 * 60 * 24);
              $sub_total = $fetch_rental_cart['price'] * $days;
              $grand_total += $sub_total;
          ?>
          <div class="mb-2">
            <p class="d-flex justify-content-between">
              <span><?= $fetch_rental_cart['name']; ?></span>
              <span>₹<?= $fetch_rental_cart['price']; ?> x <?= $days; ?> days</span>
            </p>
            <p class="d-flex justify-content-between">
              <strong>Subtotal:</strong>
              <strong>₹<?= $sub_total; ?>/-</strong>
            </p>
          </div>
          <?php
            }
          }
          ?>
          <hr>
          <p class="d-flex justify-content-between fw-bold fs-5">
            <span>Grand Total:</span>
            <span>₹<?= $grand_total; ?>/-</span>
          </p>
          <a href="rental_checkout.php" class="btn btn-custom btn-block mb-2">Proceed to Checkout</a>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
