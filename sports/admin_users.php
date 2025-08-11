<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:login.php');
};

if (isset($_GET['delete'])) {

    $delete_id = $_GET['delete'];
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->execute([$delete_id]);
    header('location:admin_users.php');

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>

    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="css/admin_style.css">

    <style>
        body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f3e4;
    margin: 0;
    padding: 0;
    color: #333;
}

.header {
    background: #c75c5c;
    color: white;
    padding: 20px 40px;
    font-size: 2rem;
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    margin-left: 20px;
}

/* Sidebar */
.sidebar {
    background-color: #9b2a2f;
    position: fixed;
    padding: 30px 20px;
    height: 100vh;
    width: 250px;
    color: #fff;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.4);
    top: 70px;
    bottom: 0;
    overflow-y: auto;
    z-index: 100;
}

.sidebar a {
    color: #dcdcdc;
    font-size: 1rem;
    padding: 12px 20px;
    text-decoration: none;
    display: block;
    border-radius: 6px;
    margin-bottom: 10px;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background: #ffcc00;
    color: #fff;
}

/* Content */
.content {
    margin-left: 270px; /* Adjust content margin to accommodate sidebar */
    padding: 40px;
    padding-top: 110px;
}

.title {
    font-size: 2.5rem;
    color: #e74c3c;
    text-align: center;
    margin-bottom: 20px;
}

/* User Accounts Section */
.user-accounts {
    margin-top: 90px;
    padding-left:400px;
}

/* Box container - Display cards in a row */
.box-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    margin-top: 40px;
}

/* Card style for user information */
.box {
    background: #fff;
    border-radius: 10px;
    padding: 60px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 30px;
    width: 100%; /* Adjust width to fit in 4 items per row */
    text-align: center;
    overflow: hidden;
    position: relative;
}

/* Hover effect for user cards */
.box:hover {
    transform: translateY(-10px);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
}

/* User image styling */
.box img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
}

/* Text styling for user information */
.box p {
    font-size: 1.1rem;
    margin: 10px 0;
}

/* Delete button styling */
.box .delete-btn {
    background: #e74c3c;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    transition: background 0.3s ease;
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
}

/* Hover effect for delete button */
.box .delete-btn:hover {
    background: #c0392b;
}

/* User type color */
.box .user-type {
    font-weight: bold;
    color: #e74c3c;
}

/* Responsive Design */
@media (max-width: 992px) {
    .box-container {
        flex-direction: column;
        align-items: center;
    }

    .box {
        width: 80%; /* Adjust the width for smaller screens */
    }
}

@media (max-width: 768px) {
    .header {
        font-size: 1.5rem;
        padding: 15px 20px;
    }

    .sidebar {
        width: 200px;
        padding: 15px;
    }

    .content {
        margin-left: 220px; /* Adjust content margin to stay aligned with the sidebar */
        padding: 20px;
    }

    .box {
        width: 100%; /* Ensure full width on mobile devices */
    }
}

    </style>
</head>

<body>

    <?php include 'admin_header.php'; ?>

    <section class="user-accounts">

        <h1 class="title">User Accounts</h1>

        <div class="box-container">

            <?php
            $select_users = $conn->prepare("SELECT * FROM `users` WHERE user_type = 'user'");
            $select_users->execute();
            while ($fetch_users = $select_users->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="box" style="<?php if ($fetch_users['id'] == $admin_id) {
                                            echo 'display:none';
                                        }; ?>">
                    <img src="uploaded_img/<?= $fetch_users['image']; ?>" alt="">
                    <p>user id: <span><?= $fetch_users['id']; ?></span></p>
                    <p>username: <span><?= $fetch_users['name']; ?></span></p>
                    <p>email: <span><?= $fetch_users['email']; ?></span></p>
                    <p>user type: <span class="user-type" style="color:<?php if ($fetch_users['user_type'] == 'admin') {
                                                                            echo 'orange';
                                                                        }; ?>"><?= $fetch_users['user_type']; ?></span></p>
                    <a href="admin_users.php?delete=<?= $fetch_users['id']; ?>" onclick="return confirm('delete this user?');" class="delete-btn">delete</a>
                </div>
            <?php
            }
            ?>
        </div>

    </section>

    <script src="js/script.js"></script>

</body>

</html>
