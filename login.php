<?php
include 'conn.php';

session_start();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM login WHERE email='$email' AND password='$password'";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['email'] = $email;
        header("Location: index1.php");
     
        exit();
    } else {
        echo "Invalid email or password.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <form method="POST">
        <label>Email</label>
        <input type="text" name="email" required><br><br>

        <label>Password</label>
        <input type="password" name="password" required><br><br>

        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>
