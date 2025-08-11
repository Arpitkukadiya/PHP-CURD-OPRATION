<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `message` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `message`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'sent message successfully!';

   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
<style>
   /* General styles */
body {
    background-color: #f9f9f9;
    font-family: 'Roboto', sans-serif;
    margin: 0;
    padding: 0;
}

/* Title styling */
.contact .title {
    font-size: 2.5rem;
    text-align: center;
    margin: 30px 0;
    color: #1a73e8;
    text-transform: uppercase;
}

/* Contact form container */
.contact {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 70px 150px;
    max-width: 800px;
    margin: 0 auto;
    background-color: #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    border-radius: 50px;
}

/* Form input styles */
.contact form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact form .box {
    width: 100%;
    padding: 15px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f7f7f7;
    transition: border-color 0.3s, box-shadow 0.3s;
}

.contact form .box:focus {
    border-color: #1a73e8;
    box-shadow: 0 0 8px rgba(26, 115, 232, 0.3);
    outline: none;
}

/* Button styling */
.contact form .btn {
    background-color: #1a73e8;
    color: white;
    padding: 15px;
    font-size: 1.1rem;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.contact form .btn:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Responsive design */
@media (max-width: 768px) {
    .contact {
        padding: 30px 15px;
    }

    .contact form {
        gap: 15px;
    }

    .contact form .box {
        font-size: 0.9rem;
        padding: 12px;
    }

    .contact form .btn {
        font-size: 1rem;
        padding: 12px;
    }
}

   </style>
</head>
<body>
   
<?php include 'header.php'; ?>

<section class="contact">

   <h1 class="title">get in touch</h1>

   <form action="" method="POST">
      <input type="text" name="name" class="box" required placeholder="enter your name">
      <input type="email" name="email" class="box" required placeholder="enter your email">
      <input type="number" name="number" min="0" class="box" required placeholder="enter your number">
      <textarea name="msg" class="box" required placeholder="enter your message" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" class="btn" name="send">
   </form>

</section>








<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>