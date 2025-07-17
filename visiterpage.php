<?php

include 'conn.php';

session_start();

$email=isset($_SESSION['email'])? $_SESSION['email']:null;

if(isset($_SESSION['visit']))
{
    $_SESSION['visit']+=1;
}
else
{
    $_SESSION['visit']=1;
}

$visit=$_SESSION['visit'];
?>










<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>welcom</h1>
    <?php 
      if($email)
      {
        echo "<h2>hello .'$email'. </h2>";
        echo"count of visit site '.$visit.'";
        echo" <a href=\"display.php\" class=\"btn\">display</a>";

    }
      else
      {
       echo"hello user" ;
       echo"count of visit site '.$visit.'";
       echo" <a href=\"login.php\" class=\"btn\">Login</a>";

       }
    
    ?>
</body>
</html>