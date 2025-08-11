<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    header('location:admin_users.php');

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
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

.title {
    font-size: 2.5rem;
    color: #e74c3c;
    text-align: center;
    margin-bottom: 20px;
}

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

.main-content {
    padding: 20px;
    margin-top: 80px;
    background-color: #f4f7f8;
    flex: 1;
    padding-left: 300px; /* Updated to apply 300px padding-left */
    border: 2px solid #ddd;
    border-radius: 50px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}

/* Search Form */
.search-form {
    display: flex;
    margin-top: 50px;
    padding-left: 300px; /* Updated to apply 300px padding-left */
    width: 100%;
}

.search-form form {
    display: flex;
    max-width: 600px;
    margin: 0 auto;
}

.search-form .box {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid #ddd;
    border-radius: 50px;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.search-form .box:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
}

.search-form .btn {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 50px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
}

.search-form .btn:hover {
    background: linear-gradient(45deg, #0056b3, #003f87);
}

/* Product Display */
.product-display {
    margin-top: 60px;
    padding-left: 300px; /* Updated to apply 300px padding-left */
}

.product-display .box-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    margin-top: 40px;
}

.product-display .box {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    width: calc(33% - 20px);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
    position: relative;
}

.product-display .box:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.product-display .box img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.product-display .box .name {
    font-size: 1.6rem;
    color: #333;
    padding: 15px;
    text-align: center;
    font-weight: bold;
    background: #f8f8f8;
}

.product-display .box .details {
    font-size: 1rem;
    color: #555;
    padding: 10px;
    text-align: center;
}

.product-display .box .flex-btn {
    display: flex;
    justify-content: space-around;
    padding: 10px;
    background: #f5f5f5;
}

.product-display .box .flex-btn a {
    background: #28a745;
    padding: 8px 15px;
    border-radius: 5px;
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.product-display .box .flex-btn a:hover {
    background: #218838;
}

.empty {
    font-size: 1.5rem;
    color: #bbb;
    text-align: center;
    width: 100%;
    margin-top: 50px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .product-display .box-container {
        flex-direction: column;
        align-items: center;
    }

    .product-display .box {
        width: 80%;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 200px;
        padding: 15px;
    }

    .main-content {
        margin-left: 220px;
        padding: 20px;
    }

    .product-display .box {
        width: 100%;
    }
}

   </style>
</head>
<body>

<!-- Include Admin Header -->
<?php include 'admin_header.php'; ?>

<!-- Sidebar (included from admin_header.php) -->
<div class="sidebar">
   <!-- Profile Section -->
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

   <!-- Navbar -->
   <nav class="navbar">
      <a href="admin_page.php"><i class="fas fa-home"></i> Home</a>
      <a href="admin_products.php"><i class="fas fa-box"></i> Products</a>
      <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
      <a href="admin_users.php"><i class="fas fa-users"></i> Users</a>
      <a href="admin_contacts.php"><i class="fas fa-envelope"></i> Messages</a>
      <a href="admin_search.php"><i class="fas fa-search"></i> Search</a>
      <a href="logout.php" class="logout-btn">Logout</a>
   </nav>
</div>

<!-- Main Content Section -->
<div class="main-content">
   <section class="search-form">
      <form action="" method="POST">
         <input type="text" class="box" name="search_box" placeholder="Search products...">
         <input type="submit" name="search_btn" value="Search" class="btn">
      </form>
   </section>

   <section class="product-display">
      <div class="box-container">
         <?php
         if (isset($_POST['search_btn'])) {
            $search_box = $_POST['search_box'];
            $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
            $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ? OR category LIKE ?");
            $search_query = "%$search_box%";
            $select_products->execute([$search_query, $search_query]);

            if ($select_products->rowCount() > 0) {
               while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
                  ?>
                  <div class="box">
                     <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="Product Image">
                     <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
                     <div class="details">₹<?= htmlspecialchars($fetch_products['price']); ?></div>
                     <div class="details"><?= htmlspecialchars($fetch_products['details']); ?></div>
                  </div>
                  <?php
               }
            } else {
               echo '<p class="empty">No results found!</p>';
            }
         }
         ?>
      </div>
   </section>
</div>

</body>
</html>
