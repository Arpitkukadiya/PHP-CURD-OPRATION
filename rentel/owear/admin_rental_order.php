<?php

@include 'config.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // If no admin session, redirect to login page
    header('location:login.php');
    exit; // Prevent further code execution
}

// Update payment status of rental order
if(isset($_POST['update_order'])){

   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_orders = $conn->prepare("UPDATE `rental_orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'Payment status has been updated!';
}

// Delete rental order
if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `rental_orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:admin_rental_order.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Rental Order Management - EYEWEAR</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="admin_styles.css">
   <style>
         .card-header {
         background-color: #007bff;
         color: white;
         font-weight: bold;
         padding: 1rem;
         border-radius: 8px 8px 0 0;
      }
      p{
         font-size: 0.9rem;
      }
   </style>
</head>
<body>
  
   <!-- Sidebar -->
   <div class="d-flex">
   <?php include 'admin_navbar.php' ?>


      <!-- Content -->
      <div class="content p-4 w-100">
         <section class="rental-orders container-fluied mx-3">
         <div class="d-flex justify-content-between align-items-center">

         <h3>Rental order Management</h3>

         <!-- Filter by payment status -->
         <form method="GET" class="d-flex mb-2">
            <select name="filter_payment_status" class="form-control mr-2">
               <option value="" disabled selected>Filter by Payment Status</option>
               <option value="pending">Pending</option>
               <option value="shipping">Shipping</option>
               <option value="delivered">Delivered</option>
               <option value="out for pickup">Out for Pickup</option>
               <option value="return">Return</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
         </form>
</div>
         <div class="row">
               <?php
                  // Check if a filter is set
                  if (isset($_GET['filter_payment_status']) && !empty($_GET['filter_payment_status'])) {
                     $filter_payment_status = $_GET['filter_payment_status'];
                     // Modify the query to fetch orders with the selected payment status and join with rental_payments table to get payment method
                     $select_orders = $conn->prepare("SELECT r.*, rp.payment_method FROM `rental_orders` r LEFT JOIN `rental_payments` rp ON r.id = rp.order_id WHERE r.payment_status = ? ORDER BY r.id DESC");
                     $select_orders->execute([$filter_payment_status]);
                  } else {
                     // Default query to fetch all orders and join with rental_payments table to get payment method
                     $select_orders = $conn->prepare("SELECT r.*, rp.payment_method FROM `rental_orders` r LEFT JOIN `rental_payments` rp ON r.id = rp.order_id ORDER BY r.id DESC");
                     $select_orders->execute();
                  }

                  if($select_orders->rowCount() > 0){
                     while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
               ?>
               <div class="col-md-4 mb-4">
                  <div class="card">
                     <div class="card-header">
                        Order: <?= $fetch_orders['id']; ?>
                     </div>

                     <div class="card-body">
                        <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                        <p>Email: <span><?= $fetch_orders['email']; ?></span></p>
                        <p>Contact No: <span><?= $fetch_orders['number']; ?></span></p>
                        <p>Address:<br> <span><?= $fetch_orders['address']; ?></span></p>
                        <p>Total Products:<br> <span><?= $fetch_orders['total_products']; ?></span></p>
                        <p>Total Price: <span>₹<?= $fetch_orders['total_price']; ?>/-</span></p>
                        <p>Start Date: <span><?= $fetch_orders['start_date']; ?></span></p>
                        <p>End Date: <span><?= $fetch_orders['end_date']; ?></span></p>

                        <!-- Display the payment method -->
                        <p>Payment Method: <span><?= $fetch_orders['payment_method'] ? $fetch_orders['payment_method'] : 'Not Available'; ?></span></p>

                        <form action="" method="POST">
                           <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                           <select name="update_payment" class="form-control mb-2">
                              <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                              <option value="pending">Pending</option>
                              <option value="shipping">Shipping</option>
                              <option value="delivered">Delivered</option>                        
                              <option value="out for pickup">Out for Pickup</option>                           

                              <option value="return">Return</option>                            </select>
                              <input type="submit" name="update_order" class="btn btn-primary w-100" value="Update">
                        </form>
                     </div>
                  </div>
               </div>
               <?php
                  }
               } else {
                  echo '<p class="empty">No rental orders placed yet!</p>';
               }
               ?>
            </div>
         </section>
      </div>
   </div>
</body>
</html>
