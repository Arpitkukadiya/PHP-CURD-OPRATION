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

  /*  // Fetch Subjects
    $subjects_query = "SELECT * FROM subjects";
    $subjects_result = $conn->query($subjects_query);

    // Fetch Attendance (Just an example, you can add filtering by subject or student)
    $attendance_query = "SELECT * FROM attendance";
    $attendance_result = $conn->query($attendance_query);

    // Check if queries were successful
    if (!$faculty_result || !$subjects_result || !$attendance_result) {
        die("Error in query execution: " . $conn->error);
    }
*/
    // Close connection after fetching data
    $conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Classic CSS Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            background: url('college.jpeg') no-repeat center center fixed;
            background-size: cover;
            
         
        }

        /* Header with Navigation Menu */
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 24px;
        }

        /* Navigation Menu */
        nav {
            background-color: #444;
            overflow: hidden;
        }

        nav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
        }

        nav a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Main Content */
        .container {
            width: 80%;
            margin: 20px auto;
            /*background: url('college.jpeg') no-repeat center center fixed;*/
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color:blue;
        }

        h2:hover {
            color: red;
            transform: scale(1.1);
            text-s
        }
        
        .section {
            margin-bottom: 20px;
            padding: 15px;
            background-color: none;
           /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);*/
        }

        .section h3 {
            margin-top: 0;
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
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <h1>Student Management System</h1>
    </header>

    <!-- Navigation Menu -->
    <nav>
        <a href="home.php">Home</a>
        <a href="faculty.php">Faculty Details</a>
        <a href="subjects.php">Subjects</a>
        <a href="attendance.php">Attendance</a>
    </nav>

    <!-- Main Content Section -->
    <div class="container">
        <h2>Welcome to the Student Management System</h2>

        <!-- Home Section -->
        <div class="section">
            <h3>Home</h3>
            <p>Welcome to the Student Management System. Here, you can manage students, view faculty details, track attendance, and more.</p>
        </div>

        <!-- Faculty Details Section -->
        <div class="section">
            <h3>Faculty Details</h3>
            <ul>
                <?php while ($row = $faculty_result->fetch_assoc()) { ?>
                    <li><?php echo $row['name'] . " - " . $row['qualification']; ?></li>
                <?php } ?>
            </ul>
        </div>

        <!-- Subjects Section -->
        <div class="section">
            <h3>Subjects</h3>
            <ul>
                <?php while ($row = $subjects_result->fetch_assoc()) { ?>
                    <li><?php echo $row['subject_name'] . " (Code: " . $row['subject_code'] . ")"; ?></li>
                <?php } ?>
            </ul>
        </div>

        <!-- Attendance Section -->
        <div class="section">
            <h3>Attendance</h3>
            <ul>
                <?php while ($row = $attendance_result->fetch_assoc()) { ?>
                    <li><?php echo "Student ID: " . $row['student_id'] . " - " . ($row['status'] == 1 ? "Present" : "Absent"); ?></li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Student Management System | All rights reserved.</p>
    </footer>

</body>
</html> 