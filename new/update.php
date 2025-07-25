

<?php

include 'conn.php';

if(isset($_POST['submit']))
{

    $id=$_POST['id'];
    $name=$_POST['name'];

    $sql="update s set name='$name' where roll='$id' ";
    
    $result=mysqli_query($conn,$sql);
     
    if($result)
    {

        echo'data updated';
    }
    else
    {
        echo 'data not updated';
    }


}



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="POST">
    id.      
    <input type="text" name="id" id="id">
       name.   <input type="text" name="name" id="name">
          
          <input type="submit" name="submit" id="submit">
          

        <form>
</body>
</html>