<?php
$_SESSION['user'] = $user = $_POST['user'];
$_SESSION['pass'] = $pass = $_POST['pass'];

//$conn = new dbConn();
$dbconn = dbConn::getConnection();

if ($dbconn) {
    session_regenerate_id();
    $_SESSION['flash_type'] = "success";
    $_SESSION['flash_msg'] = "Welcome user, <b>" . $user . "</b>!";
    $_SESSION['login'] = "1";
    
    log_error('User login: '.$user);
    header('location:index.php?page=home');
} else {
    unset($_SESSION['login']);
    unset($_SESSION['user']);
    unset($_SESSION['pass']);
    
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Something went wrong, Please try again!";
    header('location:index.php?page=login');
}
?>