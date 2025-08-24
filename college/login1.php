<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* CSS styles */
        /* ... your existing styles ... */
    </style>
</head>
<body>
    <div class="container">
        <h2>Student Login</h2>
        <form id="login-form" method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <a href="register.php"><button type="button">Registration</button></a>
        </form>
    </div>

    <!-- PHP Code for Login Handling -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "student_db";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve form data
        $user = $_POST['username'];
        $pass = $_POST['password'];

        // Check if user exists
        $sql = "SELECT * FROM students WHERE username='$user'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($pass, $row['password'])) {
                // Redirect to home.php with query parameter (username)
                header("Location: home.php?username=" . urlencode($user));
                exit();  // Don't forget to call exit() after header redirection
            } else {
                echo "<script>alert('Invalid password.');</script>";
            }
        } else {
            echo "<script>alert('User not found.');</script>";
        }

        // Close connection
        $conn->close();
    }
    ?>
</body>
</html>
