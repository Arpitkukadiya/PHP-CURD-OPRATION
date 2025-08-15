<?php
$cookie_name = "secure_cookie";
$cookie_value = "This is a secure cookie";
$expiry_time = time() + (86400 * 7); 

setcookie($cookie_name, $cookie_value, $expiry_time, "/", "", true, true); 
echo "Secure cookie has been set.";
?>

<?php
if (isset($_COOKIE['tata'])) {
    echo "Welcome back! Your cookie value is: " . $_COOKIE['tata'];
} else {
    echo "Welcome! This is your first visit.";
}
?>

<?php
setcookie("username", "JayKishan", time() + 3600, "/"); // 1 hour expiry
echo "Cookie 'username' has been set.";

header("Location: retrievecookie.php"); 
?>
