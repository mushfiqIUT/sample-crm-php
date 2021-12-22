<?php
log_error('USER LOGGED OUT');
session_unset();
session_destroy();
session_start();
$_SESSION['flash_type'] = "alert";
$_SESSION['flash_msg'] = "You have logged out.";
$dbconn = null;
header('location:index.php?page=login');
?>