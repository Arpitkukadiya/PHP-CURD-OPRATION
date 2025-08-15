<?php
// signin.php - User Login
include 'conn.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uName = $_POST['uName'];
    $uPass = md5($_POST['uPass']);
    $sql = "SELECT * FROM Users WHERE uName='$uName'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] == 'blocked') {
            echo "Your account is blocked.";
        } elseif ($row['uPass'] == $uPass) {
            $_SESSION['user'] = $uName;
            $conn->query("UPDATE Users SET login_attempts=0 WHERE uName='$uName'");
            header("Location: welcome.php");
        } else {
            $attempts = $row['login_attempts'] + 1;
            if ($attempts >= 3) {
                $conn->query("UPDATE Users SET status='blocked' WHERE uName='$uName'");
                echo "Your account is blocked after 3 failed attempts.";
            } else {
                $conn->query("UPDATE Users SET login_attempts=$attempts WHERE uName='$uName'");
                header("Location: login_failed.php");
            }
        }
    } else {
        echo "Invalid Username";
    }
}
?>

<form method="POST">
    Username: <input type="text" name="uName" required><br>
    Password: <input type="password" name="uPass" required><br>
    <button type="submit">Sign In</button>
    
    <a href="signup.php">sign up</a>
    <a href="forgot_password.php">forgot password</a></form>
