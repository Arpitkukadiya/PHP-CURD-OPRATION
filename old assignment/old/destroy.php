<!--?php
session_start();
session_unset(); 
session_destroy(); 
echo "Session destroyed successfully.";
?-->

<?php
session_start();
$inactive = 1800; // 30 minutes in seconds

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_unset();
    session_destroy();
    echo "Session expired due to inactivity.";
} else {
    $_SESSION['last_activity'] = time(); // Update last activity time
    echo "Session is active.";
}
?>
