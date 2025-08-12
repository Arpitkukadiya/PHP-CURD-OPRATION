<?php

$db_name = "mysql:host=localhost;dbname=owear;charset=utf8"; // Adding charset=utf8 for proper encoding
$username = "root";
$password = "";

try {
    // Create a new PDO instance and set error mode to exception
    $conn = new PDO($db_name, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Enable exceptions for errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Set default fetch mode
    ]);

    // Optional: If you want to verify the connection was successful
    // echo "Connected successfully"; 
} catch (PDOException $e) {
    // Handle connection errors
    die("Connection failed: " . $e->getMessage());
}

?>
