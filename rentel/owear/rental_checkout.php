<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['order'])) {

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = filter_var($_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ', ' . $_POST['pin_code'], FILTER_SANITIZE_STRING);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products = [];
    $start_date = null;
    $end_date = null;

    // Get cart items for the user
    $cart_query = $conn->prepare("SELECT * FROM rental_cart WHERE user_id = ?");
    $cart_query->execute([$user_id]);
    if ($cart_query->rowCount() > 0) {
        while ($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)) {
            // Calculate the number of rental days
            $start_date = new DateTime($cart_item['start_date']);
            $end_date = new DateTime($cart_item['end_date']);
            $interval = $start_date->diff($end_date);
            $days_rented = $interval->days;

            // Calculate the sub-total for this item
            $sub_total = $cart_item['price'] * $days_rented;
            $cart_total += $sub_total;

            $cart_products[] = $cart_item['name'] . ' ( ' . $days_rented . ' days )';
        }
    }

    $total_products = implode(', ', $cart_products);

    // Check if the order already exists
    $order_query = $conn->prepare("SELECT * FROM rental_orders WHERE name = ? AND number = ? AND address = ? AND total_products = ? AND total_price = ?");
    $order_query->execute([$name, $number, $address, $total_products, $cart_total]);

    if ($cart_total == 0) {
        $message[] = 'Your rental cart is empty';
    } elseif ($order_query->rowCount() > 0) {
        $message[] = 'Rental order already placed!';
    } else {
        // Insert rental order into the rental_orders table
        $insert_order = $conn->prepare("INSERT INTO rental_orders(user_id, name, number, email, address, total_products, total_price, placed_on, start_date, end_date) 
                                      VALUES(?,?,?,?,?,?,?,?,?,?)");
        $insert_order->execute([$user_id, $name, $number, $email, $address, $total_products, $cart_total, $placed_on, $start_date->format('Y-m-d'), $end_date->format('Y-m-d')]);

        $order_id = $conn->lastInsertId();  // Get the last inserted order ID

        // Check for selected payment method
        $payment_method = $_POST['payment_method'];

        if ($payment_method == 'credit_card') {
            // Get credit card details from the form (Make them required)
            $card_name = filter_var($_POST['card_name'], FILTER_SANITIZE_STRING);
            $card_number = filter_var($_POST['card_number'], FILTER_SANITIZE_STRING);
            // Ensure the expiry date is in MM/YY format
            $expiry_date = filter_var($_POST['expiry_date'], FILTER_SANITIZE_STRING);
            $cvv = filter_var($_POST['cvv'], FILTER_SANITIZE_STRING);

            // Insert payment details into the rental_payments table for credit card payment
            $insert_payment = $conn->prepare("INSERT INTO rental_payments(user_id, order_id, payment_method, card_name, card_number, expiry_date, cvv, payment_status) 
                                           VALUES(?,?,?,?,?,?,?,?)");
            $insert_payment->execute([$user_id, $order_id, 'credit_card', $card_name, $card_number, $expiry_date, $cvv, 'completed']);
        } else {
            // For COD, set the credit card details as NULL
            $insert_payment = $conn->prepare("INSERT INTO rental_payments(user_id, order_id, payment_method, card_name, card_number, expiry_date, cvv, payment_status) 
                                           VALUES(?,?,?,?,NULL,NULL,NULL,?)");
            $insert_payment->execute([$user_id, $order_id, 'COD', null, 'pending']);
        }

        // Delete items from rental_cart after order placement
        $delete_cart = $conn->prepare("DELETE FROM rental_cart WHERE user_id = ?");
        $delete_cart->execute([$user_id]);

        header('location:thankyou.php');
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 2rem;
            font-weight: 600;
            color: #343a40;
            margin-bottom: 30px;
        }

        .card-header {
            background-color: rgb(0, 0, 0);
            color: white;
            font-size: 1.25rem;
            border-radius: 30px;
        }

        .btn-custom {
            background-color: rgb(0, 0, 0);
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: rgb(41, 41, 41);
            color: white;
        }
    </style>
</head>

<body>
    <?php include 'rental_navbar.php' ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Place Your Rental Order
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <!-- Personal Info -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Your Name:</label>
                                        <input type="text" name="name" id="name" placeholder="Enter your name" class="form-control" required
                                            pattern="[A-Za-z\s]+" title="Name should only contain letters and spaces">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="number">Your Number:</label>
                                        <input type="tel" name="number" id="number" placeholder="Enter your number" class="form-control" required
                                            pattern="\d{10}" title="Number must be exactly 10 digits">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Your Email:</label>
                                <input type="email" name="email" id="email" placeholder="Enter your email" class="form-control" required
                                    pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="flat">Address Line 01:</label>
                                        <input type="text" name="flat" id="flat" placeholder="e.g. flat number" class="form-control" required
                                            pattern="^[A-Za-z0-9\s\-,.]+$" title="Address Line 1 should not contain special characters">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="street">Address Line 02:</label>
                                        <input type="text" name="street" id="street" placeholder="e.g. street name" class="form-control" required
                                            pattern="^[A-Za-z0-9\s\-,.]+$" title="Address Line 2 should not contain special characters">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City:</label>
                                        <input type="text" name="city" id="city" placeholder="e.g. Gandhinagar" class="form-control" required
                                            pattern="[A-Za-z\s]+" title="City should only contain letters and spaces">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State:</label>
                                        <input type="text" name="state" id="state" placeholder="e.g. Gujarat" class="form-control" required
                                            pattern="[A-Za-z\s]+" title="State should only contain letters and spaces">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Country:</label>
                                        <input type="text" name="country" id="country" placeholder="e.g. India" class="form-control" required
                                            pattern="[A-Za-z\s]+" title="Country should only contain letters and spaces">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pin_code">Pin Code:</label>
                                        <input type="text" name="pin_code" id="pin_code" placeholder="e.g. 382016" class="form-control" required
                                            pattern="\d{6}" title="Pin Code must be exactly 6 digits">
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method Selection -->
                            <div class="form-group">
                                <label for="payment_method">Select Payment Method:</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="" selected disabled>Choose payment method</option>
                                    <option value="COD">Cash on Delivery (COD)</option>
                                    <option value="credit_card">Credit Card</option>
                                </select>
                            </div>

                            <!-- Credit Card Details (shown only if Credit Card is selected) -->
                            <div id="credit_card_details" style="display: none;">
                                <div class="form-group">
                                    <label for="card_name">Cardholder Name:</label>
                                    <input type="text" name="card_name" id="card_name" class="form-control" required
                                        pattern="[A-Za-z\s]+" title="Cardholder name should only contain letters and spaces">
                                </div>
                                <div class="form-group">
                                    <label for="card_number">Card Number:</label>
                                    <input type="text" name="card_number" id="card_number" class="form-control" required
                                        pattern="\d{16}" title="Card number must be exactly 16 digits">
                                </div>
                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date (MM/YY):</label>
                                    <input type="text" name="expiry_date" id="expiry_date" class="form-control" required
                                        pattern="(0[1-9]|1[0-2])\/\d{2}" title="Enter a valid expiry date in MM/YY format (e.g., 05/26)">
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV:</label>
                                    <input type="text" name="cvv" id="cvv" class="form-control" required
                                        pattern="\d{3}" title="CVV must be exactly 3 digits">
                                </div>
                            </div>
                            <button type="submit" name="order" class="btn btn-custom btn-block">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Rental Cart Summary
                    </div>
                    <div class="card-body">
                        <?php
                        $cart_grand_total = 0;
                        $select_cart_items = $conn->prepare("SELECT * FROM rental_cart WHERE user_id = ?");
                        $select_cart_items->execute([$user_id]);
                        if ($select_cart_items->rowCount() > 0) {
                            while ($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)) {
                                $start_date = new DateTime($fetch_cart_items['start_date']);
                                $end_date = new DateTime($fetch_cart_items['end_date']);
                                $interval = $start_date->diff($end_date);
                                $days_rented = $interval->days;

                                $cart_total_price = ($fetch_cart_items['price'] * $days_rented);
                                $cart_grand_total += $cart_total_price;
                        ?>
                                <p><?= $fetch_cart_items['name']; ?>
                                    <span class="text-muted"> (<?= '₹' . $fetch_cart_items['price'] . '/- x ' . $days_rented . ' days'; ?>)</span>
                                </p>
                                <p class="d-flex justify-content-between">
                                    <strong>Subtotal:</strong>
                                    <strong>₹<?= $cart_total_price; ?>/-</strong>
                                </p>
                                <hr>
                        <?php
                            }
                        } else {
                            echo '<p class="empty">Your rental cart is empty!</p>';
                        }
                        ?>
                        <div class="grand-total text-dark">
                            <p class="d-flex justify-content-between">
                                <strong>Grand Total:</strong>
                                <strong>₹<?= $cart_grand_total; ?>/-</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Show Credit Card Details if Credit Card is selected
        document.getElementById('payment_method').addEventListener('change', function() {
            if (this.value === 'credit_card') {
                document.getElementById('credit_card_details').style.display = 'block';
                // Make credit card fields required
                document.getElementById('card_name').setAttribute('required', true);
                document.getElementById('card_number').setAttribute('required', true);
                document.getElementById('expiry_date').setAttribute('required', true);
                document.getElementById('cvv').setAttribute('required', true);
            } else {
                document.getElementById('credit_card_details').style.display = 'none';
                // Remove required attribute if COD is selected
                document.getElementById('card_name').removeAttribute('required');
                document.getElementById('card_number').removeAttribute('required');
                document.getElementById('expiry_date').removeAttribute('required');
                document.getElementById('cvv').removeAttribute('required');
            }
        });
    </script>
</body>

</html>