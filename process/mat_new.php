<?php
$_SESSION['mat_code'] = $mat_code = $_POST['mat_code'];
$_SESSION['mat_desc'] = $mat_desc = $_POST['mat_desc'];
$_SESSION['mat_long_desc'] = $mat_long_desc = $_POST['mat_long_desc'];
$_SESSION['p_mcat'] = $p_mcat = $_POST['p_mcat'];
if(isset($_POST['discontinued_flag']))
{
	$_SESSION['discontinued_flag'] = $discontinued_flag = $_POST['discontinued_flag'];
}
else {
	$discontinued_flag = "N";
}

//VALIDATIONS

if ($mat_code == ''){
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Material Code can not be empty.";
    header('location:index.php?page=mat_new');
    exit();
}

if ($mat_desc == ''){
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Material Description can not be empty.";
    header('location:index.php?page=mat_new');
    exit();
}

//end of validation

$flag = 0;
$dbconn->beginTransaction();

//PREPARING ALL STATEMENTS
//Table: material_category
$query_mcat = $dbconn->prepare("INSERT INTO material(
									org_id, material_code, material_desc, material_long_desc, mcat_id,
									is_sold, is_built, discontinued_flag)
								VALUES (:org_id, :material_code, :material_desc, :material_long_desc, :mcat_id,
									:is_sold, :is_built, :discontinued_flag)");

$message = '';

//EXECUTING QUERIES
try {
			
		$query_mcat->execute(array(  
        ':org_id' => $_SESSION['org_id'],
      	':material_code' => $mat_code,
      	':material_desc' => $mat_desc,
      	':material_long_desc' => $mat_long_desc,
      	':mcat_id' => $p_mcat,
      	':is_sold' => 1,
      	':is_built' => 1,
      	':discontinued_flag' => $discontinued_flag
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
    unset($_SESSION['mat_code']);
	unset($_SESSION['mat_desc']);
	unset($_SESSION['mat_long_desc']);
	unset($_SESSION['p_mcat']);
	unset($_SESSION['discontinued_flag']);

    
    $_SESSION['flash_type'] = "success";
    $_SESSION['flash_msg'] = "New Material has been added.";
    header('location:index.php?page=mat_new');
} else {
    $dbconn->rollBack();
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = $_err[2];
    header('location:index.php?page=mat_new');
}

?>
