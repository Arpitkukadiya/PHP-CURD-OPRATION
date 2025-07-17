
<?php
include 'conn.php';


if(isset($_POST['submit'])){
    $name=$_POST['name'];
    $email=$_POST['email'];

    
$sql="insert into curd (name,email) values ('$name','$email')";
$result=mysqli_query($con,$sql);

if($result){
    header("location:display.php");
}else{
    die(mysqli_error($con));
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
    <form  method="POST">
        <lable>name</lable>
        <input type="name" name="name" >
        
        <lable>email</lable>
        <input type="email" name="email">
   <button type="submit" name="submit">submit</button>
</form>
</body>
</html>