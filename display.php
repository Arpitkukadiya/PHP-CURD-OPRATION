<?php
include 'conn.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table border=3> 
        <tr>
        <th>id</th>
        <th>name</th>
        <th>email</th>
        <th>opration</th>
        </tr>

<?php

$sql="select * from curd";
$result=mysqli_query($con,$sql);
if($result){
    while($row=mysqli_fetch_assoc($result)){
   $id=$row['id'];
   $name=$row['name'];
   $email=$row['email'];
echo'<tr>
        <td>'.$id.' </td>
        <td>'.$name.' </td>
        <td>'.$email.'</td>
    <td>

        <button><a href="update.php?updateid='.$id.'">update</a></button>
        <button><a href="delete.php?deleteid='.$id.'">delete</a></button>
    </td>
        </tr>
     ';
    } 
}     

?>     

</table>
</body>
</html>