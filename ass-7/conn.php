<?php

$host = 'localhost';
$user = 'root'; // Change as per your database user
$pass = ''; // Change as per your database password
$dbname = 'vktest'; // Change as per your database name
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


