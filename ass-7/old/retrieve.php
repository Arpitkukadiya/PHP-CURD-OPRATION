<!--?php
session_start();

if (isset($_SESSION['erno'])) {
    echo "Enrollment Number: " . $_SESSION['erno'] . "<br>";
    echo "Enrollment No....<br><br>";
    
  
    echo '<form action="destroy.php" method="post">
            <button type="submit">Destroy Session</button>
          </form>';
} else 
{
  
    header("Location: destroy.php");
    exit(); 
}
?-->

<?php
session_start();
if (isset($_SESSION['user_preferences'])) {
    echo "<pre>";
    print_r($_SESSION['user_preferences']);
    echo "</pre>";
} else {
    echo "No user preferences found.";
}
?>
