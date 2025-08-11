<?php

if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}

?><header class="header">
<div class="flex">
   <a href="home.php" class="logo"> sports world<span>.</span></a>

   <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="shop.php">Food-menu</a>
      <a href="orders.php">Orders</a>
      <a href="user_order.php">Order Status</a>
      <a href="contact.php">Feedback</a>
   </nav>

   <div class="icons">
      <a href="search_page.php" class="fas fa-search"></a>
      <?php
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
         $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
         $count_wishlist_items->execute([$user_id]);
      ?>
      <a href="wishlist.php"><i class="fas fa-heart"></i>Wishlist<span>(<?= $count_wishlist_items->rowCount(); ?>)</span></a>
      <a href="cart.php"><i class="fas fa-shopping-cart"></i>Cart<span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
   </div>

   <div class="profile">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <p><?= $fetch_profile['name']; ?></p>
      <a href="logout.php" class="delete-btn">Logout</a>
   </div>
</div>

<style>
   /* General Reset */
   * {
      margin: 0px;
      padding: 0px;
      box-sizing: border-box;
   }

   body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f7f8;
      color: #333;
      padding-top: 80px; /* Space for fixed header */
   }

   /* Header Section */
   .header {
      background: #1a1a1a;
      padding: 20px 0;
      position: fixed;
      width: 100%;
      top: 0;
      left: 0;
      z-index: 1000;
   }

   .header .flex {
      display: flex;
      align-items: center;
      justify-content: space-between;
      max-width: 3500px;
      margin: 0 auto;
   }

   .header .logo {
      font-size: 2rem;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
   }

   .header .logo span {
      color: #007bff;
   }

   .navbar {
      display: flex;
      gap: 0px; /* Space between menu items */
      margin-left: 10px; /* Add space from the logo */
   }

   .navbar a {
      color: #fff;
      font-size: 1.1rem;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 4px;
      transition: background-color 0.3s ease;
   }

   .navbar a:hover {
      background-color: #007bff;
   }

   .icons {
      display: flex;
      align-items: center;
      gap: 20px;
   }

   .icons .fas {
      font-size: 1.5rem;
      color: #fff;
      cursor: pointer;
   }

   .profile {
      display: flex;
      align-items: center;
      gap: 10px;
   }

   .profile img {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
   }

   .profile p {
      color: #fff;
      margin-right: 20px;
   }

   .profile .delete-btn {
      color: #fff;
      text-decoration: none;
      background-color: #ff3333;
      padding: 10px 15px;
      border-radius: 4px;
      transition: background-color 0.3s ease;
   }

   .profile .delete-btn:hover {
      background-color: #e60000;
   }

   /* Icons links */
   .icons a {
      color: #fff;
      text-decoration: none;
      padding: 10px;
      border-radius: 4px;
      display: flex;
      align-items: center;
      gap: 5px;
   }

   .icons a span {
      font-size: 0.9rem;
   }

   /* Mobile Responsiveness */
   @media (max-width: 768px) {
      .header .flex {
         flex-direction: column;
         align-items: flex-start;
      }

      .navbar {
         flex-direction: column;
         gap: 15px;
      }

      .profile {
         flex-direction: column;
      }

      .icons {
         gap: 10px;
      }

      .card {
         width: 100%;
      }
   }


   /*header*/
</style>
</header>
