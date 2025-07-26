<?php

if(isset($_COOKIE['user']))
{
    
     $name=$_COOKIE['user'];
     echo  "'$name'";
}

?>