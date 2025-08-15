<!--?php
if (isset($_COOKIE['username'])) {
    echo "Username: " . $_COOKIE['username'];
} else {
    echo "Cookie 'username' is not set.";
} 
header("Localhost:destroycookie.php");
?-->
<?php
if (isset($_COOKIE['username'])) {
    echo "Username: " . $_COOKIE['username'] . "<br><br>";

    // Button to delete the cookie
    echo '<form action="destroycookie.php" method="post">
            <button type="submit">Delete Cookie</button>
          </form>';
} else {
    echo "Cookie 'username' is not set.";
}
?>
