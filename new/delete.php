<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Data</title>
</head>
<body>
    <form method="POST">
        <input type="text" name="roll" id="roll" placeholder="Enter Roll to Delete" required>
        <input type="submit" name="submit" id="submit" value="Delete">
    </form>
</body>
</html>

<?php
include 'conn.php';

if (isset($_POST['submit'])) {
    $id = $_POST['roll']; // ✅ Corrected this line

    $sql = "DELETE FROM s WHERE roll = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo 'Data deleted successfully.';
    } else {
        echo 'Data not deleted. Error: ' . mysqli_error($conn);
    }
}
?>
