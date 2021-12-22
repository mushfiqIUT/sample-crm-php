<?php
$mcat_id = $_POST['mcat_id'];
$mcat_code = $_POST['mcat_code'];
$mcat_desc = $_POST['mcat_desc'];
$p_mcat = ($_POST['p_mcat']);

if ($p_mcat == ''){
    $p_mcat = null;
} else {
	$p_mcat = intval($p_mcat);
}

//VALIDATIONS

if ($mcat_code == ''){
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Catagory Code can not be empty.";
    header('location:index.php?page=mcat_edit&mcat_id='.$mcat_id);
    exit();
}

if ($mcat_desc == ''){
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Catagory Description can not be empty.";
    header('location:index.php?page=mcat_edit&mcat_id='.$mcat_id);
    exit();
}

//end of validation

$flag = 0;
$dbconn->beginTransaction();

//PREPARING ALL STATEMENTS
//Table: material_category

$query_mcat = $dbconn->prepare("UPDATE material_category
								SET parent_mcat_id = :parent_mcat_id, mcat_code = :mcat_code, mcat_desc = :mcat_desc
								WHERE mcat_id = :mcat_id
								");

$message = '';

//EXECUTING QUERIES
try {
		$query_mcat->execute(array(  
      	':parent_mcat_id' => $p_mcat,
      	':mcat_code' => $mcat_code,
      	':mcat_desc' => $mcat_desc,
      	':mcat_id' => $mcat_id
    ));
	
} catch (PDOException $e){
    $flag = 1;
    if(strstr($e->getMessage(), 'SQLSTATE[')) {
        preg_match('/SQLSTATE\[(\w+)\] \[(\w+)\] (.*)/', $e->getMessage(), $matches);
        $code = ($matches[1] == 'HT000' ? $matches[2] : $matches[1]);
        $message = $matches[3];
        $_SESSION['flash_msg'] = $message;
    }
}

$_err = $dbconn->errorInfo();
if ($_err[0] != '0000' || $_err[1] != '' || $_err[2] != ''){
    $flag = 1;
}

if ($flag == 0){
    $dbconn->commit();

    $_SESSION['flash_type'] = "success";
    $_SESSION['flash_msg'] = "Material Category details has been updated.";
    header('location:index.php?page=mcat_edit&mcat_id='.$mcat_id);
} else {
    $dbconn->rollBack();
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = $_err[2];
  	 header('location:index.php?page=mcat_edit&mcat_id='.$mcat_id);
}

?>