<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
//required files
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
 
if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .' '. $_POST['street'] .' '. $_POST['city'] .' '. $_POST['state'] .' '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $placed_on = date('d-M-Y');

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $cart_query->execute([$user_id]);
   if($cart_query->rowCount() > 0){
      while($cart_item = $cart_query->fetch(PDO::FETCH_ASSOC)){
         $cart_products[] = $cart_item['name'].' ( '.$cart_item['quantity'].' )';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      };
   };

   $total_products = implode(', ', $cart_products);

   $order_query = $conn->prepare("SELECT * FROM `orders` WHERE name = ? AND number = ? AND email = ? AND method = ? AND address = ? AND total_products = ? AND total_price = ?");
   $order_query->execute([$name, $number, $email, $method, $address, $total_products, $cart_total]);

   if($cart_total == 0){
      $message[] = 'your cart is empty';
   }elseif($order_query->rowCount() > 0){
      $message[] = 'order placed already!';
   }else{
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $cart_total, $placed_on]);
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      // Send email notification
      $mail = new PHPMailer(true);

      // ... (Your existing email configuration)
   //Server settings
   $mail->isSMTP();                              //Send using SMTP
   $mail->Host       = 'smtp.gmail.com';       //Set the SMTP server to send through
   $mail->SMTPAuth   = true;             //Enable SMTP authentication
   $mail->Username   = 'arpitkukadiya10@gmail.com';   //SMTP write your email
   $mail->Password   = 'crmscaebqyzqvist';      //SMTP password
   $mail->SMTPSecure = 'ssl';            //Enable implicit SSL encryption
   $mail->Port       = 465;                                    

    //Recipients
    $mail->setFrom( $_POST["email"], $_POST["name"]); // Sender Email and name
    $mail->addAddress($_POST["email"]);     //Add a recipient email  
    $mail->addReplyTo($_POST["email"], $_POST["name"]); // reply to sender email
 
      // Content
      $mail->isHTML(true);              // Set email format to HTML
      $mail->Subject = 'Your order has been placed';   // Email subject
      $mail->Body = 'Thank you for placing your order. Your order details: ' . $total_products . '. Total Price: ₹' . $cart_total; // Email message

      try {
         $mail->send();
         echo "
         <script> 
            alert('Your order placed successfully. Confirmation email sent.');
            document.location.href = 'checkout.php';
         </script>
         ";
      } catch (Exception $e) {
         echo "
         <script> 
            alert('Error: Unable to send confirmation email. Please contact support.');
            document.location.href = 'checkout.php';
         </script>
         ";
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
   <title>checkout</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <style>
       body {
         background-color: #f4f7f8;
         font-family: 'Roboto', sans-serif;
         margin: 0;
         padding: 0;
      }

      .display-orders, .checkout-orders {
         margin: 20px;
         padding: 20px;
         background: #fff;
         border-radius: 10px;
         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
      }

      .display-orders p, .checkout-orders h3 {
         font-size: 1.2rem;
         color: #333;
      }

      .grand-total {
         font-size: 1.5rem;
         color: #ff5722;
         text-align: center;
         margin-top: 10px;
      }

      .empty {
         color: #777;
         font-size: 1.2rem;
         text-align: center;
      }

      .checkout-orders form {
         display: flex;
         flex-direction: column;
         gap: 20px;
      }

      .inputBox {
         display: flex;
         flex-direction: column;
         gap: 5px;
      }

      .inputBox input, .inputBox select {
         padding: 10px;
         border: 1px solid #ccc;
         border-radius: 5px;
         font-size: 1rem;
      }

      .btn {
         padding: 10px;
         border: none;
         background: #1a73e8;
         color: white;
         border-radius: 5px;
         cursor: pointer;
         font-size: 1rem;
         text-align: center;
      }

      .btn:hover {
         background: #0056b3;
      }

      .btn.disabled {
         background: #ccc;
         pointer-events: none;
      }
      </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="display-orders">

   <?php
      $cart_grand_total = 0;
      $select_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart_items->execute([$user_id]);
      if($select_cart_items->rowCount() > 0){
         while($fetch_cart_items = $select_cart_items->fetch(PDO::FETCH_ASSOC)){
            $cart_total_price = ($fetch_cart_items['price'] * $fetch_cart_items['quantity']);
            $cart_grand_total += $cart_total_price;
   ?>
   <p> <?= $fetch_cart_items['name']; ?> <span>(<?= '₹'.$fetch_cart_items['price'].'/- x '. $fetch_cart_items['quantity']; ?>)</span> </p>
   <?php
    }
   }else{
      echo '<p class="empty">your cart is empty!</p>';
   }
   ?>
   <div class="grand-total">grand total : <span>₹<?= $cart_grand_total; ?>/-</span></div>
</section>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>your name :</span>
            <input type="text" name="name" placeholder="enter your name" class="box" required>
         </div>
         <div class="inputBox">
            <span>your number :</span>
            <input type="number" name="number" placeholder="enter your number" class="box" required>
         </div>
         <div class="inputBox">
            <span>your email :</span>
            <input type="email" name="email" placeholder="enter your email" class="box" required>
         </div>
         <div class="inputBox">
            <span>payment method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">cash on delivery</option>
               <option value="credit card">credit card</option>
             
               <option value="paytm">paytm</option>
               <option value="paypal">paypal</option>
            </select>
         </div>
         
         <div class="inputBox">
            <span>address line 01 :</span>
            <input type="text" name="flat" placeholder="e.g. flat number" class="box" required>
         </div>
         <div class="inputBox">
            <span>address line 02 :</span>
            <input type="text" name="street" placeholder="e.g. street name" class="box" required>
         </div>
         <div class="inputBox">
            <span>city :</span>
            <input type="text" name="city" placeholder="e.g. Gandhinagar" class="box" required>
         </div>
         <div class="inputBox">
            <span>state :</span>
            <input type="text" name="state" placeholder="e.g. gujarat" class="box" required>
         </div>
         <div class="inputBox">
            <span>country :</span>
            <input type="text" name="country" placeholder="e.g. India" class="box" required>
         </div>
         <div class="inputBox">
            <span>pin code :</span>
            <input type="number" min="0" name="pin_code" placeholder="e.g. 382016" class="box" required>
         </div>
      </div>
      <input type="submit" name="order" class="btn <?= ($cart_grand_total > 1)?'':'disabled'; ?>" value="place order">

   </form>

</section>
</body>
</html>
