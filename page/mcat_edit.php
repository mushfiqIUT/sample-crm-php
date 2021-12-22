<?php
include 'include/php/conn/db_info.php';


if (isset($_GET['mcat_id'])){
    $mcat_id = $_GET['mcat_id'];
} else {
    $_SESSION['flash_type'] = "error";
    $_SESSION['flash_msg'] = "No parameter received, redirected to list.";
    header('location:index.php?page=mcat_main');
}

$dbconn = dbConn::getConnection();

$query_string = "SELECT org_id, mcat_id, mcat_code, mcat_desc, parent_mcat_id 
							  FROM material_category 
							  WHERE mcat_id = ".$mcat_id."
									AND org_id = ".$_SESSION['org_id'];
$query_mc = $dbconn->prepare($query_string);
$query_mc->execute();
$data_mc = $query_mc->fetch(PDO::FETCH_ASSOC);



//print_r($data_bp_app_cat);

?>
<script type="text/javascript" src="include/js/erp_crm.js"></script>
<form id="form_mcat_edit" method="POST" action="process.php?do=mcat_edit">
<input name="mcat_id" type="hidden" value="<?php echo $mcat_id; ?>" />

<table class="formTable">
    <tr>
        <td colspan="15" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 10px">
            Please edit the form below to update Business Partner. <font color="red">* </font> marked fields are mandatory.
            <div style="float:right">
                <input id="mcat_edit_button" class="button" type="button" value="Save"/>
                <input class="button" type="button" value="Cancel" onClick="javascript:location.href = 'index.php?page=mcat_main';" />
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="9">&nbsp;</td>
  	</tr>
    <tr>
        <td>Catagory Code<font color="red"> *</font></td>
        <td>:</td>
        <td><input class="text" type="text" name="mcat_code" value="<?php echo $data_mc['mcat_code']; ?>" /></td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <tr>
        <td>Catagory Description<font color="red"> *</font></td>
        <td>:</td>
        <td><input class="text" type="text" name="mcat_desc" value="<?php echo $data_mc['mcat_desc']; ?>" /></td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <tr>
        <td>Parent Catagory</td>
        <td>:</td>
        <td><select name="p_mcat" id="p_mcat" class="select" style="width:130px">
    			<option value=""></option>
    			<?php
				//$dbconn = dbConn::getConnection();
				//$dbconn->beginTransaction();
				$flag = 0;
				
				$query_fetch_mcat = $dbconn->prepare("SELECT mcat_id, mcat_code FROM material_category
														WHERE	org_id = :org_id");
				try {
					$query_fetch_mcat->execute(array(':org_id' => $_SESSION['org_id']));
				}
				catch(PDOException $e) {
					$flag = 1;
				    if(strstr($e->getMessage(), 'SQLSTATE[')) {
				        preg_match('/SQLSTATE\[(\w+)\] \[(\w+)\] (.*)/', $e->getMessage(), $matches);
				        $code = ($matches[1] == 'HT000' ? $matches[2] : $matches[1]);
				        $message = $matches[3];
				        $_SESSION['flash_msg'] = $message;
					}
				}
				
				if (!$flag) {
					while ($obj = $query_fetch_mcat->fetchObject()) {
						if ($data_mc['parent_mcat_id'] == $obj->mcat_id){
							echo "<option selected ='true' value='". $obj->mcat_id ."'>". $obj->mcat_code ."</option>";
						} else {
							echo "<option value='". $obj->mcat_id ."'>". $obj->mcat_code ."</option>";
						}
					}
				}
			?>
    		</select>
    		</td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
        <td> </td>
    </tr>
    <tr>
        <td colspan="9">&nbsp;</td>
    </tr>
</table>
</form>

<script type="text/javascript" charset="utf-8">

    $('#mcat_edit_button').click(function(){
       $('form#form_mcat_edit').submit(); 
    });

</script>
