<?php
//include 'include/php/conn/dbconnect.class.php';

?>
<script type="text/javascript" src="include/js/erp_crm.js"></script>
<form id="form_mat_new" method="POST" action="process.php?do=mat_new">
<table class="formTable">
	<tr>
        <td colspan="15" style="border-bottom: 1px solid #f1f1f1; padding-bottom: 10px">
            Please fill in the form below to Add a new Material. <font color="red">* </font> marked fields are mandatory.
            <div style="float:right">
                <input id="mat_new_button" class="button" type="button" value="Save" />
                <input class="button" type="button" value="Clear" onClick="javascript:location.href = 'process.php?do=mat_new_clear';" />
                <input class="button" type="button" value="Cancel" onClick="javascript:location.href = 'index.php?page=mat_main';" />
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="9">&nbsp;</td>
    </tr>
    <tr>
    	<td>
    		Material Code<font color="red"> *</font>
    	</td>
    	<td>
    		<input class="text" type="text" name="mat_code" id="mat_code" value="<?php echo $_SESSION['mat_code']; ?>" />
    	</td>
    </tr>
    <tr>
    	<td>
    		Material Description<font color="red"> *</font>
    	</td>
    	<td>
    		<textarea class="text" name="mat_desc" id="mat_desc" rows="2" cols="50"><?php echo $_SESSION['mat_desc']; ?></textarea>
    	</td>
    </tr>
	<tr></tr>
    <tr>
    	<td>
    		Material Long Description
    	</td>
    	<td>
    		<textarea class="text" name="mat_long_desc" id="mat_long_desc" rows="4" cols="50"><?php echo $_SESSION['mat_long_desc']; ?></textarea>
    	</td>
    </tr>
    <tr>
    	<td>
    		Material Category<font color="red"> *</font>
    	</td>
    	<td>
    		<select name="p_mcat" id="p_mcat" class="select">
    			
    			<?php
				$dbconn = dbConn::getConnection();
				$dbconn->beginTransaction();
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
						echo "<option value='". $obj->mcat_id ."'>". $obj->mcat_code ."</option>";
					}
				}
			?>
    		</select>
    	</td>
    </tr>
    <tr>
    	<td>
    		Discontinued Flag
    	</td>
    	<td>
    		<input type="checkbox" name="discontinued_flag" id="discontinued_flag" value="Y">
    	</td>
    </tr>

</table>
</form>


<script type="text/javascript" charset="utf-8">

$('#mat_new_button').click(function(){ 
   $('form#form_mat_new').submit(); 
});

</script>

