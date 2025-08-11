<?php
// Database configuration
$host = 'localhost';         // Replace with your database host (default is localhost)
$dbname = 'sports';          // Replace with your database name
$username = 'root';          // Replace with your database username (default is root for XAMPP)
$password = '';              // Replace with your database password (default is empty for XAMPP)


// Database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Include PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST['submit'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = md5(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));
    $cpass = md5(filter_var($_POST['cpass'], FILTER_SANITIZE_STRING));
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;
    $otp = rand(100000, 999999); // Generate OTP

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
    $select->execute([$email]);

    if ($select->rowCount() > 0) {
        $message[] = 'User email already exists!';
    } else {
        if ($pass != $cpass) {
            $message[] = 'Passwords do not match!';
        } else {
            $insert = $conn->prepare("INSERT INTO `users` (name, email, password, image, otp) VALUES (?, ?, ?, ?, ?)");
            $insert->execute([$name, $email, $pass, $image, $otp]);

            if ($insert) {
                move_uploaded_file($image_tmp_name, $image_folder);

                // Send OTP
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'arpitkukadiya10@gmail.com';
                    $mail->Password = 'crmscaebqyzqvist';
                    $mail->SMTPSecure = 'ssl';
                    $mail->Port = 465;
                    $mail->setFrom('your_email@gmail.com', 'Sports Website');
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Verification - OTP';
                    $mail->Body = "<p>Hello $name,</p><p>Your OTP is <strong>$otp</strong>.</p>";

                    $mail->send();
                    $message[] = 'Registration successful! Verify your email.';
                    header('Location: verify_email.php?email=' . urlencode($email));
                    exit();
                } catch (Exception $e) {
                    $message[] = 'Error sending email: ' . $e->getMessage();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <h3>Register</h3>
        <input type="text" name="name" placeholder="Enter your name" required>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="password" name="pass" placeholder="Enter your password" required>
        <input type="password" name="cpass" placeholder="Confirm your password" required>
        <input type="file" name="image" required>
        <input type="submit" name="submit" value="Register">
    </form>
    <?php if (isset($message)) { foreach ($message as $msg) { echo "<p>$msg</p>"; } } ?>
</body>
</html>
