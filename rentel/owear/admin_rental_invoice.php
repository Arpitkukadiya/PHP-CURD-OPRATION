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
      .table th, .table td {
         vertical-align: middle;
      }

      .table th {
         background-color: #007bff;
         color: white;
         font-weight: bold;
      }

      .table td {
         font-size: 1rem;
      }

      .btn-custom {
         margin-right: 10px;
      }

      .empty {
         text-align: center;
         font-size: 1.2rem;
         color: #6c757d;
      }
   </style>
   <!-- Include jsPDF and jsPDF autotable plugin -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.20/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
  
   <!-- Sidebar -->
   <div class="d-flex">
      <?php include 'admin_navbar.php' ?>

      <!-- Content -->
      <div class="content p-4 w-100">
         <section class="rental-orders container-fluied mx-2">
         <div class="d-flex justify-content-between align-items-center mb-4">
   <h2 class="mb-0">Rental Order Management</h2>
   <button id="downloadPdf" class="btn btn-success">Download PDF</button>
</div>
            <table id="rentalOrdersTable" class="table table-bordered table-hover">
               <thead>
                  <tr>
                     <th>Order ID</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Contact No</th>
                     <th>Total Products</th>
                     <th>Total Price</th>
                     <th>Start Date</th>
                     <th>End Date</th>
                     <th>Orders Status</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                     // Modified SQL query to select orders in descending order
                     $select_orders = $conn->prepare("SELECT * FROM `rental_orders` ORDER BY id DESC");
                     $select_orders->execute();
                     if($select_orders->rowCount() > 0){
                        while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                  ?>
                  <tr>
                     <td><?= $fetch_orders['id']; ?></td>
                     <td><?= $fetch_orders['name']; ?></td>
                     <td><?= $fetch_orders['email']; ?></td>
                     <td><?= $fetch_orders['number']; ?></td>
                     <td><?= $fetch_orders['total_products']; ?></td>
                     <td><?= $fetch_orders['total_price']; ?>/-</td>
                     <td><?= $fetch_orders['start_date']; ?></td>
                     <td><?= $fetch_orders['end_date']; ?></td>
                        <td><?= $fetch_orders['payment_status']; ?></td>
                  </tr>
                  <?php
                        }
                     } else {
                        echo '<tr><td colspan="9" class="text-center">No rental orders placed yet!</td></tr>';
                     }
                  ?>
               </tbody>
            </table>
         </section>
      </div>
   </div>

   <!-- JavaScript to generate the PDF -->
   <script>
      document.getElementById('downloadPdf').addEventListener('click', function () {
         const { jsPDF } = window.jspdf;

         const doc = new jsPDF();

         // Add header
         doc.setFontSize(16);
         doc.text('Rental Order Management', 105, 20, null, null, 'center');
         doc.setFontSize(12);
         doc.text('Order List - EYEWEAR', 105, 30, null, null, 'center');

         // Generate the table for PDF
         doc.autoTable({ 
            html: '#rentalOrdersTable', 
            startY: 40, 
            theme: 'grid',
            margin: { top: 10, left: 10, right: 10, bottom: 10 },
            headStyles: {
                fillColor: [0, 123, 255], 
                textColor: 255,
            }
         });

         // Add footer
         doc.setFontSize(10);
         doc.text('Page ' + doc.internal.getNumberOfPages(), 180, doc.internal.pageSize.height - 10);

         // Save the PDF
         doc.save('rental_orders.pdf');
      });
   </script>
</body>
</html>
