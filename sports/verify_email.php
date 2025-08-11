<?php
// Database configuration
$host = 'localhost';         // Replace with your database host (default is localhost)
$dbname = 'sports';          // Replace with your database name
$username = 'root';          // Replace with your database username (default is root for XAMPP)
$password = '';              // Replace with your database password (default is empty for XAMPP)


try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

if (isset($_POST['verify'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $otp = filter_var($_POST['otp'], FILTER_SANITIZE_NUMBER_INT);

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND otp = ?");
    $select->execute([$email, $otp]);

    if ($select->rowCount() > 0) {
        $update = $conn->prepare("UPDATE `users` SET verified = 1, otp = NULL WHERE email = ?");
        $update->execute([$email]);
        $message = 'Email verified successfully!';
        header('Location: login.php');
        exit();
    } else {
        $message = 'Invalid OTP!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>
    <form action="" method="POST">
        <h3>Email Verification</h3>
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="number" name="otp" placeholder="Enter the OTP" required>
        <input type="submit" name="verify" value="Verify Email">
    </form>
    <?php if (isset($message)) { echo "<p>$message</p>"; } ?>
</body>
</html>
