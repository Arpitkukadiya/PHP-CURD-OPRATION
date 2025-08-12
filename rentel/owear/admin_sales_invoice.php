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
      .table th, .table td {
         vertical-align: middle;
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
      <section class="placed-orders container-fluied mx-3">
         <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="text-center">Manage Orders</h2>
            <button id="download-pdf" class="btn btn-primary download-btn">Download PDF</button>
         </div>

         <div class="table-responsive">
            <table class="table table-bordered" id="orders-table">
               <thead class="thead-dark">
                  <tr>
                     <th>Order ID</th>
                     <th>Name</th>
                     <th>Contact No</th>
                     <th>Products</th>
                     <th>Price</th>
                     <th>Orders Status</th>
                  </tr>
               </thead>
               <tbody>
                  <?php
                     // Modify the query to fetch orders in descending order based on order_id
                     $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY id DESC");
                     $select_orders->execute();
                     if($select_orders->rowCount() > 0){
                        while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
                  ?>
                           <tr>
                              <td><?= $fetch_orders['id']; ?></td>
                              <td><?= $fetch_orders['name']; ?></td>
                              <td><?= $fetch_orders['number']; ?></td>
                              <td><?= $fetch_orders['total_products']; ?></td>
                              <td><?= $fetch_orders['total_price']; ?></td>
                              <td><?= $fetch_orders['payment_status']; ?></td>
                           </tr>
                     <?php
                        }
                     } else {
                        echo '<tr><td colspan="7" class="empty">No orders placed yet!</td></tr>';
                     }
                  ?>
               </tbody>
            </table>
         </div>
      </section>
   </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- jsPDF Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- jsPDF autoTable Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<script>
   // Function to download table data as PDF
   document.getElementById('download-pdf').addEventListener('click', function () {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      
      // Add Title
      doc.setFontSize(18);
      doc.text("Order Management Report", 14, 20);

      // Adjust the table position and margin to avoid overflow
      doc.autoTable({
         html: '#orders-table',
         startY: 30, // Position the table below the title
         theme: 'grid', // Use grid style for table
         headStyles: { fillColor: [41, 128, 185] }, // Header color
         bodyStyles: { fontSize: 10 }, // Body text size
         columnStyles: { 
            0: { cellWidth: 25 }, 
            1: { cellWidth: 30 }, 
            3: { cellWidth: 30 }, 
            4: { cellWidth: 30 }, 
            5: { cellWidth: 30 }, 
            6: { cellWidth: 30 },
         },
         margin: { top: 30, left: 2, right: 2, bottom: 10 }, // Added margins to avoid overflow
         pageBreak: 'auto', // Automatic page breaks
         rowPageBreak: 'auto', // Allow page break within rows if needed
      });

      // Add Footer (Page Number)
      doc.setFontSize(10);
      doc.text('Generated on: ' + new Date().toLocaleString(), 14, doc.internal.pageSize.height - 10);
      doc.text('Page ' + doc.internal.getNumberOfPages(), doc.internal.pageSize.width - 30, doc.internal.pageSize.height - 10, {align: 'right'});

      // Save PDF
      doc.save('orders_management_report.pdf');
   });
</script>

</body>
</html>
