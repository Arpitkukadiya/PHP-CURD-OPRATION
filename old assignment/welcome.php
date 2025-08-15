<?php
// welcome.php - Welcome Page
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php");
}
echo "Welcome, " . $_SESSION['user'];
?>
<a href='logout.php'>Logout</a>