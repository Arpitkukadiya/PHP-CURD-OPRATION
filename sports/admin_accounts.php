<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $delete_admins = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_admins->execute([$delete_id]);
    header('location:admin_accounts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Accounts</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <style>
    /* General Reset and Body Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Roboto', sans-serif;
        display: flex;
        color: #333;
        background-color: #f4f7f8;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 250px;
        background-color: #1a1a1a; /* Dark sidebar theme */
        color: #fff;
        height: 100vh;
        position: fixed;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding: 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.4);
        top: 70px;
    }

    .profile-section {
        text-align: center;
        margin-bottom: 20px;
    }

    .profile-section img {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        margin-bottom: 8px;
        border: 2px solid #fff;
    }

    .profile-section p {
        font-size: 16px;
        font-weight: bold;
        margin: 5px 0;
    }

    .logout-btn {
        background-color: #ff6666;
        color: #fff;
        padding: 7px 10px;
        text-decoration: none;
        border-radius: 5px;
        display: inline-block;
        margin-top: 10px;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .logout-btn:hover {
        background-color: #cc3333;
    }

    .navbar {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .navbar a {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: #fff;
        font-size: 14px;
        padding: 8px 10px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .navbar a i {
        margin-right: 8px;
        font-size: 16px;
    }

    .navbar a:hover {
        background-color: #007bff; /* Lighter blue on hover */
    }

    /* Main Content Styles */
    .main-content {
        margin-left: 270px; /* Sidebar width */
        padding: 40px;
        padding-top: 110px;
    }

    .user-accounts .title {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #1a73e8;
        text-align: center;
        text-transform: uppercase;
    }

    .box-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .box {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .box img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: 10px;
        border: 2px solid #ddd;
    }

    .box p {
        margin: 5px 0;
        font-size: 14px;
        color: #555;
    }

    .box span {
        font-weight: bold;
        color: #333;
    }

    .delete-btn {
        display: inline-block;
        background-color: #ff6666;
        color: #fff;
        padding: 8px 15px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        margin-top: 10px;
        transition: background-color 0.3s;
    }

    .delete-btn:hover {
        background-color: #cc3333;
    }
</style>

</head>
<body>


<?php include 'admin_header.php'; ?>

<div class="main-content">
    <section class="user-accounts">
        <h1 class="title">Admin Accounts</h1>
        <div class="box-container">
            <?php
            $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = 'admin'");
            $select_users->execute();
            while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="box" style="<?php if ($fetch_users['id'] == $admin_id) {
                    echo 'display:none';
                }; ?>">
                    <img src="uploaded_img/<?= $fetch_users['image']; ?>" alt="Profile Image">
                    <p>Admin ID: <span><?= $fetch_users['id']; ?></span></p>
                    <p>Admin Name: <span><?= $fetch_users['name']; ?></span></p>
                    <p>Email: <span><?= $fetch_users['email']; ?></span></p>
                    <p>User Type: <span><?= $fetch_users['user_type']; ?></span></p>
                    <a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('Delete this user?');" class="delete-btn">Delete</a>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

</body>
</html>
