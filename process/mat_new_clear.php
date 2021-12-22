<?php
    unset($_SESSION['mat_code']);
	unset($_SESSION['mat_desc']);
	unset($_SESSION['mat_long_desc']);
	unset($_SESSION['p_mcat']);
	unset($_SESSION['discontinued_flag']);

$_SESSION['flash_type'] = "success";
$_SESSION['flash_msg'] = "Form has been cleared, all draft data has been removed!";
header('location:index.php?page=mat_new');
?>