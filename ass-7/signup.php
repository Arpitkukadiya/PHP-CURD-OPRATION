<?php
// signup.php - User Registration
include 'conn.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uName = $_POST['uName'];
    $uPass = md5($_POST['uPass']);
    $secQue = $_POST['secQue'];
    $secAns = md5($_POST['secAns']);
    $sql = "INSERT INTO Users (uName, uPass, secQue, secAns) VALUES ('$uName', '$uPass', '$secQue', '$secAns')";
    if ($conn->query($sql)) {
        echo "User Registered Successfully";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<form method="POST">
    Username: <input type="text" name="uName" required><br>
    Password: <input type="password" name="uPass" required><br>
    Security Question: <input type="text" name="secQue" required><br>
    Security Answer: <input type="text" name="secAns" required><br>
    <button type="submit">Sign Up</button>
    <a href="signin.php">sign in</a>
    
</form>
