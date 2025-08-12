<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

try {
    $db_name = "mysql:host=localhost;dbname=owear;charset=utf8"; 
    $username = "root";
    $password = "";

    $conn = new PDO($db_name, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Fetch rental orders for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM rental_orders WHERE user_id = :user_id ORDER BY placed_on DESC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $orders = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Order History</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Style for the container and general layout */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            border: none;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color:rgb(0, 0, 0);
            color: #fff;
            padding: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            background-color: #ffffff;
            padding: 25px;
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .card-body .order-detail {
            font-size: 0.9rem;
            margin-bottom: 10px;
            color: #495057;
        }

        .order-detail span {
            font-weight: bold;
            color:rgb(0, 0, 0);
        }

        .order-status {
            font-weight: bold;
            font-size: 1rem;
        }

        .order-status.pending {
            color: #ffc107;
        }

        .order-status.completed {
            color: #28a745;
        }

        .order-status.failed {
            color: #dc3545;
        }

        .order-dates {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .order-dates p {
            margin: 0;
            font-size: 0.9rem;
            color: #495057;
        }

    
    </style>
</head>
<body>
<?php include 'rental_navbar.php' ?>

    <div class="container-fluied mx-5">
        <h2 class="text-center mb-4 mt-3">Your Rental Order History</h2>

        <div class="row">
            <?php if ($orders): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header">
                                Order: <?= $order['id'] ?>
                            </div>
                            <div class="card-body">
                                <p class="order-detail"><span>Name:</span> <?= $order['name'] ?></p>
                                <p class="order-detail"><span>Phone:</span> <?= $order['number'] ?></p>
                                <p class="order-detail"><span>Email:</span> <?= $order['email'] ?></p>
                                <p class="order-detail"><span>Address:</span><br> <?= $order['address'] ?></p>
                                <p class="order-detail"><span>Total Products:</span><br> <?= $order['total_products'] ?></p>
                                <p class="order-detail"><span>Total Price:</span> ₹<?= $order['total_price'] ?></p>
                                <p class="order-detail"><span>Status:</span> 
                                    <span class="order-status <?= strtolower($order['payment_status']) ?>">
                                        <?= ucfirst($order['payment_status']) ?>
                                    </span>
                                </p>

                                <!-- Dates Section -->
                                <div class="order-dates">
                                    <p><span>Start Date:</span> <?= $order['start_date'] ?></p>
                                    <p><span>End Date:</span> <?= $order['end_date'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-12">No rental orders found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
