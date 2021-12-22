<?php
unset($_SESSION['mcat_code']);
unset($_SESSION['mcat_desc']);
unset($_SESSION['p_mcat']);

$_SESSION['flash_type'] = "success";
$_SESSION['flash_msg'] = "Form has been cleared, all draft data has been removed!";
header('location:index.php?page=mcat_new');
?>
