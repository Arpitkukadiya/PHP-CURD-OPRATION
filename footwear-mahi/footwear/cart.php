<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
    exit();
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:cart.php');
    exit();
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
    exit();
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $p_qty = filter_var($_POST['p_qty'], FILTER_SANITIZE_NUMBER_INT);
    $p_size = $_POST['p_size']; // Get selected UK shoe size
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ?, size = ? WHERE id = ?");
    $update_qty->execute([$p_qty, $p_size, $cart_id]);
    header('location:cart.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: Arial, sans-serif;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            margin-top: 20px;
        }

        .cart-table td {
            padding: 15px;
            vertical-align: middle;
        }

        .cart-img {
            width: 80px;
            height: auto;
            border-radius: 5px;
        }

        .btn-custom {
            background-color: black;
            color: white;
        }

        .btn-custom:hover {
            background-color: grey;
            color: white;
        }

        .delete-btn {
            background-color: red;
            color: white;
        }

        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-3">
        <h1 class="text-center">Shopping Cart</h1>
        <div class="row">
            <div class="col-lg-8">
                <?php
                $grand_total = 0;
                $cart_items = [];
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                        $grand_total += $sub_total;
                        $cart_items[] = $fetch_cart;
                ?>
                        <table class="cart-table">
                            <tr>
                                <td><img src="uploaded_img/<?= $fetch_cart['image']; ?>" class="cart-img"></td>
                                <td><?= $fetch_cart['name']; ?><br>₹<?= $fetch_cart['price']; ?>/-<br>Size: <?= $fetch_cart['size']; ?></td>
                                <td>
                                    <form action="" method="POST" class="d-flex">
                                        <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                                        <input type="number" min="1" max="5" value="<?= $fetch_cart['quantity']; ?>" class="form-control" name="p_qty">
                                        <select name="p_size" class="form-control ml-2">
                                            <option value="UK 6" <?= ($fetch_cart['size'] == 'UK 6') ? 'selected' : ''; ?>>UK 6</option>
                                            <option value="UK 7" <?= ($fetch_cart['size'] == 'UK 7') ? 'selected' : ''; ?>>UK 7</option>
                                            <option value="UK 8" <?= ($fetch_cart['size'] == 'UK 8') ? 'selected' : ''; ?>>UK 8</option>
                                            <option value="UK 9" <?= ($fetch_cart['size'] == 'UK 9') ? 'selected' : ''; ?>>UK 9</option>
                                            <option value="UK 10" <?= ($fetch_cart['size'] == 'UK 10') ? 'selected' : ''; ?>>UK 10</option>
                                            <option value="UK 11" <?= ($fetch_cart['size'] == 'UK 11') ? 'selected' : ''; ?>>UK 11</option>
                                            <option value="UK 12" <?= ($fetch_cart['size'] == 'UK 12') ? 'selected' : ''; ?>>UK 12</option>
                                        </select>
                                        <input type="submit" value="Update" name="update_qty" class="btn btn-warning ml-2">
                                    </form>
                                </td>
                                <td>₹<?= $sub_total; ?>/-</td>
                                <td>
                                    <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="btn delete-btn" onclick="return confirm('Delete this from cart?');">Delete</a>
                                </td>
                            </tr>
                        </table>
                <?php
                    }
                } else {
                    echo '<p class="text-center">Your cart is empty</p>';
                }
                ?>
            </div>

            <div class="col-lg-4">
                <div class="card mt-4">
                    <div class="card-header text-center">
                        <h4>Bill Summary</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($cart_items)) : ?>
                            <ul class="list-group mb-3">
                                <?php foreach ($cart_items as $item) : ?>
                                    <li class="list-group-item">
                                        <?= $item['name']; ?> (₹<?= $item['price']; ?> x <?= $item['quantity']; ?>) 
                                        - Size: <?= $item['size']; ?>
                                        <span class="float-right">₹<?= ($item['price'] * $item['quantity']); ?>/-</span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                        <p><strong>Total:</strong> ₹<?= $grand_total; ?>/-</p>
                        <a href="checkout.php" class="btn btn-custom btn-block <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
