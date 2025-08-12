<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit; // Prevent further code execution
}

// Total number of customers
$customers_query = $conn->prepare("SELECT COUNT(*) as total_customers FROM users WHERE user_type = 'user'");
$customers_query->execute();
$customers_data = $customers_query->fetch(PDO::FETCH_ASSOC);

// Total number of products
$products_query = $conn->prepare("SELECT COUNT(*) as total_products FROM products");
$products_query->execute();
$products_data = $products_query->fetch(PDO::FETCH_ASSOC);

// Total number of orders
$orders_query = $conn->prepare("SELECT COUNT(*) as total_orders FROM orders");
$orders_query->execute();
$orders_data = $orders_query->fetch(PDO::FETCH_ASSOC);

// Total number of rental orders
$rental_orders_query = $conn->prepare("SELECT COUNT(*) as total_rental_orders FROM rental_orders");
$rental_orders_query->execute();
$rental_orders_data = $rental_orders_query->fetch(PDO::FETCH_ASSOC);

// Total number of rental products
$rental_products_query = $conn->prepare("SELECT COUNT(*) as total_rental_products FROM rental_products");
$rental_products_query->execute();
$rental_products_data = $rental_products_query->fetch(PDO::FETCH_ASSOC);

// Total number of rental cart items
$rental_cart_query = $conn->prepare("SELECT COUNT(*) as total_rental_cart FROM rental_cart");
$rental_cart_query->execute();
$rental_cart_data = $rental_cart_query->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EYEWEAR Admin Dashboard</title>
    <!-- Bootstrap CSS for layout -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="admin_styles.css">
    <style>
        /* Header Styles */
        header {
            background-color: #003366; /* Dark Blue */
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #003366; /* Dark Blue */
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding-top: 60px;
            overflow-y: auto;
        }

        .sidebar ul {
            list-style-type: none;
            padding-left: 0;
        }

        .sidebar li {
            padding: 12px;
            border-bottom: 1px solid #004080; /* Slightly Lighter Blue */
        }

        .sidebar li a {
            color: #e6f0ff; /* Light Blue */
            text-decoration: none;
            display: block;
        }

        .sidebar li a:hover {
            color: #cce0ff; /* Lighter Blue */
        }

        /* Content Styles */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .widget {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
        }

        .widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .widget-icon {
            font-size: 2.5rem;
            margin-right: 15px;
            color: #007bff;
        }

        .widget h3 {
            color: #004080;
            margin-bottom: 5px;
        }

        .widget p {
            font-size: 18px;
            color: #333;
        }

        /* Add more padding for content */
        .container {
            max-width: 1200px;
        }

    </style>
</head>
<body>

<?php include 'admin_navbar.php' ?>

    <!-- Content -->
    <div class="content">
        <div class="container">
        <h2 class="mb-3" style="color: #003366;">Dashboard</h2>

            <div class="row">
                <!-- Customers -->
                <div class="col-12 col-md-4">
                    <div class="widget">
                        <i class="fas fa-users widget-icon"></i>
                        <div>
                            <h3>Customers</h3>
                            <p>No. of registered customers: <strong><?= $customers_data['total_customers']; ?></strong></p>
                        </div>
                    </div>
                </div>

                <!-- Products -->
                <div class="col-12 col-md-4">
                    <div class="widget">
                        <i class="fas fa-box widget-icon"></i>
                        <div>
                            <h3>Products</h3>
                            <p>No. of products available: <strong><?= $products_data['total_products']; ?></strong></p>
                        </div>
                    </div>
                </div>

                <!-- Orders -->
                <div class="col-12 col-md-4">
                    <div class="widget">
                        <i class="fas fa-shopping-cart widget-icon"></i>
                        <div>
                            <h3>Orders</h3>
                            <p>Total number of orders: <strong><?= $orders_data['total_orders']; ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Rental Orders -->
                <div class="col-12 col-md-4">
                    <div class="widget">
                        <i class="fas fa-cart-plus widget-icon"></i>
                        <div>
                            <h3>Rental Orders</h3>
                            <p>Total rental orders: <strong><?= $rental_orders_data['total_rental_orders']; ?></strong></p>
                        </div>
                    </div>
                </div>

                <!-- Rental Products -->
                <div class="col-12 col-md-4">
                    <div class="widget">
                        <i class="fas fa-tshirt widget-icon"></i>
                        <div>
                            <h3>Rental Products</h3>
                            <p>Total rental products: <strong><?= $rental_products_data['total_rental_products']; ?></strong></p>
                        </div>
                    </div>
                </div>

                

            <!-- Add more widgets or content here -->
        </div>
    </div>

    <!-- Bootstrap Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
