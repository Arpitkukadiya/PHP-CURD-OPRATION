<?php

include 'conn.php';


if(isset($_GET['deleteid'])){

    $id=$_GET['deleteid'];
    $sql="delete from curd where id=$id";
    $resul=mysqli_query($con,$sql);
    if($resul){
        header("location:display.php");
    }
    else{
        die(mysqli_error($con));
    }
}


?>
