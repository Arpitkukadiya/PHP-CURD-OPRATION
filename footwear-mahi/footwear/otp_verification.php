<?php
session_start(); // Start the session
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verify'])) {
    // Check if the OTP is set in the session
    if (isset($_SESSION['otp'])) {
        $otp_entered = $_POST['otp'];

        // Check if entered OTP matches the session OTP
        if ($otp_entered == $_SESSION['otp']) {
            $name = $_SESSION['name'];
            $email = $_SESSION['email'];
            $password = $_SESSION['password'];
            $contact = $_SESSION['contact'];

            // Insert the new user into the database
            $insert = $conn->prepare("INSERT INTO `users`(name, email, password, contact) VALUES(?,?,?,?)");
            $insert->execute([$name, $email, $password, $contact]);

            if ($insert) {
                // Clear the session data after successful registration
                unset($_SESSION['otp']);
                unset($_SESSION['name'], $_SESSION['email'], $_SESSION['password'], $_SESSION['contact']);

                // Redirect to the login page after successful registration
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error registering the user. Please try again.";
            }
        } else {
            // OTP mismatch error
            $error_message = "Incorrect OTP. Please try again.";
        }
    } else {
        $error_message = "OTP session expired. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login1.css">

</head>
<body>
    <div class="container">
        <form method="POST">
            <h2 class="text-center">Enter OTP</h2>

            <?php
            // Display error message if OTP verification fails
            if (isset($error_message)) {
                echo '<div class="alert alert-danger">' . $error_message . '</div>';
            }
            ?>

<div class="form-group">
    <label for="otp">OTP</label>
    <input type="text" name="otp" class="form-control" placeholder="Check your OTP" pattern="\d{6}" title="OTP must be exactly 6 digits" required>
</div>


            <button type="submit" name="verify" class="btn btn-primary btn-block">Verify OTP</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
