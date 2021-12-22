<style type="text/css" title="currentStyle">
    @import "include/css/demo_table_jui.css";
    @import "include/css/jquery-ui-1.8.4.custom.css";
    @import "include/css/TableTools_JUI.css";
</style>
<script type="text/javascript" src="include/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="include/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="include/js/TableTools.js"></script>
<script type="text/javascript" src="include/js/erp_crm.js"></script> 
<script type="text/javascript" src="include/js/ZeroClipboard.js"></script>

<script type="text/javascript" charset="utf-8">
function initDocumentReady(){
    return true;
}
</script>

<table name="bp_main_header" class="formTable">
    <tr>
        <td style="padding-bottom: 10px">
            Please click <b>Add New</b> to insert new Material Category or <b>Edit</b> to modify existing record.
            <div style="float:right">
                <a href="index.php?page=mcat_new">
                    <input class="button" type="button" name="add_new" value="Add New" />
                </a>
            </div>
        </td>
    </tr>
</table>

<?php
$dbconn = dbConn::getConnection();

if (isset($_POST['search'])){

	
	$_SESSION['mcatCodeFilter'] = $mcatCodeFilter = $_POST['mcatCodeFilter'];
	$_SESSION['mcatDescFilter'] = $mcatDescFilter = $_POST['mcatDescFilter'];
	$_SESSION['p_mcat'] = $p_mcat = $_POST['p_mcat'];
	
	    
    if ($mcatCodeFilter != '' || $mcatDescFilter != '' || $p_mcat != '' )
    {
    	$sql = "SELECT	org_id, mcat_id, parent_mcat_id,
						mcat_code, mcat_desc		
				FROM 	material_category			
				WHERE 	org_id = ".$_SESSION['org_id']." 
						AND LOWER(COALESCE(mcat_code, '')) LIKE LOWER('%".$mcatCodeFilter."%')
						AND LOWER(COALESCE(mcat_desc, '')) LIKE LOWER('%".$mcatDescFilter."%')
    			";
		if($p_mcat != '')
		{
			$sql = $sql." AND parent_mcat_id = ".$p_mcat;
		}
		//echo $sql;  
		//exit();
		//OR LOWER(COALESCE(bp.email_2, ''))LIKE LOWER('%".$EAddress_filter."%')	
		//$dbconn = dbConn::getConnection();
		$query =  $dbconn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
      
	   /* $arr_bp_id = array();
        
        for ($i = 0; $i < $query->rowCount(); $i++ ){
                $data = $result[$i];
              	array_push($arr_bp_id, $data['bp_id']);
        }
        $arr_bp_id_unique = array_unique($arr_bp_id);
	    */
        $total_no_catagory = $query->rowCount();
		
    } else {
        unset($_POST['search']);
    }
} else {
	unset($_SESSION['mcatCodeFilter']);
	unset($_SESSION['mcatDescFilter']);
	unset($_SESSION['p_mcat']);
}
?>

<form name="advanced_search" Method="POST" action="index.php?page=mcat_main">
<table width="500px" name="search_filters">
    <tr>
        <td colspan="5"><div class="searchHeader"><b style="float: left;">Advance Search Filters</b><div title="Hide Advanced Search" class="toggle toggleUpButton"></div></div></td>
    </tr>
    <tr>
        <td>Material Category Code</td>
        <td>Material Category Description</td>
		<td>Child Category of</td>
    </tr>
    <tr>
        <td><input class="text" type="text" name="mcatCodeFilter" value="<?php echo $_SESSION['mcatCodeFilter']; ?>" /></td>
        <td><input class="text" type="text" name="mcatDescFilter" value="<?php echo $_SESSION['mcatDescFilter']; ?>" /></td>
		<td>
			<select name="p_mcat" id="p_mcat" class="select" style="width:130px">
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
						if ($_SESSION['p_mcat'] == $obj->mcat_id){
							echo "<option selected ='true' value='". $obj->mcat_id ."'>". $obj->mcat_code ."</option>";
						} else {
							echo "<option value='". $obj->mcat_id ."'>". $obj->mcat_code ."</option>";
						}
					}
				}
			?>
    		</select>
		</td>
    </tr>
    <tr>
    	<td colspan="5"><input class="text" type="hidden" name="search" value="show" /></td>
    </tr>
    <tr>
    	<td><input class="button" type="submit" value="Search" style="width:130px" />
    </tr>
</table>
</form>

<table name="cat_main_header" class="formTable">
    <tr>
        <td style="padding-bottom: 10px">
            <div style="float:right">
			Total Catagory <input id="total_cat" class="text" type="text" readonly="readonly" disabled="disabled" name="total_catagory"  style="width:50px" value="<?php echo $total_no_catagory ?>" />
            </div>
        </td>
    </tr>
</table>

<table id="bp_list" class="formattedTable">
    <thead>
        <tr>
            <td>Category Code</td>
            <td>Category Description</td>
            <td>Parent Category</td>
            <td>Options</td>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Category Code</th>
            <th>Category Description</th>
            <th>Parent Category</th>
        </tr>
    </tfoot>
    <tbody>

        <?php

        if (isset($_POST['search'])){
            unset($_POST['search']);
            
            for ($i = 0; $i < $query->rowCount(); $i++ ){
                $data = $result[$i];
				$sql_pcat = "SELECT	mcat_code		
							 FROM material_category			
							 WHERE org_id =	".$_SESSION['org_id']."
								   AND mcat_id = ".$data['parent_mcat_id'];
				
				$query_pcat =  $dbconn->prepare($sql_pcat);
        		$query_pcat->execute();
        		$result_pcat = $query_pcat->fetch();
        ?>  
        <tr>
            <td><?php echo $data['mcat_code']; ?></td>
            <td><?php echo $data['mcat_desc']; ?></td>
            <td><?php echo $result_pcat['mcat_code']; ?></td>
            <td><a href="index.php?page=mcat_edit&mcat_id=<?php echo $data['mcat_id']; ?>" ><img class="optionButton" src="image/edit.png" title="Edit Record" /></a></td>
        </tr>
        <?php
            }
        }
        ?>
    </tbody>
</table>

<script type="text/javascript">
$(document).ready(function() {
    oTable = $('#bp_list').dataTable({
        "bJQueryUI": true,
        "bFilter": true,
        "bSort": false,
        "sPaginationType": "full_numbers",
        "aoColumns": [
            /*0 Cat Code*/               {"bSearchable":true, "bVisible":true},
            /*1 Cat Desc*/               {"bSearchable":true, "bVisible":true},
            /*2 Parent Cat*/             {"bSearchable":true, "bVisible":true},
            /*3 Options*/              	 {"bSearchable":false, "bVisible":true}
        ],
        "sDom": '<"fg-toolbar ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix"Tlfr>t<"fg-toolbar ui-toolbar ui-widget-header ui-corner-bl ui-corner-br ui-helper-clearfix"ip>',
        "oTableTools": {
        "sSwfPath": "include/swf/copy_cvs_xls.swf",
        "aButtons": [   
                        /*{
                            "sExtends": "xls",
                            "sButtonText": "Save",                      
                            "mColumns": [0, 1, 2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38]
                        }*/
                    ]
        }
    }).columnFilter({
	aoColumns:  [
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        {type: "text"},
                        null
                    ]
    });
    
    $("div.toggle").click(function(){
        if ($(this).hasClass('toggleUpButton')){
            $('table[name=search_filters] tr:not(:first)').fadeOut();
            $(this).removeClass('toggleUpButton').addClass('toggleDownButton');
            $(this).attr('title', 'Show Advanced Search');
        } else if ($(this).hasClass('toggleDownButton')){
            $('table[name=search_filters] tr:not(:first)').fadeIn();
            $(this).removeClass('toggleDownButton').addClass('toggleUpButton');
            $(this).attr('title', 'Hide Advanced Search');
        }
    });
   
});
</script>