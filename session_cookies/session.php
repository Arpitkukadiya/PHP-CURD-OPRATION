<?php

session_start();


$name='arpit patel';
$_SESSION['name']=$name;
session_destroy();
echo "<a href='getsession.php'> session get</a>";
?>