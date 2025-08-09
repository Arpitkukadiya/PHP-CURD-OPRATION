<?php

@include 'config.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit;
}

// Fetch feedback data using PDO
$query = "SELECT * FROM order_feedback";
$stmt = $conn->prepare($query); // Prepare the query
$stmt->execute(); // Execute the query

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Feedback - Admin Panel</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your custom CSS -->
   
    <style>
    header {
            background-color: #003366; /* Dark Blue */
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #003366; /* Dark Blue */
            color: #fff;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding-top: 60px;
            overflow-y: auto;
        }

        .sidebar ul {
            list-style-type: none;
            padding-left: 0;
        }

        .sidebar li {
            padding: 12px;
            border-bottom: 1px solid #004080; /* Slightly Lighter Blue */
        }

        .sidebar li a {
            color: #e6f0ff; /* Light Blue */
            text-decoration: none;
            display: block;
        }

        .sidebar li a:hover {
            color: #cce0ff; /* Lighter Blue */
        }

        /* Content Styles */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .widget {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            display: flex;
            align-items: center;
        }

        .widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .widget-icon {
            font-size: 2.5rem;
            margin-right: 15px;
            color: #007bff;
        }

        .widget h3 {
            color: #004080;
            margin-bottom: 5px;
        }

        .widget p {
            font-size: 18px;
            color: #333;
        }

        /* Add more padding for content */
        .container {
            max-width: 1200px;
        }

    </style>
</head>
<body>
<?php include 'admin_navbar.php' ?>

<!-- Content Section -->
<div class="content">
    <h1 class="text-center mb-4">User Feedback</h1>

    <?php
    if ($stmt->rowCount() > 0) {
        // Loop through each feedback and display it in a card
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="card mb-4">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">Order ID: ' . $row['order_id'] . '</h5>';
            echo '<h6 class="card-subtitle mb-2 text-muted">User ID: ' . $row['user_id'] . '</h6>';
            echo '<p class="card-text"><strong>Feedback:</strong> ' . $row['feedback'] . '</p>';
            echo '<p class="card-text"><strong>Rating:</strong> ' . $row['rating'] . ' stars</p>';
            echo '<p class="card-text"><strong>Created At:</strong> ' . $row['created_at'] . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No feedback available.</p>';
    }
    ?>
</div>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
