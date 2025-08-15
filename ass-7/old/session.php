<!--<html><!-?php
session_start();
$_SESSION['erno'] = '24MCA30044'; 
echo "Session variable 'erno' is set.";

header("Location: retrieve.php"); 
?--->

<?php
session_start();
$_SESSION['user_preferences'] = [
    'theme' => 'dark',
    'language' => 'English',
    'font_size' => '14px'
];
echo "User preferences have been stored in session.";
?>
