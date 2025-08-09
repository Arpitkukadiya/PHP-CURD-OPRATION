   <!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Order Management - EYEWEAR</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="admin_styles.css">
      <style>
        /* Table Styling */
.table {
   width: 70%; /* Make the table take up the full width */
   margin-top: 20px; /* Add some space at the top of the table */
   border-collapse: collapse; /* Ensure borders are collapsed together */
}

.table th, .table td {
   padding: 12px; /* Add padding for better readability */
   text-align: left; /* Align text to the left for consistency */
   border: 1px solid #ddd; /* Light gray border for the table */
}

.table th {
   background-color: #004080; /* Dark blue header */
   color: #fff; /* White text for better contrast */
   font-weight: bold;
}

.table td {
   background-color: #f9f9f9; /* Light background for table rows */
}

.table tr:nth-child(even) {
   background-color: #f2f2f2; /* Light grey background for even rows */
}

.table tr:hover {
   background-color: #e6f7ff; /* Light blue hover effect */
}

/* Adjusting Sidebar and Content */
.sidebar {
   width: 250px;
   background-color: #003366; /* Dark blue background for sidebar */
   color: #fff;
   position: fixed;
   top: 0;
   left: 0;
   bottom: 0;
   padding-top: 60px;
   overflow-y: auto;
}

.content {
   margin-left: 270px; /* Add space for sidebar */
   padding: 20px;
   overflow-x: auto; /* Allow horizontal scrolling if needed */
}

/* For the PDF Button */
.download-btn {
   padding: 0.5rem 1.5rem;
   background-color: #007bff;
   color: white;
   border-radius: 5px;
   text-decoration: none;
}

.download-btn:hover {
   background-color: #0056b3;
}

/* Sidebar List Styling */
.sidebar ul {
   list-style-type: none;
   padding-left: 0;
}

.sidebar li {
   padding: 12px;
   border-bottom: 1px solid #004080;
}

.sidebar li a {
   color: #e6f0ff;
   text-decoration: none;
   display: block;
}

.sidebar li a:hover {
   color: #cce0ff;
}

      </style>
   </head>
   <body>
      <!-- Sidebar -->
      <div class="sidebar  text-white p-3">
         <h2 class="text-center mb-4">Admin Panel</h2>
         <ul class="list-unstyled">
            <li><a href="admin_dashboard.html" class="text-white d-block mb-2">Dashboard</a></li>
            <li><a href="admin_customer.html" class="text-white d-block mb-2">Manages Customers</a></li>
            <li><a href="admin_product.html" class="text-white d-block mb-2">Manages Sales Products</a></li>
            <li><a href="admin_order.html" class="text-white d-block mb-2">Manages Sales Orders</a></li>
            <li><a href="admin_sales_invoice.html" class="text-white d-block mb-2">Manages Sales Invoice</a></li>
            <li><a href="user_feedback.html" class="text-white d-block mb-2">Manages feedback</a></li>
            <li><a id="logoutLink" href="logout.html" class="text-white d-block mb-2">Logout</a></li>
         </ul>
      </div>

      <!-- Content -->
      <div class="content">
         <section class="placed-orders container-fluid mx-3">
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
                        <th>Order Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     <!-- Demo Order Data -->
                     <tr>
                        <td>1001</td>
                        <td>John Doe</td>
                        <td>9876543210</td>
                        <td>3</td>
                        <td>₹1,499</td>
                        <td>Pending</td>
                     </tr>
                     <tr>
                        <td>1002</td>
                        <td>Jane Smith</td>
                        <td>9876543211</td>
                        <td>2</td>
                        <td>₹2,000</td>
                        <td>Shipping</td>
                     </tr>
                     <tr>
                        <td>1003</td>
                        <td>Robert Brown</td>
                        <td>9876543212</td>
                        <td>1</td>
                        <td>₹799</td>
                        <td>Delivered</td>
                     </tr>
                     <tr>
                        <td>1004</td>
                        <td>Michael Lee</td>
                        <td>9876543213</td>
                        <td>5</td>
                        <td>₹3,500</td>
                        <td>Pending</td>
                     </tr>
                     <tr>
                        <td>1005</td>
                        <td>Emily Davis</td>
                        <td>9876543214</td>
                        <td>4</td>
                        <td>₹2,999</td>
                        <td>Delivered</td>
                     </tr>
                     <tr>
                        <td>1006</td>
                        <td>David Wilson</td>
                        <td>9876543215</td>
                        <td>2</td>
                        <td>₹1,600</td>
                        <td>Shipping</td>
                     </tr>
                  </tbody>
               </table>
            </div>
         </section>
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
