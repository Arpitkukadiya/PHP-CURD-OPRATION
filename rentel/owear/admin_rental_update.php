<?php

@include 'config.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}

if (isset($_GET['update'])) {
    $update_id = $_GET['update'];
    $select_products = $conn->prepare("SELECT * FROM rental_products WHERE id = ?");
    $select_products->execute([$update_id]);
    if ($select_products->rowCount() > 0) {
        $fetch_rental_products = $select_products->fetch(PDO::FETCH_ASSOC);
    } else {
        $message[] = 'Product not found!';
        header('location:admin_product.php');
    }
}

if (isset($_POST['update_product'])) {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $details = $_POST['details'];
    $details = filter_var($details, FILTER_SANITIZE_STRING);

    $update_product = $conn->prepare("UPDATE rental_products SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
    $update_product->execute([$name, $category, $details, $price, $id]);

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image = filter_var($image, FILTER_SANITIZE_STRING);
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploaded_img/' . $image;

        if ($image_size > 2000000) {
            $message[] = 'Image size is too large!';
        } else {
            $select_delete_image = $conn->prepare("SELECT image FROM rental_products WHERE id = ?");
            $select_delete_image->execute([$id]);
            $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
            unlink('uploaded_img/' . $fetch_delete_image['image']);
            move_uploaded_file($image_tmp_name, $image_folder);
            $update_image = $conn->prepare("UPDATE rental_products SET image = ? WHERE id = ?");
            $update_image->execute([$image, $id]);
        }
    }

    $message[] = 'Product updated successfully!';
    header('location:admin_rental_product.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product - EYEWEAR</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_styles.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Arial', sans-serif;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 12px 20px;
            font-size: 16px;
            width: 100%;
            border-radius: 8px;
            text-transform: uppercase;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

       

        .form-group {
            margin-bottom: 25px;
        }


        .custom-file-input {
            border-radius: 8px;
            padding: 12px;
        }

        .custom-file-label {
            border-radius: 8px;
            padding: 12px;
        }

        .message {
            text-align: center;
            font-size: 18px;
            color: green;
        }

        .form-row {
            display: flex;
            flex-direction: column;
        }

        .form-row select,
        .form-row input {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="d-flex">
        <?php include 'admin_navbar.php' ?>

        <!-- Content -->
        <div class="content w-100">
            <div class="container">
                <div class="product-form">
                    <h2 class="h3 mb-5 text-center">Update Rental Product</h2>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $fetch_rental_products['id']; ?>">

                        <div class="form-row">
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" required value="<?= $fetch_rental_products['name']; ?>" placeholder="Enter product name">
                            </div>
                            <div class="form-group">
                                <select name="category" class="form-control" required>
                                    <option value="<?= $fetch_rental_products['category']; ?>" selected><?= $fetch_rental_products['category']; ?></option>
                                    <option value="saree">Saree</option>
                                    <option value="dress">Dress</option>
                                    <option value="western">Western</option>
                                    <option value="kurtis">Kurtis</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <input type="number" min="0" name="price" class="form-control" required value="<?= $fetch_rental_products['price']; ?>" placeholder="Enter product price">
                            </div>
                            <div class="form-group">
                                <input type="file" name="image" class="form-control" accept="image/jpg, image/jpeg, image/png">
                            </div>
                        </div>

                        <div class="form-group">
                            <textarea name="details" class="form-control" required placeholder="Enter product details"  rows="3"><?= $fetch_rental_products['details']; ?></textarea>
                        </div>

                        <div class="text-center">
                            <input type="submit" class="btn btn-primary" value="Update Product" name="update_product">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
