<?php
@include 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

try {
    // PDO connection setup
    $db_name = "mysql:host=localhost;dbname=owear;charset=utf8";
    $username = "root";
    $password = "";

    $conn = new PDO($db_name, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Fetching the orders for the logged-in user
    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = :user_id ORDER BY placed_on DESC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    $orders = $stmt->fetchAll();

    // Handling feedback submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_feedback'])) {
        $order_id = $_POST['order_id'];
        $feedback = $_POST['feedback'];
        $rating = $_POST['rating'];

        $feedback_stmt = $conn->prepare("INSERT INTO order_feedback (order_id, user_id, feedback, rating) VALUES (:order_id, :user_id, :feedback, :rating)");
        $feedback_stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
        $feedback_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $feedback_stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);
        $feedback_stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
        $feedback_stmt->execute();

        echo "<script>alert('Thank you for your feedback!');</script>";
    }

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }
        .order-card {
            margin-bottom: 20px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .order-card h3 {
            color: #007bff;
        }
        .order-card p {
            margin-bottom: 10px;
        }
        .order-card .btn {
            background-color:rgb(0, 0, 0);
            color: white;
        }
        .modal-content {
            border-radius: 8px;
        }
        .modal-header {
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .modal-body table {
            width: 100%;
            border-collapse: collapse;
        }
        .modal-body table th, .modal-body table td {
            padding: 12px;
            text-align: left;
        }
        .modal-body table th {
            background-color: #f1f1f1;
        }
        /* Star Rating */
        .star-rating input {
            display: none;
        }
        .star-rating label {
            font-size: 30px;
            color: #ddd;
            cursor: pointer;
        }
        .star-rating input:checked ~ label {
            color: #f39c12;
        }
        .btn{
            border: none;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container-fluid mt-3">
    <h2 class="text-center mb-4">Your Order History</h2>

    <div class="row">
        <?php if (empty($orders)): ?>
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    No orders found.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="col-md-3">
                    <div class="order-card">
                        <h4>Order: <?php echo $order['id']; ?></h4><hr>
                        <p><strong>Name:</strong> <?php echo $order['name']; ?></p>
                        <p><strong>Contact:</strong> <?php echo $order['number']; ?></p>
                        <p><strong>Email:</strong> <?php echo $order['email']; ?></p>
                        <p><strong>Address:</strong> <?php echo $order['address']; ?></p>
                        <p><strong>Total Price:</strong> ₹<?php echo $order['total_price']; ?></p>
                        <p><strong>Order Status:</strong> <?php echo $order['payment_status']; ?></p>


                        <?php
                        // Check if feedback has already been provided for this order
                        $feedback_check = $conn->prepare("SELECT * FROM order_feedback WHERE order_id = :order_id AND user_id = :user_id");
                        $feedback_check->bindParam(':order_id', $order['id'], PDO::PARAM_INT);
                        $feedback_check->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        $feedback_check->execute();
                        $feedback_data = $feedback_check->fetch();

                        // If feedback exists, don't show the feedback button
                        if (!$feedback_data): ?>
                            <button class="btn btn-secondary w-100 btn-sm mb-1" data-toggle="modal" data-target="#feedbackModal<?php echo $order['id']; ?>">Give Feedback</button>
                        <?php else: ?>
                            
                            <p><strong>Rating:</strong> <?php echo str_repeat('★', $feedback_data['rating']); ?></p>
                        <?php endif; ?>
                        <button class="btn btn-dark w-100" data-toggle="modal" data-target="#orderModal<?php echo $order['id']; ?>">View Product</button>

                    </div>
                </div>

                <!-- Modal for Ordered Products -->
                <div class="modal fade" id="orderModal<?php echo $order['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="orderModalLabel<?php echo $order['id']; ?>">Ordered Products for Order: <?php echo $order['id']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-striped" id="productTable<?php echo $order['id']; ?>">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Category</th>
                                            <th>Price (₹)</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $products = explode(", ", $order['total_products']);
                                        foreach ($products as $product) {
                                            $product_details = explode("(", $product);
                                            $product_name = $product_details[0];
                                            $product_qty = rtrim($product_details[1], ")");
                                            
                                            $product_stmt = $conn->prepare("SELECT * FROM products WHERE name = :name");
                                            $product_stmt->bindParam(':name', $product_name, PDO::PARAM_STR);
                                            $product_stmt->execute();
                                            
                                            $product_info = $product_stmt->fetch();
                                            if ($product_info) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $product_info['name']; ?></td>
                                                    <td><?php echo $product_info['category']; ?></td>
                                                    <td>₹<?php echo $product_info['price']; ?></td>
                                                    <td><?php echo $product_qty; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal for Feedback -->
                <div class="modal fade" id="feedbackModal<?php echo $order['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel<?php echo $order['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="feedbackModalLabel<?php echo $order['id']; ?>">Give Feedback for Order: <?php echo $order['id']; ?></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <div class="form-group">
                                        <label for="rating<?php echo $order['id']; ?>">Rating</label>
                                        <div class="star-rating">
                                            <input type="radio" id="star5<?php echo $order['id']; ?>" name="rating" value="5" required>
                                            <label for="star5<?php echo $order['id']; ?>">★</label>
                                            <input type="radio" id="star4<?php echo $order['id']; ?>" name="rating" value="4">
                                            <label for="star4<?php echo $order['id']; ?>">★</label>
                                            <input type="radio" id="star3<?php echo $order['id']; ?>" name="rating" value="3">
                                            <label for="star3<?php echo $order['id']; ?>">★</label>
                                            <input type="radio" id="star2<?php echo $order['id']; ?>" name="rating" value="2">
                                            <label for="star2<?php echo $order['id']; ?>">★</label>
                                            <input type="radio" id="star1<?php echo $order['id']; ?>" name="rating" value="1">
                                            <label for="star1<?php echo $order['id']; ?>">★</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="feedback<?php echo $order['id']; ?>">Feedback:</label>
                                        <textarea name="feedback" class="form-control" id="feedback<?php echo $order['id']; ?>" rows="2" required></textarea>
                                    </div>
                                    <button type="submit" name="submit_feedback" class="btn btn-success">Submit Feedback</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
