<?php
session_start();

require_once 'include/php/lib/utility.php';

if ($_SESSION['login'] == "1"){
    define('HOME_PAGE', 'home');
    define('P404', 'pageNotFound');
    define('AcDn', 'accessDenied');
} 
else {
    define('HOME_PAGE', 'login');
    define('P404', 'login');
}

$include = '';

if(isset($_GET['page']) && $_GET['page'] != '') {
	$page = $_GET['page'];
	if(file_exists('page/'.$page.'.php') && $_SESSION['login'] == "1") {
		if (hasAccess($page)){
			$include = 'page/'.$page.'.php';
		}
		else {
			$include = 'page/'.AcDn.'.php';
			log_error('Access denied: '.$page);
        }
	} 
	else {
		$include = 'page/'.P404.'.php';
		log_error('Access denied: '.$page);
	}
} 
else {
	$include = 'page/'.HOME_PAGE.'.php';
}
?>

<!DOCTYPE html>
<html>
<head>
<title>MenuApp</title>

<meta http-equiv="Content-Language" content="en-us">
<meta name="author" content="IMpulse (BD) Ltd" >
<meta name="developer" content="Fahad Hasan" >
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">

<link rel="icon" type="image/png" href="favicon.ico">
<link rel="stylesheet" type="text/css" href="include/css/Style.css" >
<link rel="stylesheet" type="text/css" href="include/css/jquery-ui-1.8.4.custom.css" >

<script type="text/javascript" src="include/js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="include/js/jquery.ui.core.js"></script>
<script type="text/javascript" src="include/js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="include/js/jquery.ui.position.js"></script>
<script type="text/javascript" src="include/js/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="include/js/jquery.ui.autocomplete.js"></script>

</head>
<body>
<div class="loading">Loading...</div>
<div id="container" >
	<div id="header">
		<h1>Menu Application Content Management</h1>
		<h2>Enhancing Business Performance...</h2>
	</div>
	<div id="navigation">
		<ul>  
            <li style="float:right"><a href="process.php?do=logout"><b><?php echo $_SESSION['user']; ?></b> : Logout</a></li>
    		<?php 
    		if($_SESSION['login'] == "1") { 
    		?>
    		<li><a href="index.php">Home</a></li>
    		<li><a href="index.php?page=mcat_main">Category Management</a></li>
    		<li><a href="index.php?page=mat_main">Material Management</a></li>
<!--     	<li><a href="index.php?page=mcat_new">Create Category</a></li> 
    		<li><a href="index.php?page=mat_new">Create Material</a></li> -->
    		<?php } ?>
		</ul>
	</div>        
        <?php
        include 'module/flash_msg.php';
        ?>
	<div id="content">
	<?php
		include $include;
	?>
	</div>
	<div id="footer">
		<div class="fcenter">
			<div class="fleft"><p>Copyright 2011</p></div>
                        <div class="fright"><p>Developed by: <a target="_blank" href="http://www.impulsebdltd.com"><b>IMpulse (BD) Ltd.</b></a></p></div>
			<div class="fcenter"><p>&nbsp;</p></div>
		</div>
	</div>

</div>
</body>
</html>