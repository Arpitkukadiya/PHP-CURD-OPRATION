

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>hello</h1>  

<form method="post" action="">
           <input type="text" id="name" name="name">
           <input type="text" id="roll" name="roll">
           <input type="submit" id="submit" name="submit">
             



    </form>
</body>
</html>

<?php

include 'conn.php';


if(isset($_POST['submit']))
{
    $name=$_POST['name'];
  $roll = $_POST['roll'];

   $sql = "INSERT INTO s (name, roll) VALUES ('$name', '$roll')";
    $result=mysqli_query($conn,$sql);
    if($result)
    {
        echo 'data insert';
    }
    else
    {
    echo 'data not insert';    
    }

}

?>