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
   $select_delete_image = $conn->prepare("SELECT image FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);

   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_product.php');
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
            height: 400px;
            width: 100%;
        }
      
        .product-name {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .product-price {
            font-size: 1.1rem;
            color: green;
        }
        .product-category {
            font-size: 1rem;
            color: #555;
        }
        .action-btns a {
            margin-right: 10px;
        }
        p{
            margin-bottom: 10px;
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
    <h2 class="text-center">Products List</h2>
    <a href="admin_add_product.php" class="btn btn-primary">Add product</a>
</div>

                <div class="row">
                    <?php
                        $show_products = $conn->prepare("SELECT * FROM `products`");
                        $show_products->execute();
                        if($show_products->rowCount() > 0){
                            while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
                    ?>
                        <div class="col-md-3 product-card mb-3">
                            <div class="card">
                                <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="<?= $fetch_products['name']; ?>" class="card-img-top">
                                <div class="card-body">
                                    <h5 class="product-name"><?= $fetch_products['name']; ?></h5>
                                    <p class="product-price">₹<?= $fetch_products['price']; ?>/-</p>
                                    <p class="product-category"><?= $fetch_products['category']; ?></p>
                                    <p class="card-text"><?= substr($fetch_products['details'], 0, 100); ?>...</p>
                                    <div class="action-btns">
                                        <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="btn btn-info btn-sm">Update</a>
                                        <a href="admin_product.php?delete=<?= $fetch_products['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this product?');">Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12"><p class="text-center">No products added yet!</p></div>';
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
