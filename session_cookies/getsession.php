<?php
session_start();
if(isset($_SESSION['name']))
{

    $name1=$_SESSION['name'];

echo "'$name1'";
}


?>