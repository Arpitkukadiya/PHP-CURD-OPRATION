<?php
// Your PHP code (e.g., database connection, fetching profile data) should go here.

if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Panel</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <style>
      /* General Reset and Body Styles */
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: 'Roboto', sans-serif;
         display: flex;
         color: #333;
         background-color: #f4f7f8;
      }

      /* Sidebar Styles */
      .sidebar {
         width: 220px;
         background-color: #000; /* Black theme */
         color: #fff;
         height: 100vh;
         position: fixed;
         display: flex;
         flex-direction: column;
         justify-content: flex-start;
         padding: 20px 10px;
         box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
      }

      .profile-section {
         text-align: center;
         margin-bottom: 15px;
      }

      .profile-section img {
         width: 75px;
         height: 75px;
         border-radius: 50%;
         margin-bottom: 8px;
         border: 2px solid #fff;
      }

      .profile-section p {
         font-size: 16px;
         font-weight: bold;
         margin: 5px 0;
      }

      .logout-btn {
         background-color: #ff6666;
         color: #fff;
         padding: 7px 10px;
         text-decoration: none;
         border-radius: 5px;
         display: inline-block;
         margin-top: 10px;
         font-size: 14px;
         transition: background-color 0.3s;
      }

      .logout-btn:hover {
         background-color: #cc3333;
      }

      .navbar {
         display: flex;
         flex-direction: column;
         gap: 10px;
      }

      .navbar a {
         display: flex;
         align-items: center;
         text-decoration: none;
         color: #fff;
         font-size: 14px;
         padding: 8px 10px;
         border-radius: 5px;
         transition: background-color 0.3s;
      }

      .navbar a i {
         margin-right: 8px;
         font-size: 16px;
      }

      .navbar a:hover {
         background-color: #333; /* Darker grey for hover */
      }

      /* Header Styles */
      .header {
         background: #1a1a1a;
         color: white;
         padding: 20px;
         font-size: 2rem;
         position: fixed;
         width: 100%;
         top: 0;
         z-index: 1000;
         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      }

      /* Main Content Styles */
      .main-content {
         margin-left: auto; /* Sidebar width */
         padding: 2px;
         background-color: #f7f7f7; /* Cream theme */
         width: auto;
         overflow-x: auto;
      }

      
      /* Message Box */
      .message {
         position: fixed;
         top: 10px;
         left: 50%;
         transform: translateX(-50%);
         background-color: #ff6666;
         color: #fff;
         padding: 10px 15px;
         border-radius: 5px;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
         display: flex;
         align-items: center;
         justify-content: space-between;
         gap: 10px;
         font-size: 14px;
         z-index: 10;
      }

      .message span {
         font-size: 14px;
      }

      .message i {
         cursor: pointer;
         font-size: 16px;
      }

      /* Responsive Design */
      @media (max-width: 768px) {
         .sidebar {
            width: 1800px;
         }

         .main-content {
            margin-left: 180px;
            width: calc(100% - 180px);
         }

         .navbar a {
            font-size: 12px;
            padding: 8px 10px;
         }
      }

      @media (max-width: 480px) {
         .sidebar {
            width: 1500px;
         }

         .main-content {
            margin-left: 150px;
            width: calc(100% - 150px);
         }

         .navbar a {
            font-size: 12px;
            padding: 5px 8px;
         }
      }
   </style>
</head>
<body>

   <!-- Header Section -->
   <div class="header">
      Admin Panel 
   </div>

   <!-- Sidebar Section -->
   <div class="sidebar">
      <div class="profile-section">
         <?php
         // Fetch user profile from database
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
         $select_profile->execute([$admin_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="Profile Picture">
         <p><?= $fetch_profile['name']; ?></p>
      </div>

      <nav class="navbar">
         <a href="admin_page.php"><i class="fas fa-home"></i> Home</a>
         <a href="admin_products.php"><i class="fas fa-box"></i> Products</a>
         <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
         <a href="admin_users.php"><i class="fas fa-users"></i> Users</a>
         <a href="admin_contacts.php"><i class="fas fa-envelope"></i> Messages</a>
         <a href="admin_search.php" ><i class="fas fa-search"></i>search</a>
         <a href="logout.php" class="logout-btn">Logout</a>
      </nav>
   </div>

   <!-- Main Content Section 
   <div class="main-content">
 Your main page content here 
     
   </div>-->

</body>
</html>
