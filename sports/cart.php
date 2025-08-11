<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:login.php');
};

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$delete_id]);
    header('location:cart.php');
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:cart.php');
}

if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $p_qty = $_POST['p_qty'];
    $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);
    $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
    $update_qty->execute([$p_qty, $cart_id]);
    $message[] = 'cart quantity updated';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .shopping-cart {
            padding: 20px;
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .shopping-cart .title {
            font-size: 2rem;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .shopping-cart .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .shopping-cart .box {
            flex: 1 1 calc(33.333% - 20px);
            max-width: 300px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .shopping-cart .box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .shopping-cart .box .name {
            font-size: 1.2rem;
            font-weight: bold;
            color: #555;
            margin-bottom: 10px;
        }

        .shopping-cart .box .price {
            font-size: 1.1rem;
            color: #007bff;
            margin-bottom: 15px;
        }

        .shopping-cart .box .qty {
            width: 60px;
            text-align: center;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }

        .shopping-cart .box .flex-btn {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .shopping-cart .box .option-btn,
        .shopping-cart .box .btn {
            padding: 10px 15px;
            font-size: 0.9rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
        }

        .shopping-cart .box .option-btn {
            background: #007bff;
            color: #fff;
        }

        .shopping-cart .box .btn {
            background: #dc3545;
            color: #fff;
        }

        .shopping-cart .box .option-btn:hover {
            background: #0056b3;
        }

        .shopping-cart .box .btn:hover {
            background: #c82333;
        }

        .shopping-cart .box .sub-total {
            font-size: 1rem;
            color: #555;
            margin-top: 15px;
        }

        .shopping-cart .cart-total {
            margin-top: 30px;
            text-align: center;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .shopping-cart .cart-total p {
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .shopping-cart .cart-total a {
            display: inline-block;
            margin: 10px;
            padding: 10px 15px;
            font-size: 1rem;
            color: #fff;
            background: #28a745;
            border-radius: 4px;
            text-decoration: none;
            transition: 0.3s;
        }

        .shopping-cart .cart-total a.delete-btn {
            background: #dc3545;
        }

        .shopping-cart .cart-total a.disabled {
            background: #ccc;
            pointer-events: none;
        }

        .shopping-cart .cart-total a:hover {
            background: #218838;
        }

        .shopping-cart .cart-total a.delete-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<section class="shopping-cart">

    <h1 class="title">Products Added</h1>

    <div class="box-container">
        <?php
        $grand_total = 0;
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);
        if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <form action="" method="POST" class="box">
                    <img src="uploaded_img/<?= $fetch_cart['image']; ?>" alt="Product Image">
                    <div class="name"><?= $fetch_cart['name']; ?></div>
                    <div class="price">₹<?= $fetch_cart['price']; ?>/-</div>
                    <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
                    <div class="flex-btn">
                        <input type="number" min="1" value="<?= $fetch_cart['quantity']; ?>" class="qty" name="p_qty">
                        <input type="submit" value="update" name="update_qty" class="option-btn">
                        <a href="view_page.php?pid=<?= $fetch_cart['pid']; ?>" class="btn">Product Info</a>
                        <a href="cart.php?delete=<?= $fetch_cart['id']; ?>" class="btn" onclick="return confirm('Delete this item?');">Delete</a>
                    </div>
                    <div class="sub-total">Sub Total: ₹<?= $sub_total = $fetch_cart['price'] * $fetch_cart['quantity']; ?>/-</div>
                </form>
                <?php
                $grand_total += $sub_total;
            }
        } else {
            echo '<p class="empty">Your cart is empty</p>';
        }
        ?>
    </div>

    <div class="cart-total">
        <p>Grand Total: ₹<?= $grand_total; ?>/-</p>
        <a href="shop.php" class="option-btn">Continue Shopping</a>
        <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Delete All</a>
        <a href="checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
    </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
