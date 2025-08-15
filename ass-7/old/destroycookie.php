<?php
setcookie("username", "", time() - 3600, "/"); // Set expiry in the past
echo "Cookie 'username' has been deleted.";
?>
