<?php
$mat_id = $_POST['mat_id'];
$mat_code = $_POST['mat_code'];
$mat_desc = $_POST['mat_desc'];
$mat_long_desc = $_POST['mat_long_desc'];
$mcat = ($_POST['p_mcat']);
if(isset($_POST['discontinued_flag']))
{
	$discontinued_flag = $_POST['discontinued_flag'];
}
else {
	$discontinued_flag = "N";
}

//VALIDATIONS

if ($mat_code == ''){
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Material Code can not be empty.";
    header('location:index.php?page=mat_edit&material_id='.$mat_id);
    exit();
}

if ($mat_desc == ''){
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "Material Description can not be empty.";
    header('location:index.php?page=mat_edit&material_id='.$mat_id);
    exit();
}

//end of validation

$flag = 0;
$dbconn->beginTransaction();

//PREPARING ALL STATEMENTS
//Table: material_category
$queryString = "UPDATE material
								SET material_code = :material_code, material_desc = :material_desc, material_long_desc = :material_long_desc,
								mcat_id = :mcat_id, discontinued_flag = :discontinued_flag
								WHERE material_id = :material_id
								";
// echo $queryString."<br />";
// echo "This is mcat_id: $mat_id<br />";
// echo "This is mcat_code: $mat_code<br />";
// echo "This is mcat_desc: $mat_desc<br />";
// echo "This is mcat_long_desc: $mat_long_desc<br />";
// echo "This is mcat: $mcat<br />";

$query_mcat = $dbconn->prepare($queryString);

$message = '';

//EXECUTING QUERIES
try {
		$query_mcat->execute(array(  
      	':material_code' => $mat_code,
      	':material_desc' => $mat_desc,
      	':material_long_desc' => $mat_long_desc,
      	':mcat_id' => $mcat,
      	':discontinued_flag' => $discontinued_flag,
      	':material_id' => $mat_id
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
    $_SESSION['flash_msg'] = "Material details has been updated.";
    header('location:index.php?page=mat_edit&material_id='.$mat_id);
} else {
    $dbconn->rollBack();
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = $_err[2];
  	header('location:index.php?page=mat_edit&material_id='.$mat_id);
}

?>