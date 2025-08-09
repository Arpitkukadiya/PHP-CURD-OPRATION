<?php
session_start(); // Start the session
include 'config.php';
// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);
    $cpass = md5($_POST['cpass']);
    $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);
    $contact = $_POST['contact'];
    $contact = filter_var($contact, FILTER_SANITIZE_STRING);

    // Check if email already exists
    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);

    if ($select->rowCount() > 0) {
        $message[] = 'User email already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            // Generate OTP
            $otp = rand(100000, 999999); // Generate a random 6-digit OTP
            $_SESSION['otp'] = $otp;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = $pass;
            $_SESSION['contact'] = $contact;

            // Send OTP via email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'rewards06a@gmail.com'; // Your email address
                $mail->Password = 'iqsz wygn btfz ispa'; // Your email app-specific password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('your_email@gmail.com', 'FOOTWEAR-MAHII');
                $mail->addAddress($email, $name);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Code for Registration';
                $mail->Body = "<p>Your OTP Code is: <b>$otp</b></p>";

                $mail->send();

                header("Location: otp_verification.php");
                exit(); // Redirect to OTP verification page

            } catch (Exception $e) {
                $_SESSION['error'] = "Mailer Error: {$mail->ErrorInfo}";
                header("Location: otp_verification.php");
                exit();
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
    <title>Register</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login1.css">
</head>

<body>
    <div class="container">
        <form action="" method="POST">
            <h2 class="text-center">Register Now</h2>
            <?php
            if (isset($message)) {
                foreach ($message as $msg) {
                    echo '
                    <div class="message">
                        <span>' . $msg . '</span>
                        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                    </div>
                    ';
                }
            }
            ?>
             <div class="form-group">
        <label for="name" class="form-label">Name</label>
        <input type="text" name="name" id="name" class="form-control" pattern="^[A-Za-z ]+$" title="Name should contain only letters and spaces" required>
    </div>
    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Enter a valid email address" required>
    </div>
    <div class="form-group">
        <label for="contact" class="form-label">Contact Number</label>
        <input type="text" name="contact" id="contact" class="form-control" pattern="^[0-9]{10}$" title="Contact number must be exactly 10 digits" required>
    </div>
            <div class="form-group">
                <label for="pass" class="form-label">Password</label>
                <input type="password" name="pass" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="cpass" class="form-label">Confirm Password</label>
                <input type="password" name="cpass" class="form-control" required>
            </div>
    <button type="submit" name="submit" class="btn btn-primary btn-block">Register Now</button>
    <p class="text-center mt-3">Already have an account? <a href="login.php">Login here</a></p>
    </form>
    </div>


    <script>
    document.getElementById("myForm").addEventListener("submit", function(event) {
        var name = document.getElementById("name").value;
        var email = document.getElementById("email").value;
        var contact = document.getElementById("contact").value;
        
        var namePattern = /^[A-Za-z ]+$/;
        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        var contactPattern = /^[0-9]{10}$/;
        
        if (!namePattern.test(name)) {
            alert("Name should contain only letters and spaces.");
            event.preventDefault();
        }
        if (!emailPattern.test(email)) {
            alert("Enter a valid email address.");
            event.preventDefault();
        }
        if (!contactPattern.test(contact)) {
            alert("Contact number must be exactly 10 digits.");
            event.preventDefault();
        }
    });
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>