<?php
    // Database connection
    $servername = "localhost";
    $username = "root";  // Replace with your DB username
    $password = "";      // Replace with your DB password
    $dbname = "student_db"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch Faculty Details
    $faculty_query = "SELECT * FROM faculty";
    $faculty_result = $conn->query($faculty_query);

    // Close connection after fetching data
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Details - Student Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Classic CSS Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header with Navigation Menu */
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Navigation Menu */
        nav {
            background-color: #444;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Main Content */
        .container {
            width: 80%;
            margin: 20px auto;
            animation: fadeIn 1s ease;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            animation: slideUp 1s ease-in-out;
        }

        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: slideUp 1s ease-in-out;
        }

        .section h3 {
            margin-top: 0;
        }

        /* Table for Faculty Details */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideUp {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        /* Home Button */
        .home-btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin: 20px 0;
            text-align: center;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .home-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <h1>Faculty Details - Student Management System</h1>
    </header>

    <!-- Navigation Menu -->
    <nav>
        <a href="home.php">Home</a>
        <a href="faculty_details.php">Faculty Details</a>
        <a href="subjects.php">Subjects</a>
        <a href="attendance.php">Attendance</a>
    </nav>

    <!-- Main Content Section -->
    <div class="container">
        <h2>Faculty Information</h2>

        <!-- Faculty Details Table -->
        <div class="section">
            <h3>List of Faculty Members</h3>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Experience (Years)</th>
                    <th>Specialized Subject</th>
                </tr>
                <?php
                if ($faculty_result->num_rows > 0) {
                    while ($row = $faculty_result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['subject']}</td>
                                <td>{$row['experience']}</td>
                                <td>{$row['specialize_subject']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No faculty members found.</td></tr>";
                }
                ?>
            </table>
        </div>

        <!-- Home Button -->
        <a href="home.php"><button class="home-btn">Go to Home</button></a>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Student Management System | All rights reserved.</p>
    </footer>

</body>
</html>
