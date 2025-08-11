<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_product'])){

   $pid = $_POST['pid'];
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
   $old_image = $_POST['old_image'];

   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, details = ?, price = ? WHERE id = ?");
   $update_product->execute([$name, $category, $details, $price, $pid]);

   $message[] = 'Product updated successfully!';

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'Image size is too large!';
      }else{

         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->execute([$image, $pid]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'Image updated successfully!';
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
      /* Update Product Section (Classic Card Style) */
      .update-product {
          margin: 40px auto;
          padding: 10px;
          background: #fff;
          border-radius: 8px;
          box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
          max-width: 700px;
          width: 100%;
          text-align: center;
          font-family: 'Arial', sans-serif;
      }

      /* Title Style */
      .update-product .title {
          font-size: 2.2rem;
          color: #333;
          margin-bottom: 20px;
          font-weight: 600;
      }

      /* Form Layout */
      .update-product form {
          display: flex;
          flex-direction: column;
          align-items: center;
      }

      /* Input Fields Styling */
      .update-product input[type="text"],
      .update-product input[type="number"],
      .update-product select,
      .update-product textarea,
      .update-product input[type="file"] {
          width: 90%;
          max-width: 500px;
          padding: 12px;
          margin: 10px 0;
          border: 1px solid #ccc;
          border-radius: 6px;
          font-size: 1rem;
          box-sizing: border-box;
          transition: border-color 0.3s ease-in-out;
      }

      /* Focus Effect for Inputs */
      .update-product input[type="text"]:focus,
      .update-product input[type="number"]:focus,
      .update-product select:focus,
      .update-product textarea:focus,
      .update-product input[type="file"]:focus {
          border-color: #007bff;
          outline: none;
      }

      /* Image Preview Styling */
      .update-product img {
          width: 180px;
          height: 180px;
          object-fit: cover;
          margin-bottom: 20px;
          border-radius: 6px;
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      }

      /* Buttons Styling */
      .update-product .flex-btn {
          display: flex;
          justify-content: space-between;
          width: 100%;
          max-width: 500px;
          margin-top: 20px;
      }

      .update-product .btn,
      .update-product .option-btn {
          background: #007bff;
          color: white;
          padding: 12px 25px;
          border-radius: 6px;
          text-decoration: none;
          font-size: 1.1rem;
          width: 45%;
          text-align: center;
          transition: background-color 0.3s ease-in-out;
      }

      /* Hover Effect for Buttons */
      .update-product .btn:hover,
      .update-product .option-btn:hover {
          background: #0056b3;
      }

      /* Empty State Message */
      .update-product .empty {
          font-size: 1.2rem;
          color: #e74c3c;
          margin-top: 20px;
      }

      /* Error Message Styling */
      .update-product .error-message {
          font-size: 1rem;
          color: #e74c3c;
          margin-top: 10px;
          font-weight: 500;
      }

      /* Success Message Styling */
      .update-product .success-message {
          font-size: 1rem;
          color: #2ecc71;
          margin-top: 10px;
          font-weight: 500;
      }
   </style>
</head>
<body>

<?php include 'admin_header.php'; ?>




<section class="update-product">

   <h1 class="title">Update Product</h1>   

   <?php




if(isset($message)){
          foreach($message as $msg){
              echo "<p class='success-message'>$msg</p>";
          }
      }
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <input type="text" name="name" placeholder="Enter product name" required class="box" value="<?= $fetch_products['name']; ?>">
      <input type="number" name="price" min="0" placeholder="Enter product price" required class="box" value="<?= $fetch_products['price']; ?>">
      <select name="category" class="box" required>
         <option selected><?= $fetch_products['category']; ?></option>
         <option value="south indian">South Indian</option>
         <option value="kathiyavadi">Kathiyavadi</option>
         <option value="italian food">Italian Food</option>
         <option value="punjabi food">Punjabi Food</option>
      </select>
      <textarea name="details" required placeholder="Enter product details" class="box" cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <div class="flex-btn">
         <input type="submit" class="btn" value="Update Product" name="update_product">
         <a href="admin_products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No products found!</p>';
      }
   ?>

</section>

<script src="js/script.js"></script>

</body>
</html>
