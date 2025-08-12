<?php
@include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $pass = md5($_POST['pass']);
    $pass = filter_var($pass, FILTER_SANITIZE_STRING);

    $sql = "SELECT * FROM `users` WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email, $pass]);
    $rowCount = $stmt->rowCount();  

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rowCount > 0) {
        if ($row['user_type'] == 'user') {
            $_SESSION['user_id'] = $row['id'];
            header('location:index.php');
        } elseif ($row['user_type'] == 'admin') {
            $_SESSION['admin_id'] = $row['id']; // Ensure admin session is set
            header('location:admin_dashboard.php'); // Redirect to admin dashboard
        } else {
            $message[] = 'No user found!';
        }
    } else {
        $message[] = 'Incorrect email or password!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="login1.css">
   
</head>
<body>
        

        <div class="container">
        
            <form action="" method="POST">
                <h2 class="text-center">Login</h2>
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
    <label for="email" class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" title="Enter a valid email address">
</div>
                <div class="form-group">
                    <label for="pass" class="form-label">Password</label>
                    <input type="password" name="pass" class="form-control" required>
                </div>
                <button type="submit" name="submit" class="btn btn-primary btn-block">Login Now</button>
                <p class="h6 text-center mt-3">Create your Account <a href="register.php">Register here</a></p>
                </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
