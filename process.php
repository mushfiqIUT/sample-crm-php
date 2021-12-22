<?php
session_start();
//error_reporting(0);

require_once 'include/php/lib/utility.php';

define('REDIRECT_PAGE', 'index.php?page=login');

if ($_SESSION['login'] == "1" || $_GET['do'] == 'login') {
    if (isset($_GET['do'])) {
        $do = $_GET['do'];
		
        if(file_exists('process/'.$do.'.php')) {
            $include = 'process/'.$do.'.php';
            $dbconn = dbConn::getConnection();
			//echo "process page";
            include $include;
			//echo "process page";
        }
    } 
    else {
        header('location:'.REDIRECT_PAGE);
    }
} 
else {
    header('location:'.REDIRECT_PAGE);
}
?>