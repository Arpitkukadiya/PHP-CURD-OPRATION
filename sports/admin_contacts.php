<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `message` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:admin_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Messages</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

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
      /* General Reset */
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
      }

      body {
         font-family: Arial, sans-serif;
         background-color: #f5f5f5;
         color: #333;
      }

      /* Header Styles */
      header {
         background-color: #003366;
         color: #fff;
         padding: 15px 20px;
         display: flex;
         justify-content: space-between;
         align-items: center;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }

      header .logo {
         font-size: 24px;
         font-weight: bold;
         text-transform: uppercase;
      }

      header nav a {
         text-decoration: none;
         color: #fff;
         margin-left: 15px;
         font-size: 16px;
         transition: color 0.3s;
      }

      header nav a:hover {
         color: #ffcc00;
      }

      /* Section Styles */
      .messages {
         padding: 20px;
         margin-top: 20px;
         padding-left: 400px;
      }

      .messages .title {
         font-size: 24px;
         margin-bottom: 20px;
         color: #333;
         text-align: center;
         text-transform: uppercase;
      }

      .box-container {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
         gap: 20px;
      }

      .box {
         background-color: #fff;
         padding: 15px;
         border-radius: 10px;
         box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
         text-align: center;
      }

      .box p {
         margin: 5px 0;
         font-size: 14px;
         color: #555;
      }

      .box span {
         font-weight: bold;
         color: #333;
      }

      .delete-btn {
         display: inline-block;
         background-color: #ff6666;
         color: #fff;
         padding: 8px 15px;
         text-decoration: none;
         border-radius: 5px;
         font-size: 14px;
         margin-top: 10px;
         transition: background-color 0.3s;
      }

      .delete-btn:hover {
         background-color: #cc3333;
      }

      /* Responsive Design */
      @media (max-width: 768px) {
         header {
            flex-wrap: wrap;
            text-align: center;
         }

         header nav a {
            margin-left: 0;
            margin-top: 10px;
         }

         .messages {
            padding-left: 20px;
         }
      }
   </style>
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="messages">

   <h1 class="title">Messages</h1>

   <div class="box-container">

   <?php
      $select_message = $conn->prepare("SELECT * FROM `message`");
      $select_message->execute();
      if($select_message->rowCount() > 0){
         while($fetch_message = $select_message->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>User ID: <span><?= $fetch_message['user_id']; ?></span></p>
      <p>Name: <span><?= $fetch_message['name']; ?></span></p>
      <p>Number: <span><?= $fetch_message['number']; ?></span></p>
      <p>Email: <span><?= $fetch_message['email']; ?></span></p>
      <p>Message: <span><?= $fetch_message['message']; ?></span></p>
      <a href="admin_contacts.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Delete this message?');" class="delete-btn">Delete</a>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">You have no messages!</p>';
      }
   ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
