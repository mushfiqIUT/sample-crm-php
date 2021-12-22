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
            Please click <b>Add New</b> to insert new Material or <b>Edit</b> to modify existing record.
            <div style="float:right">
                <a href="index.php?page=mat_new">
                    <input class="button" type="button" name="add_new" value="Add New" />
                </a>
            </div>
        </td>
    </tr>
</table>

<?php
$dbconn = dbConn::getConnection();

if (isset($_POST['search'])){

	
	$_SESSION['matCodeFilter'] = $matCodeFilter = $_POST['matCodeFilter'];
	$_SESSION['matDescFilter'] = $matDescFilter = $_POST['matDescFilter'];
	$_SESSION['mcat'] = $mcat = $_POST['mcat'];
	
	    
    if ($matCodeFilter != '' || $matDescFilter != '' || $mcat != '' )
    {
    	$sql = "SELECT	org_id, material_id, material_code,
						material_desc, mcat_id		
				FROM 	material			
				WHERE 	org_id = ".$_SESSION['org_id']." 
						AND LOWER(COALESCE(material_code, '')) LIKE LOWER('%".$matCodeFilter."%')
						AND LOWER(COALESCE(material_desc, '')) LIKE LOWER('%".$matDescFilter."%')
    			";
		if($mcat != '')
		{
			$sql = $sql." AND mcat_id = ".$mcat;
		}
		//echo $sql;  
		//exit();
		//OR LOWER(COALESCE(bp.email_2, ''))LIKE LOWER('%".$EAddress_filter."%')	
		//$dbconn = dbConn::getConnection();
		$query =  $dbconn->prepare($sql);
        $query->execute();
        $result = $query->fetchAll();
      
	    /*$arr_bp_id = array();
        
        for ($i = 0; $i < $query->rowCount(); $i++ ){
                $data = $result[$i];
              	array_push($arr_bp_id, $data['bp_id']);
        }
        $arr_bp_id_unique = array_unique($arr_bp_id);
        $total_no_customer = count($arr_bp_id_unique);
		 */
		 $total_no_material = $query->rowCount();
    } else {
        unset($_POST['search']);
    }
} else {
	unset($_SESSION['matCodeFilter']);
	unset($_SESSION['matDescFilter']);
	unset($_SESSION['mcat']);
}
?>

<form name="advanced_search" Method="POST" action="index.php?page=mat_main">
<table width="500px" name="search_filters">
    <tr>
        <td colspan="5"><div class="searchHeader"><b style="float: left;">Advance Search Filters</b><div title="Hide Advanced Search" class="toggle toggleUpButton"></div></div></td>
    </tr>
    <tr>
        <td>Material Code</td>
        <td>Material Description</td>
		<td>Material Category</td>
    </tr>
    <tr>
        <td><input class="text" type="text" name="matCodeFilter" value="<?php echo $_SESSION['matCodeFilter']; ?>" /></td>
        <td><input class="text" type="text" name="matDescFilter" value="<?php echo $_SESSION['matDescFilter']; ?>" /></td>
		<td>
			<select name= "mcat" id="mcat" class="select" style="width:130px">
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
						if ($_SESSION['mcat'] == $obj->mcat_id){
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

<table name="mat_main_header" class="formTable">
    <tr>
        <td style="padding-bottom: 10px">
            <div style="float:right">
			Total Material <input id="total_mat" class="text" type="text" readonly="readonly" disabled="disabled" name="total_material"  style="width:50px" value="<?php echo $total_no_material ?>" />
            </div>
        </td>
    </tr>
</table>

<table id="bp_list" class="formattedTable">
    <thead>
        <tr>
            <td>Material Code</td>
            <td>Material Description</td>
            <td>Material Category</td>
            <td>Options</td>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Material Code</th>
            <th>Material Description</th>
            <th>Material Category</th>
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
								   AND mcat_id = ".$data['mcat_id'];
				
				$query_pcat =  $dbconn->prepare($sql_pcat);
        		$query_pcat->execute();
        		$result_pcat = $query_pcat->fetch();
        ?>  
        <tr>
            <td><?php echo $data['material_code']; ?></td>
            <td><?php echo $data['material_desc']; ?></td>
            <td><?php echo $result_pcat['mcat_code']; ?></td>
            <td><a href="index.php?page=mat_edit&material_id=<?php echo $data['material_id']; ?>" ><img class="optionButton" src="image/edit.png" title="Edit Record" /></a></td>
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
            /*0 Mat Code*/               {"bSearchable":true, "bVisible":true},
            /*1 Mat Desc*/               {"bSearchable":true, "bVisible":true},
            /*2 Mat Cat*/             	 {"bSearchable":true, "bVisible":true},
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