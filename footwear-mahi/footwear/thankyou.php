<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank You for Shopping</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
@import url('https://fonts.googleapis.com/css2?family=Baloo+2:wght@400..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
body {
        font-family: "Roboto", sans-serif;
    }

    .container {
      /* padding-top: 50px; */
      /* margin-top: 20px; */
      text-align: center;
    }
  
    .thank-you-icon {
      margin-top: 20px;
      font-size: 100px;
      color: #28a745; /* Green color */
    }

    .thank-you-heading {
      margin-top: 20px;
      color: #333; /* Dark gray color */
      font-size: 32px;
      font-weight: bold;
    }

    .thank-you-message {
      margin-top: 20px;
      color: #666; /* Medium gray color */
      font-size: 18px;
    }

    .continue-shopping-btn {
      margin-top: 30px;
      padding: 10px 30px;
      background-color:rgb(0, 0, 0); /* Blue color */
      color: #fff; /* White color */
      border: none;
      border-radius: 5px;
      font-size: 18px;
      transition: background-color 0.3s ease;
    }

    .continue-shopping-btn:hover {
      background-color:rgb(39, 39, 39); /* Darker blue on hover */
    }
    .product-card {
    background-color: #f8f9fa; /* Light background color */
    border-radius: 20px;
    min-height: 20px;
    margin-bottom: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 15px;
    /* bottom: 20px; */
    transition: all 0.3s ease;
    margin-top: 20px;
  }

  </style>
</head>
<body>
<?php include 'navbar.php' ?>


<div class="container">
  <div class="product-card mx-5">
  <i class="fas fa-check-circle thank-you-icon"></i>
  <h2 class="thank-you-heading">Thank You for Shopping!</h2>
  <p class="thank-you-message">Your order has been successfully placed. We will process your order shortly.</p>
  <p class="thank-you-message">You will receive an email confirmation with your order details.</p>
  <a href="index.php" class="btn btn-primary continue-shopping-btn">Continue Shopping</a>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
