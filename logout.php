<?php
session_start();
// remove all session variables
session_unset(); 
$_SESSION['login_user'] = null;
// destroy the session 
session_destroy();
header("Location: index.php");
exit();
?>