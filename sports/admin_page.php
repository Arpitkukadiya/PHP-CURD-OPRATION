<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <style>
       body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f3e4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Sidebar */
        .sidebar {
            background-color: #9b2a2f;
            position: fixed;
            padding: 30px 20px;
            height: 100vh;
            width: 250px;
            color: #fff;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.4);
            top: 70px;
            bottom: 0;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar a {
            color: #dcdcdc;
            font-size: 1rem;
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background: #ffcc00;
            color: #fff;
        }

        /* Content */
        .content {
            margin-left: 270px;
            padding: 40px;
            padding-top: 110px;
        }

        .title {
            font-size: 2.5rem;
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
        }
      /* General Reset */
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: Arial, sans-serif;
         background-color: #111; /* Dark background for black theme */
         color: #fff; /* White text for contrast */
      }

      /* Dashboard Section */
      .dashboard {
         padding: 20px;
         
    padding-left:300px;
         display: flex;
         flex-direction: column;
         align-items: center;
         justify-content: center;
         text-align: center;
         min-height: 100vh; /* Ensure section fills the screen */
      }

      /* Button Styles */
      .btn1,
      .delete-btn,
      .option-btn {
         display: block;
         width: 200px;
         margin-top: 1rem;
         border-radius: .5rem;
         color: var(--black);
         font-size: 1.5rem;
         padding: 1.3rem;
         text-transform: capitalize;
         cursor: pointer;
         text-align: center;
         transition: background-color 0.3s;
      }

      .btn1 {
         background-color: #1a1a1a; /* Dark grey button background */
         color: #fff; /* White text */
         border: 2px solid #333; /* Slightly lighter border for contrast */
      }

      .delete-btn1 {
         background-color: #cc3333; /* Red background for delete */
         color: #fff;
      }

      .option-btn1 {
         background-color: #ff9900; /* Orange background for options */
         color: #fff;
      }

      .btn1:hover,
      .delete-btn1:hover,
      .option-btn1:hover {
         background-color: #333; /* Dark grey for hover effect */
         color: #fff; /* White text on hover */
      }

      /* Flexbox Layout for Buttons */
      .flex-btn1 {
         display: flex;
         flex-wrap: wrap;
         gap: 1rem;
         justify-content: center;
      }

      .flex-btn1 > * {
         flex: 1;
         min-width: 200px; /* Prevent the buttons from being too small */
      }

      /* Background Image */
      .vk1 {
         width: 100%;
         position: absolute;
         z-index: -2;
         opacity: 0.2; /* Slightly reduce opacity for background */
      }

      /* Title Styles */
      .title {
         color: black; /* White color for the title */
         font-size: 2.5rem;
         margin-bottom: 20px;
      }

      /* Additional Styling for Buttons */
      a {
         text-decoration: none; /* Remove underline from links */
      }

      a:focus {
         outline: none; /* Remove focus outline */
      }
   </style>

   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
   
<img class="vk1" src="home-bg1.jpg" alt="foodzone1">
<?php include 'admin_header.php'; ?>

<section class="dashboard">

   <h1 class="title">Dashboard</h1>

   <div class="flex-btn1">
   
   <?php
   // Total pending orders
   $total_pendings = 0;
   $select_pendings = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
   $select_pendings->execute(['pending']);
   while($fetch_pendings = $select_pendings->fetch(PDO::FETCH_ASSOC)){
      $total_pendings += $fetch_pendings['total_price'];
   }
   ?>
   <a href="admin_orders.php" class="btn1"><?= $total_pendings; ?>/-<br>Pending Orders</a>

   <?php
   // Total completed orders
   $total_completed = 0;
   $select_completed = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = ?");
   $select_completed->execute(['completed']);
   while($fetch_completed = $select_completed->fetch(PDO::FETCH_ASSOC)){
      $total_completed += $fetch_completed['total_price'];
   }
   ?>
   <a href="admin_orders.php" class="btn1"><?= $total_completed; ?>/-<br>Completed Orders</a>

   <?php
   // Total orders
   $select_orders = $conn->prepare("SELECT * FROM `orders`");
   $select_orders->execute();
   $number_of_orders = $select_orders->rowCount();
   ?>
   <a href="admin_orders.php" class="btn1"><?= $number_of_orders; ?><br>See Orders</a>

   <?php
   // Total products
   $select_products = $conn->prepare("SELECT * FROM `products`");
   $select_products->execute();
   $number_of_products = $select_products->rowCount();
   ?>
   <a href="admin_products.php" class="btn1"><?= $number_of_products; ?><br>See Products</a>

   <?php
   // Total users
   $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
   $select_users->execute(['user']);
   $number_of_users = $select_users->rowCount();
   ?>
   <a href="admin_users.php" class="btn1"><?= $number_of_users; ?><br>See User Accounts</a>

   <?php
   // Total admin accounts
   $select_admins = $conn->prepare("SELECT * FROM `users` WHERE user_type = ?");
   $select_admins->execute(['admin']);
   $number_of_admins = $select_admins->rowCount();
   ?>
   <a href="admin_accounts.php" class="btn1"><?= $number_of_admins; ?><br>Admin Accounts</a>

   <?php
   // Total messages
   $select_messages = $conn->prepare("SELECT * FROM `message`");
   $select_messages->execute();
   $number_of_messages = $select_messages->rowCount();
   ?>
   <a href="admin_contacts.php" class="btn1"><?= $number_of_messages; ?><br>Messages/Feedback</a>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
