<?php
session_start();

// Get email from session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : null;

if (isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] += 1;
} else {
    $_SESSION['visit_count'] = 1;
}

$visitCount = $_SESSION['visit_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Welcome Page</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        h1 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .welcome {
            color: #007bff;
            font-size: 1.25rem;
            margin-bottom: 10px;
        }

        .visit-count {
            font-size: 1rem;
            color: #555;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 25px;
            font-size: 1rem;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #0056b3;
        }

        .footer {
            margin-top: 30px;
            font-size: 0.85rem;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Welcome to My Website</h1>

    <p class="welcome">
        <?php
        if ($email) {
            echo "Welcome, <strong>$email</strong>!";

           echo" <a href=\"logout.php\" class=\"btn\">Logout</a>";

        } else {
            echo "Hello, <strong>Guest</strong>!";
           echo" <a href=\"login.php\" class=\"btn\">Login</a>";

        }
        ?>
    </p>

    <p class="visit-count">
        count of visiting site<?php 
         echo $visitCount;
        ?>
    </p>


    <div class="footer">
        &copy; <?= date('Y') ?> Made by Arpit Kukadiya
    </div>
</div>

</body>
</html>
