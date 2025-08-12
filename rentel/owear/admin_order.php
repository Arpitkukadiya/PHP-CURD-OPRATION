<?php

@include 'config.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit; 
}

if(isset($_POST['update_order'])){
   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'Payment has been updated!';
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:admin_order.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Order Management - EYEWEAR</title>
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="admin_styles.css">
   <style>
      .card {
         border: none;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
         border-radius: 8px;
      }

      .card:hover {
         transform: translateY(-5px);
         box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
      }

      .card-header {
         background-color: #007bff;
         color: white;
         font-weight: bold;
         padding: 1rem;
         border-radius: 8px 8px 0 0;
      }

      .card-body {
         padding: 1.5rem;
      }

      .order-details p {
         margin-bottom: 10px;
         font-size: 1rem;
         color: #333;
      }

      .btn-custom {
         background-color: #28a745;
         color: white;
         padding: 0.5rem 1.5rem;
         border-radius: 5px;
      }

      .btn-custom:hover {
         background-color: #218838;
      }

      .btn-danger-custom {
         background-color: #dc3545;
         color: white;
         padding: 0.5rem 1.5rem;
         border-radius: 5px;
      }

      .btn-danger-custom:hover {
         background-color: #c82333;
      }

      .empty {
         text-align: center;
         font-size: 1.2rem;
         color: #6c757d;
      }
   </style>
</head>
<body>

<!-- Sidebar -->
<div class="d-flex">
   <?php include 'admin_navbar.php' ?>

   <!-- Content -->
   <div class="content p-4 w-100">
      <section class="placed-orders container-fluied mx-5">
         <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-center">Manages Orders</h2>

            <!-- Filter by payment status -->
            <form method="GET" class="d-flex">
               <select name="filter_payment_status" class="form-control mr-2">
                  <option value="" disabled selected>Filter by Status</option>
                  <option value="pending">Pending</option>
                  <option value="shipping">Shipping</option>
                  <option value="delivered">Delivered</option>
               </select>
               <button type="submit" class="btn btn-primary">Filter</button>
            </form>
         </div>

         <div class="row">
            <?php
               // Check if a filter is set
               if (isset($_GET['filter_payment_status']) && !empty($_GET['filter_payment_status'])) {
                  $filter_payment_status = $_GET['filter_payment_status'];
                  // Modify the query to fetch orders with the selected payment status
                  $select_orders = $conn->prepare("SELECT * FROM `orders` o LEFT JOIN `payments` p ON o.id = p.order_id WHERE o.payment_status = ? ORDER BY o.id DESC");
                  $select_orders->execute([$filter_payment_status]);
               } else {
                  // Default query to fetch all orders
                  $select_orders = $conn->prepare("SELECT * FROM `orders` o LEFT JOIN `payments` p ON o.id = p.order_id ORDER BY o.id DESC");
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
                        <div class="order-details">
                           <p>User ID: <span><?= $fetch_orders['user_id']; ?></span></p>
                           <p>Placed On: <span><?= $fetch_orders['placed_on']; ?></span></p>
                           <p>Name: <span><?= $fetch_orders['name']; ?></span></p>
                           <p>Contact No: <span><?= $fetch_orders['number']; ?></span></p>
                           <p>Address:<br> <span><?= $fetch_orders['address']; ?></span></p>
                           <p>Total Products:<br> <span><?= $fetch_orders['total_products']; ?></span></p>
                           <p>Total Price: <span>₹<?= $fetch_orders['total_price']; ?>/-</span></p>
                           <p>Payment Method: <span><?= $fetch_orders['payment_method'] ? $fetch_orders['payment_method'] : 'Not Available'; ?></span></p>
                        </div>

                        <form action="" method="POST">
                           <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
                           <select name="update_payment" class="form-control mb-3">
                              <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
                              <option value="pending">Pending</option>
                              <option value="shipping">Shipping</option>
                              <option value="delivered">Delivered</option>       
                           </select>

                           <div class="d-flex justify-content-between">
                              <input type="submit" name="update_order" class="btn btn-custom w-100" value="Update">
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            <?php
               }
            } else {
               echo '<p class="empty">No orders placed yet!</p>';
            }
            ?>
         </div>
      </section>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
