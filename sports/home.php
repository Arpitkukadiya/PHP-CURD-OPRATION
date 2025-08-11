<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_wishlist'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);

   $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
   $check_wishlist_numbers->execute([$p_name, $user_id]);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_wishlist_numbers->rowCount() > 0){
      $message[] = 'already added to wishlist!';
   }elseif($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{
      $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?,?,?,?,?)");
      $insert_wishlist->execute([$user_id, $pid, $p_name, $p_price, $p_image]);
      $message[] = 'added to wishlist!';
   }

}

if(isset($_POST['add_to_cart'])){

   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $p_name = $_POST['p_name'];
   $p_name = filter_var($p_name, FILTER_SANITIZE_STRING);
   $p_price = $_POST['p_price'];
   $p_price = filter_var($p_price, FILTER_SANITIZE_STRING);
   $p_image = $_POST['p_image'];
   $p_image = filter_var($p_image, FILTER_SANITIZE_STRING);
   $p_qty = $_POST['p_qty'];
   $p_qty = filter_var($p_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $check_wishlist_numbers = $conn->prepare("SELECT * FROM `wishlist` WHERE name = ? AND user_id = ?");
      $check_wishlist_numbers->execute([$p_name, $user_id]);

      if($check_wishlist_numbers->rowCount() > 0){
         $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name = ? AND user_id = ?");
         $delete_wishlist->execute([$p_name, $user_id]);
      }

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $pid, $p_name, $p_price, $p_qty, $p_image]);
      $message[] = 'added to cart!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Custom CSS -->
   <link rel="stylesheet" href="css/style.css">
   <style>
      	*{
			margin: 0;
			padding: 0;
			box-sizing: border-box;
            padding-top:100px;
		}


		.home-category .box-container .box img{
   width: 100%;
   height: 12vw;
   margin-bottom: 1rem;
}

.products .box-container .box img{
   width: 100%;
   height: 12vw;
   margin-bottom: 1rem;
}

		.a{
			width: 100%;
			min-height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
         background: url(../images/home-bg11.jpg) no-repeat;
		}
.slide-container {
    position: fixed; /* Ensures it stays in the background and doesn't scroll */
    top: 0;
    left: 0;
    width: 100%; /* Full width of the viewport */
    height: 100%; /* Full height of the viewport */
    border: none; /* Removes the border */
    box-shadow: none; /* Removes the shadow */
    z-index: -1; /* Pushes it behind other content */
}

.slide-container .slides {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.slide-container .slides img {
    width: 100%;
    height: 100%;
    position: absolute;
    object-fit: cover;
}

.slide-container .slides img:not(.active) {
    top: 0;
    left: -100%;
}
		span.next, span.prev{
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			padding: 14px;
			color: #eee;
			font-size: 24px;
			font-weight: bold;
			transition: 0.5s;
			border-radius: 3px;
			user-select: none;
			cursor: pointer;
			z-index: 1;
		}
		span.next{
			right: 20px;
		}
		span.prev{
			left: 20px;
		}
		span.next:hover, span.prev:hover{
			background-color: #ede6d6;
			opacity: 0.8;
			color: #222;
		} 
		.dotsContainer{
			position: absolute;
			bottom: 5px;
			z-index: 3;
			left: 50%;
			transform: translateX(-50%);
		}
		.dotsContainer .dot{
			width: 15px;
			height: 15px;
			margin: 0px 2px;
			border: 3px solid #bbb;
			border-radius: 50%;
			display: inline-block;
			cursor: pointer;
			transition: background-color 0.6s ease;
		}
		.dotsContainer .active{
			background-color: #555;
		}

		@keyframes next1{
			from{
				left: 0%
			}
			to{
				left: -100%;
			}
		}
		@keyframes next2{
			from{
				left: 100%
			}
			to{
				left: 0%;
			}
		}

		@keyframes prev1{
			from{
				left: 0%
			}
			to{
				left: 100%;
			}
		}
		@keyframes prev2{
			from{
				left: -100%
			}
			to{
				left: 0%;
			}
		}

      .h3{
      color: #f6ebeb;
      }
     
     
      </style>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home page</title>

   <!-- font awesome cdn link-->  
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
	
   <!-- custom css file link  -->
    <style>
        /* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f4f7f8;
}

/* Home Page Container */
.a {
    width: 100%;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: url(../images/home-bg11.jpg) no-repeat center center fixed;
    background-size: cover;
    padding: 40px 0;
}
		.slide-container {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: -1;
		}

		.slides {
			width: 100%;
			height: 100%;
			position: relative;
			overflow: hidden;
		}

		.slides img {
			width: 100%;
			height: 100%;
			position: absolute;
			object-fit: cover;
			transition: 1s ease;
		}

		.slides img:not(.active) {
			opacity: 0;
		}

		.buttons {
			position: absolute;
			top: 50%;
			left: 0;
			width: 100%;
			display: flex;
			justify-content: space-between;
			z-index: 1;
		}

		span.next,
		span.prev {
			padding: 14px;
			color: #eee;
			font-size: 24px;
			font-weight: bold;
			background-color: rgba(0, 0, 0, 0.5);
			border-radius: 3px;
			cursor: pointer;
			transition: 0.5s;
		}

		span.next:hover,
		span.prev:hover {
			background-color: #ede6d6;
			opacity: 0.8;
			color: #222;
		}

		.dotsContainer {
			position: absolute;
			bottom: 5px;
			left: 50%;
			transform: translateX(-50%);
			z-index: 2;
		}

		.dotsContainer .dot {
			width: 15px;
			height: 15px;
			margin: 0 2px;
			border: 3px solid #bbb;
			border-radius: 50%;
			display: inline-block;
			cursor: pointer;
			transition: background-color 0.6s ease;
		}

		.dotsContainer .active {
			background-color: #555;
		}
/* Section Styling */
section {
    padding: 60px 0;
}

.home-category,
.products {
    text-align: center;
    padding-top:100px;
}

/* Category and Products Section */
.home-category .title,
.products .title {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 30px;
}

.box-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 30px;
}

/* Card Styling */
.box {
    width: 250px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    text-align: center;
    padding: 20px;
    transition: transform 0.3s ease;
}

.box:hover {
    transform: translateY(-10px);
}

.box img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 15px;
}

.box h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 15px;
}

/* Buttons */
.btn,
.option-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    margin-top: 10px;
    transition: background-color 0.3s;
}

.btn:hover,
.option-btn:hover {
    background-color: #0056b3;
}

/* Product Form Styling */
form.box {
    padding: 0;
}

.qty {
    width: 50px;
    padding: 5px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
}

input[type="submit"] {
    border: none;
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

input[type="submit"]:hover {
    background-color: #218838;
}

.empty {
    font-size: 1.2rem;
    color: #d9534f;
}

/* Footer Styling */
footer {
    background-color: #333;
    color: #fff;
    padding: 20px;
    text-align: center;
}

footer p {
    margin: 0;
}

footer a {
    color: #00d1ff;
    text-decoration: none;
}

footer a:hover {
    text-decoration: underline;
}

/* Responsiveness */
@media (max-width: 768px) {
    .box-container {
        flex-direction: column;
        align-items: center;
    }
    
    .box {
        width: 100%;
        margin-bottom: 20px;
    }
    
    .slides img {
        height: 50vh;
    }
}

        </style>

</head>
<body>
   
<?php include 'header.php'; ?>

<div class="home-bg">

<section class="home-category">

   <h1 class="title">shop by category</h1>

   <div class="box-container">

      <div class="box">
         <img src="1.webp" alt="">
         <h3>CRICKET</h3>
         <!--p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Exercitationem, quaerat.</p-->
         <a href="category.php?category=CRICKET" class="btn"> CRICKET</a>
      </div>
    
      <div class="box">
         <img src="2.jpeg" alt="">
         <h3>FOOTBALL</h3>
         <!--p>Spicy soul food from Gujarat's heartland, a vibrant orchestra of earthy dals, tangy curries, and crispy flatbreads. ️.</p-->
         <a href="category.php?category=FOOTBALL" class="btn">FOOTBALL</a>
      </div>

      <div class="box">
         <img src="3.webp" alt="">
         <h3>HOCKEY</h3>
         <!--p>Sun-kissed simplicity meets vibrant passion in a culinary aria of pasta, pizza, and regional treasures.</p-->
         <a href="category.php?category=HOCKEY" class="btn">HOCKEY</a>
      </div>

      <div class="box">
         <img src="4.jpg" alt="">
         <h3>BADMINTON</h3>
         <!--p>While not always fiery, Punjabi food definitely packs a punch with warming spices like ginger, garlic, chili peppers, and garam masala..</p-->
         <a href="category.php?category=BADMINTON" class="btn">BADMINTON</a>
      </div>

   </div>

</section>

<section class="products">

   <h1 class="title">latest products</h1>

   <div class="box-container">

   <?php
      $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
      $select_products->execute();
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
      <!--div class="price">₹<span><?= $fetch_products['price']; ?></span>/-</div-->
     
      <img src="uploaded_img/<?= $fetch_products['image']; ?>" alt="">
      <div class="name"><?= $fetch_products['name']; ?>    ₹<?= $fetch_products['price']; ?>/-</div>
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_products['name']; ?>">
      <input type="hidden" name="p_price" value="<?= $fetch_products['price']; ?>">
      <input type="hidden" name="p_image" value="<?= $fetch_products['image']; ?>">
      <input type="number" min="1" value="1" name="p_qty" class="qty">
	  <a href="view_page.php?pid=<?= $fetch_products['id']; ?>" class="btn">product information</a>
      <input type="submit" value="add to wishlist" class="option-btn" name="add_to_wishlist">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

   </div>

</section>







<?php include 'footer.php'; ?>

<script src="js/script.js">
	<!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Custom CSS -->
</script>

</body>
</html>