<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
}

if (isset($_POST['add_product'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
    $select_products->execute([$name]);

    if ($select_products->rowCount() > 0) {
        $message[] = 'Product name already exists!';
    } else {

        $insert_products = $conn->prepare("INSERT INTO `products`(name, category, details, price, image) VALUES(?,?,?,?,?)");
        $insert_products->execute([$name, $category, $details, $price, $image]);

        if ($insert_products) {
            if ($image_size > 2000000) {
                $message[] = 'Image size is too large!';
            } else {
                move_uploaded_file($image_tmp_name, $image_folder);
                $message[] = 'New product added!';
            }
        }

    }

};

if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
    $select_delete_image->execute([$delete_id]);
    $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
    unlink('uploaded_img/' . $fetch_delete_image['image']);
    $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
    $delete_products->execute([$delete_id]);
    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
    $delete_wishlist->execute([$delete_id]);
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
    $delete_cart->execute([$delete_id]);
    header('location:admin_products.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
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

        /* Content */
        .content {
            margin-left: 270px;
            padding: 40px;
            padding-top: 110px;
        }

        .title {
            font-size: 2.5rem;
            color: #e74c3c;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Add Product Form */
        .add-products {
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
            padding-left: 50px;
        }

        .add-products .inputBox {
            margin-bottom: 20px;
        }

        .add-products .inputBox input,
        .add-products .inputBox select,
        .add-products textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 1rem;
            box-sizing: border-box;
        }

        .add-products textarea {
            resize: vertical;
            min-height: 120px;
        }

        .add-products .btn {
            background: #e74c3c;
            color: #fff;
            padding: 14px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .add-products .btn:hover {
            background: #c0392b;
        }

        /* Show Products Section */
        .show-products {
            margin-top: 60px;
        }

        .show-products .box-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
            margin-top: 40px;
        }

        .show-products .box {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            width: calc(33% - 20px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            position: relative;
        }

        .show-products .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .show-products .box img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .show-products .box .name {
            font-size: 1.6rem;
            color: #333;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            background: #f8f8f8;
        }

        .show-products .box .cat {
            font-size: 1rem;
            color: #888;
            text-align: center;
            padding-bottom: 10px;
        }

        .show-products .box .details {
            font-size: 1rem;
            color: #555;
            padding: 10px;
            text-align: center;
        }

        .show-products .box .flex-btn {
            display: flex;
            justify-content: space-around;
            padding: 10px;
            background: #f5f5f5;
        }

        .show-products .box .flex-btn a {
            background: #28a745;
            padding: 8px 15px;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .show-products .box .flex-btn a:hover {
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
            .show-products .box-container {
                flex-direction: column;
                align-items: center;
            }

            .show-products .box {
                width: 80%;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
                padding: 15px;
            }

            .content {
                margin-left: 220px;
                padding: 20px;
            }

            .show-products .box {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="content">
        <section class="add-products">
            <h1 class="title">Add New Product</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="flex">
                    <div class="inputBox">
                        <input type="text" name="name" class="box" required placeholder="Enter product name">
                        <select name="category" class="box" required>
                            <option value="" selected disabled>Select category</option>
                            <option value="CRICKET">CRICKET</option>
                            <option value="FOOTBALL">FOOTBALL</option>
                            <option value="HOCKEY">HOCKEY</option>
                            <option value="BADMINTON">BADMINTON</option>
                        </select>
                        
                    </div>
                    <div class="inputBox">
                        <input type="number" min="0" name="price" class="box" required placeholder="Enter product price">
                        <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
                    </div>
                </div>
                <textarea name="details" class="box" required placeholder="Enter product details" cols="30" rows="10"></textarea>
                <input type="submit" class="btn" value="Add Product" name="add_product">
            </form>
        </section>

        <section class="show-products">
            <h1 class="title">Products Added</h1>
            <div class="box-container">
                <?php
                $show_products = $conn->prepare("SELECT * FROM `products`");
                $show_products->execute();
                if ($show_products->rowCount() > 0) {
                    while ($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)) {
                ?>
                        <div class="box">
                            <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="Product Image">
                            <div class="name"><?= $fetch_products['name']; ?> ₹<?= $fetch_products['price']; ?>/-</div>
                            <div class="cat"><?= $fetch_products['category']; ?></div>
                            <div class="details"><?= $fetch_products['details']; ?></div>
                            <div class="flex-btn">
                                <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
                                <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<p class="empty">No products added yet!</p>';
                }
                ?>
            </div>
        </section>

    </section>

</body>

</html>
