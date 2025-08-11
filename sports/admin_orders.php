<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_order'])){

   $order_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   $update_payment = filter_var($update_payment, FILTER_SANITIZE_STRING);
   $update_orders = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_orders->execute([$update_payment, $order_id]);
   $message[] = 'payment has been updated!';

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $delete_orders = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_orders->execute([$delete_id]);
   header('location:admin_orders.php');

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>orders</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

<style>
   body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7f8;
    margin: 0;
    padding: 0;
}

.header {
    background: #1a1a1a;
    color: white;
    padding: 20px;
    font-size: 2rem;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
}

.sidebar {
    background-color: #1a1a1a;
    position: fixed;
    padding: 20px;
    height: 100vh;
    width: 250px;
    color: #fff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.4);
    top: 70px;
}

.sidebar a {
    color: #dcdcdc;
    font-size: 1rem;
    padding: 50px 75px;
    text-decoration: none;
    display: block;
    border-radius: 4px;
    margin-bottom: 10px;
    transition: background-color 0.3s;
}

.content {
    margin-left: 270px;
    padding: 360px;
    padding-top: 30px;
    padding-left:400px;
}

.placed-orders {
    margin-top: 50px;
    padding: 20px;
    padding-left:400px;
}

.placed-orders .title {
    font-size: 2.5rem;
    color: #1a73e8;
    text-align: center;
    margin-bottom: 30px;
}

.placed-orders .box-container {
    display: flex;
    flex-wrap: wrap;
    gap: 30px;
    justify-content: space-between;
}

.placed-orders .box {
    background-color: #fff;
    border-radius: 10px;
    width: 300px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.placed-orders .box:hover {
    transform: scale(1.05);
}

.placed-orders .box p {
    font-size: 1rem;
    color: #333;
    margin: 8px 0;
}

.placed-orders .box span {
    color: #007bff;
    font-weight: bold;
}

.placed-orders .drop-down {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-top: 10px;
    font-size: 1rem;
}

.placed-orders .flex-btn {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.placed-orders .flex-btn .option-btn {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.placed-orders .flex-btn .option-btn:hover {
    background-color: #0056b3;
}

.placed-orders .flex-btn .delete-btn {
    background-color: #ff0000;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    transition: background 0.3s;
}

.placed-orders .flex-btn .delete-btn:hover {
    background-color: #cc0000;
}

.placed-orders .empty {
    font-size: 1.5rem;
    color: #bbb;
    text-align: center;
    width: 100%;
    margin-top: 50px;
}

   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="placed-orders">

   <h1 class="title">placed orders</h1>

   <div class="box-container">

      <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
      ?>
      <div class="box">
         <p> user id : <span><?= $fetch_orders['user_id']; ?></span> </p>
         <p> placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
         <p> name : <span><?= $fetch_orders['name']; ?></span> </p>
         <p> email : <span><?= $fetch_orders['email']; ?></span> </p>
         <p> number : <span><?= $fetch_orders['number']; ?></span> </p>
         <p> address : <span><?= $fetch_orders['address']; ?></span> </p>
         <p> total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
         <p> total price : <span>₹<?= $fetch_orders['total_price']; ?>/-</span> </p>
         <p> payment method : <span><?= $fetch_orders['method']; ?></span> </p>
         <form action="" method="POST">
            <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
            <select name="update_payment" class="drop-down">
               <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
               <option value="pending">pending</option>
               <option value="completed">completed</option>
            </select>
            <div class="flex-btn">
               <input type="submit" name="update_order" class="option-btn" value="udate">
               <a href="admin_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('delete this order?');">delete</a>
            </div>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      ?>

   </div>

</section>












<script src="js/script.js"></script>

</body>
</html>