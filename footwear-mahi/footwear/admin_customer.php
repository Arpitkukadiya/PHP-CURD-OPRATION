<?php

@include 'config.php';

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('location:login.php');
    exit; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management - EYEWEAR</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="admin_styles.css">
    <style>
        .customer-card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .customer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #f8f9fa;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .customer-details {
            font-size: 1rem;
            color: #555;
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 0.5rem 1rem;
        }

        .btn-custom:hover {
            background-color: #0056b3;
        }

        .text-highlight {
            color: #007bff;
        }
    </style>
</head>
<body>

<?php include 'admin_navbar.php' ?>

<!-- Content -->
<div class="content w-75 p-3">
    <div class="container-fluid">
        <section class="user-accounts">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-center">Manages Customer</h2>
            </div>
            
            <div class="row">
                <?php
                    $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = 'user'");
                    $select_users->execute();
                    while($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)){
                ?>
                    <div class="col-md-4">
                        <div class="card customer-card">
                            <div class="card-header">
                                Customer ID: <?= $fetch_users['id']; ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $fetch_users['name']; ?></h5>
                                <p class="customer-details"><strong>Email:</strong> <?= $fetch_users['email']; ?></p>
                                <p class="customer-details"><strong>Contact:</strong> <?= $fetch_users['contact']; ?></p>
                               
                            </div>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </section>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
