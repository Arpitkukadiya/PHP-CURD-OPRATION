<?php

include 'conn.php';

$sql="select*from s";
$result=mysqli_query($conn,$sql);
if($result)
{
    while ($row = mysqli_fetch_assoc($result))
    {
        $id=$row['roll'];
        $name=$row['name'];
   
    echo'<tr>
        <td>'.$id.' </td>
        <td>'.$name.' </td>

      
        </tr>
     ';

}
 }

?>