<?php
@include 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM rental_products WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM rental_products WHERE id = ?");
   $delete_products->execute([$delete_id]);

   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_rental_product.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_styles.css">
    <style>
        .card-img-top {
            object-fit: cover;
            height: 350px;
            width: 100%;
        }

        .product-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card-body {
            padding: 1.25rem;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }

        .btn-custom {
            margin-right: 10px;
            padding: 0.5rem 1rem;
        }

        .empty {
            text-align: center;
            font-size: 1.2rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="d-flex">
    <?php include 'admin_navbar.php' ?>

    <div class="content w-100">
        <div class="container-fluied mx-3 mt-1">
            <section class="show-products">
            <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-center mb-0">Manages Rental Products</h3>
    <a href="admin_rental_add_product.php" class="btn btn-primary">Add product</a>
</div>

                <div class="row">
                    <?php
                        $show_products = $conn->prepare("SELECT * FROM rental_products");
                        $show_products->execute();
                        if($show_products->rowCount() > 0){
                            while($fetch_rental_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
                    ?>
                    <div class="col-md-3 mb-4">
                        <div class="card product-card">
                            <img src="uploaded_img/<?= $fetch_rental_products['image']; ?>" class="card-img-top" alt="<?= $fetch_rental_products['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $fetch_rental_products['name']; ?></h5>
                                <p class="card-text"><?= $fetch_rental_products['details']; ?></p>
                                <p class="card-text"><strong>Price: </strong>₹<?= $fetch_rental_products['price']; ?>/-</p>
                                <p class="card-text"><strong>Category: </strong><?= $fetch_rental_products['category']; ?></p>
                                <div class="d-flex justify-content-between">
                                    <a href="admin_rental_update.php?update=<?= $fetch_rental_products['id']; ?>" class="btn btn-info btn-sm btn-custom">Update</a>
                                    <a href="admin_rental_product.php?delete=<?= $fetch_rental_products['id']; ?>" class="btn btn-danger btn-sm btn-custom" onclick="return confirm('Delete this product?');">Delete</a>
                                </div>
                            </div>
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
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
