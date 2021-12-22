<?php
require_once 'include/php/conn/dbconnect.class.php';
//Replaces '' with NULL
function replaceEmptyNull($val){
    if (empty($val)){
        return null;
    } else {
        return $val;
    }
}

//Checks for page access for user
function hasAccess($page){
/*	SA 20121105 Commented out the following block
	for ease of work - Block Start
    $host = $GLOBALS['host'];
    $dbname = $GLOBALS['dbname'];
    
    $ACCESS_IGNORE_LIST = ARRAY('home', 'login', 'logout', null);
    $return = true;
    if (in_array($page, $ACCESS_IGNORE_LIST)) {
        $return = true;
    } else {
        try {
            
            //$dbconn = new PDO("pgsql:dbname=$dbname;host=$host", $_SESSION['user'], $_SESSION['pass'], array(PDO::ATTR_PERSISTENT => true));
            //$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbconn = dbConn::getConnection();

            $query = $dbconn->prepare("SELECT DISTINCT COUNT(*) AS COUNT
            FROM 	app_object, app_object_user_permission  aoup
            WHERE  	aoup.object_key = app_object.object_key
                        AND aoup.user_key = (SELECT user_key FROM app_user WHERE username = (SELECT username FROM v_current_user))
                        AND object_type in ('W')
                        AND physical_name = '".$page."'");

            $query->execute();
            $result = $query->fetchColumn();

            if ($result == '0' ){
                $return = false;
            }
        } catch (PDOException $e) {
            $return = false;
        }
    }
    //log_error("hasAccess: ".$page." = ".$return);
    return $return;
 
 	Block End*/
 
	return true;
}

//Returns visitors IP Address
function VisitorIP(){ 
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $TheIp=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $TheIp=$_SERVER['REMOTE_ADDR'];
    }
    return trim($TheIp);
}

//Write to error log
function log_error($message){
    $logFile = 'APPLICATION.LOG';
    $fh = fopen($logFile, 'a+');
    $dateTime = date('d/m/Y h:i:s A');
    $user = $_SESSION['user'];
    fwrite($fh, '['.$dateTime.'][USER: '.$user.'/'.VisitorIP().']: '."$message\r\n");
    fclose($fh);
}
?>
