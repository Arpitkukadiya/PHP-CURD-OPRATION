<?php

@include 'config.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}

if(isset($_POST['add_product'])){

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
    $image_folder = 'uploaded_img/'.$image;
 
    $select_products = $conn->prepare("SELECT * FROM rental_products WHERE name = ?");
    $select_products->execute([$name]);
 
    if($select_products->rowCount() > 0){
       $message[] = 'Product name already exists!';
    } else {
       $insert_products = $conn->prepare("INSERT INTO rental_products(name, category, details, price, image) VALUES(?,?,?,?,?)");
       $insert_products->execute([$name, $category, $details, $price, $image]);
 
       if($insert_products){
          if($image_size > 2000000){
             $message[] = 'Image size is too large!';
          } else {
             move_uploaded_file($image_tmp_name, $image_folder);
             $message[] = 'New product added!';
             header('Location: admin_rental_product.php'); // Redirect after product is added
             exit(); // Ensure script execution is stopped after the redirect
          }
       }
    }
 }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
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
            margin: 0 auto;
        }

    

        .form-control {
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 150px;
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

        .custom-file-input {
            border-radius: 8px;
            padding: 12px;
        }

        .custom-file-label {
            border-radius: 8px;
            padding: 12px;
        }

        .text-center {
            text-align: center;
        }

        .form-row {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            width: 100%;
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
            <h2 class="mt-2 mb-4 text-center">Add New Product</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" required placeholder="Enter product name">
                    </div>
                    <div class="form-group">
                        <select name="category" class="form-control" required>
                            <option value="" selected disabled>Select category</option>
                            <option value="saree">Saree</option>
                            <option value="dress">Dress</option>
                            <option value="western">Western</option>
                            <option value="kurtis">Kurtis</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <input type="number" min="0" name="price" class="form-control" required placeholder="Enter product price">
                    </div>
                    <div class="form-group">
                        <input type="file" name="image" required class="form-control" accept="image/jpg, image/jpeg, image/png">
                    </div>
                </div>
                <div class="form-group">
                    <textarea name="details" class="form-control" required placeholder="Enter product details" rows="3"></textarea>
                </div>
                <div class="text-center">
                    <input type="submit" class="btn btn-primary" value="Add Product" name="add_product">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
