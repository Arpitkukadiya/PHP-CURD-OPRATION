<?php
// forgot_password.php - Secure Password Recovery
include 'conn.php';

function generateRandomPassword($length = 10) {
    // Escape the `$` character to avoid errors
    $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#\$_-";
    return substr(str_shuffle($characters), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uName = $_POST['uName'];
    $secAns = md5($_POST['secAns']);
    
    // Check if the user exists with the correct security answer
    $sql = "SELECT * FROM Users WHERE uName='$uName' AND secAns='$secAns'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate a random password (human-readable)
        $newPassword = generateRandomPassword(10);
        $hashedPassword = md5($newPassword); // Store the hashed password in the database

        // Update the password in the database
        $conn->query("UPDATE Users SET uPass='$hashedPassword' WHERE uName='$uName'");

        // Show the human-readable password to the user
        echo "<p style='color: green; font-size: 18px;'>Your new password is: <b>$newPassword</b></p>";
        echo "<p style='color: red; font-size: 16px;'>Please change it after logging in.</p>";
    } else {
        echo "<p style='color: red; font-size: 16px;'>Invalid Security Answer</p>";
    }
}
?>

<form method="POST" style="width: 300px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 2px 2px 10px rgba(0,0,0,0.1);">
    <label style="font-weight: bold;">Username:</label> 
    <input type="text" name="uName" required style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px;"><br>
    
    <label style="font-weight: bold;">Security Answer:</label> 
    <input type="text" name="secAns" required style="width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ccc; border-radius: 5px;"><br>
    
    <button type="submit" style="width: 100%; padding: 10px; background-color: green; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Recover Password
    </button>
</form>
