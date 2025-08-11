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
   <style>
    .box-container .box img{
   width: 100%;
  height: 12vw;
   margin-bottom: 1rem;
}
/* General styling for the page */
body {
   font-family: 'Roboto', sans-serif;
   background-color: #f4f7f8;
   margin: 0;
   padding: 0;
}

/* Category Section Styling */
.p-category {
   display: flex;
  
   justify-content: space-around;
   background-color: #1a1a1a;
   padding: 20px 0;
   padding-top:100px;
}

.p-category a {
   color: #fff;
   text-decoration: none;
   font-size: 1.2rem;
   font-weight: bold;
   padding: 10px 20px;
   border-radius: 30px;
   background-color: #007bff;

   transition: background-color 0.3s ease, color 0.3s ease;
}

.p-category a:hover {
   background-color: #0056b3;
   color: #fff;
}

/* Product Section Styling */
.products {
   padding: 20px;
   text-align: center;
}

.products .title {
   font-size: 2.5rem;
   color: #333;
   margin-bottom: 30px;
   font-weight: bold;
}

.box-container {
   display: grid;
   grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
   gap: 20px;
   padding: 20px;
}

.box {
   background-color: #fff;
   border-radius: 10px;
   box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
   padding: 20px;
   transition: transform 0.3s ease;
}

.box:hover {
   transform: translateY(-10px);
}

.box img {
   width: 100%;
   height: 12vw;
   object-fit: cover;
   border-radius: 10px;
   margin-bottom: 1rem;
}

.box .name {
   font-size: 1.5rem;
   font-weight: bold;
   margin-bottom: 15px;
   color: #333;
}

.box .price {
   font-size: 1.2rem;
   color: #28a745;
   font-weight: bold;
   margin-bottom: 15px;
}

.box .qty {
   width: 50px;
   padding: 5px;
   margin-bottom: 15px;
   border-radius: 5px;
   border: 1px solid #ddd;
}

.box .option-btn,
.box .btn {
   display: inline-block;
   padding: 10px 20px;
   border-radius: 5px;
   background-color: #007bff;
   color: #fff;
   font-size: 1rem;
   text-decoration: none;
   cursor: pointer;
   transition: background-color 0.3s ease;
}

.box .option-btn:hover,
.box .btn:hover {
   background-color: #0056b3;
}

/* Styling for empty state */
.empty {
   color: #d9534f;
   font-size: 1.5rem;
   font-weight: bold;
}


      </style>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="p-category">

   <a href="category.php?category=CRICKET">CRICKET</a>
   <a href="category.php?category=FOOTBALL">FOOTBALL</a>
   <a href="category.php?category=HOCKEY">HOCKEY</a>
   <a href="category.php?category=BADMINTON">BADMINTON</a>
  
</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products`");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <!--div class="price">₹<span><?= $fetch_products['price']; ?></span>/-</div-->
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?>   ₹<?= $fetch_products['price']; ?>/-</div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
      <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="btn">product information</a>
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>