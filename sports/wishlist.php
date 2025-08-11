<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

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

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE id = ?");
   $delete_wishlist_item->execute([$delete_id]);
   header('location:wishlist.php');

}

if(isset($_GET['delete_all'])){

   $delete_wishlist_item = $conn->prepare("DELETE FROM `wishlist` WHERE user_id = ?");
   $delete_wishlist_item->execute([$user_id]);
   header('location:wishlist.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <style>
      body {
         background-color: #f4f7f8;
         font-family: 'Roboto', sans-serif;
         margin: 0;
         padding: 0;
      }

      .title {
         text-align: center;
         font-size: 2.5rem;
         color: #1a73e8;
         margin: 20px 0;
      }

      .wishlist {
         padding: 20px;
         padding-top: 70px;
      }
      .wishlist .box-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 20px auto;
}

.wishlist .box {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    padding: 20px;
    text-align: center;
    position: relative;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.wishlist .box:hover {
    transform: translateY(-10px);
    box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.2);
}

.wishlist .box img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
}

.wishlist .box .name {
    font-size: 1.4rem;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.wishlist .box .price {
    font-size: 1.2rem;
    color: #ff5722;
    margin-bottom: 15px;
}

.wishlist .box .qty {
    width: 60px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
    text-align: center;
}

.wishlist .box .btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 1rem;
    color: #fff;
    background: #1a73e8;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}

.wishlist .box .btn:hover {
    background: #0056b3;
}

.wishlist .box .fas {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 1.5rem;
    color: #ff5722;
    cursor: pointer;
    transition: color 0.3s ease;
}

.wishlist .box .fas:hover {
    color: #e91e63;
}

      .wishlist .box .fas {
         position: absolute;
         top: 10px;
         right: 10px;
         font-size: 1.5rem;
         color: #ff5722;
         cursor: pointer;
         transition: color 0.3s ease;
      }

      .wishlist .box .fas:hover {
         color: #e91e63;
      }

      .wishlist-total {
         text-align: center;
         margin-top: 20px;
      }

      .wishlist-total p {
         font-size: 1.5rem;
         font-weight: bold;
         color: #333;
         margin-bottom: 15px;
      }

      .option-btn, .delete-btn {
         display: inline-block;
         padding: 10px 20px;
         font-size: 1rem;
         color: #fff;
         background: #007bff;
         border: none;
         border-radius: 5px;
         cursor: pointer;
         text-decoration: none;
         transition: background-color 0.3s ease;
         margin: 5px;
      }

      .option-btn:hover {
         background: #0056b3;
      }

      .delete-btn {
         background: #ff5722;
      }

      .delete-btn:hover {
         background: #e91e63;
      }

      .delete-btn.disabled {
         background: #ccc;
         pointer-events: none;
      }

      .empty {
         text-align: center;
         font-size: 1.5rem;
         color: #777;
      }
   </style>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>wishlist</title>

   <!-- font awesome cdn link-->  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="wishlist">

   <h1 class="title">products added</h1>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_wishlist = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
      $select_wishlist->execute([$user_id]);
      if($select_wishlist->rowCount() > 0){
         while($fetch_wishlist = $select_wishlist->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="POST" class="box">
       <img src="uploaded_img/<?= $fetch_wishlist['image']; ?>" alt="">
      <div class="name"><?= $fetch_wishlist['name']; ?></div>
      <div class="price">₹<?= $fetch_wishlist['price']; ?>/-</div>
      <input type="number" min="1" value="1" class="qty" name="p_qty">
      <input type="hidden" name="pid" value="<?= $fetch_wishlist['pid']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_wishlist['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_wishlist['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_wishlist['image']; ?>">
      <input type="submit" value="add to cart" name="add_to_cart" class="btn">
   </form>
   <?php
      $grand_total += $fetch_wishlist['price'];
      }
   }else{
      echo '<p class="empty">your wishlist is empty</p>';
   }
   ?>
   </div>

   <div class="wishlist-total">
      <p>grand total : <span>₹<?= $grand_total; ?>/-</span></p>
      <a href="shop.php" class="option-btn">continue shopping</a>
      <a href="wishlist.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>">delete all</a>
   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
