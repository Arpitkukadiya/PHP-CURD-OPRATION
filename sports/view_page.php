<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>quick view</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
 <style>
    body {
         font-family: 'Roboto', sans-serif;
         margin: 0;
         padding: 0;
         background-color: #f4f7f8;
      }

      .title {
         text-align: center;
         font-size: 2.5rem;
         margin: 20px 0;
         color: #333;
      }

      .quick-view {
         max-width: 1200px;
         margin: 20px auto;
         padding: 20px;
         background: #fff;
         border-radius: 10px;
         
         padding-top:100px;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .box {
         display: flex;
         flex-wrap: wrap;
         gap: 20px;
         align-items: center;
      }

      .box img {
         width: 40%;
         border-radius: 10px;
         box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .box .name {
         font-size: 1.8rem;
         font-weight: bold;
         color: #333;
      }

      .box .details {
         font-size: 1.2rem;
         margin: 10px 0;
         color: #666;
      }

      .box .price {
         font-size: 1.5rem;
         color: #1a73e8;
         font-weight: bold;
         margin: 10px 0;
      }

      .box .qty {
         width: 60px;
         padding: 5px;
         border: 1px solid #ccc;
         border-radius: 5px;
         margin-right: 10px;
      }

      .btn,
      .option-btn {
         display: inline-block;
         padding: 10px 20px;
         margin: 10px 5px 0 0;
         background: linear-gradient(45deg, #007bff, #0056b3);
         color: #fff;
         text-decoration: none;
         border-radius: 5px;
         font-size: 1rem;
         transition: 0.3s;
         text-align: center;
      }

      .btn:hover,
      .option-btn:hover {
         background: linear-gradient(45deg, #0056b3, #007bff);
      }

      .btn {
         background: #1a73e8;
      }

      .option-btn {
         background: #ff9800;
      }

      .empty {
         text-align: center;
         font-size: 1.5rem;
         color: #999;
         margin: 20px 0;
      }
   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="quick-view">

   <h1 class="title">quick view</h1>

   <?php
      $pid = $_GET['pid'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$pid]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
<!--      <div class="price">₹<span><?= $fetch_products['price']; ?></span>/-</div>-->
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?>    ₹<?= $fetch_products['price']; ?>/-</div>
      <div class="name"><?= $fetch_products['name']; ?></div>
      <div class="details"><?= $fetch_products['details']; ?></div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
      <a href="shop.php" class="btn">back to home</a>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products added yet!</p>';
      }
   ?>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>