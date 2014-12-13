<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';
require_once($arrIni['base'].'lib/db/db.php');

require_once $arrIni['base'].'inc/checkACL.php';
require_once $arrIni['base'].'inc/users.class.php';
require_once $arrIni['base'].'inc/filemarks.class.php';
session_start();

GetAllFiles($_GET['chartid'], $_GET['boxid'], $_GET['orderid']);

$objFilemarks = new Filemarks;

$group_permission = GetUserPermission();

 

function GetAllFiles($chartid, $boxid, $orderid) {
	
	global $group_permission;
	
	print_r($group_permission);
	
	$show_file_marker  = ($group_permission['use_file_marker']['view'] == 1 || $group_permission['use_file_marker']['change'] == 1) ? true : false;
	
	if($show_file_marker == true) {
		$marker_header = '<th width="30%">Marks</th>';
	}
	
	$antes = '<table><thead><tr><th><a href="#" link-type="order" my-data-reveal-id="'.$orderid.'">Order '.GetName($orderid).'</a> > <a href="#" link-type="box" link-order="'.$orderid.'" link-box="'.$boxid.'" my-data-reveal-id="'.$boxid.'">Box '.GetName($boxid).'</a> > Your Files in Chart '.GetName($chartid).'</th></tr></thead><tbody><tr><td><table><thead><tr><th width="20%">Filename</th>'.$marker_header.'<th width="20%">Creation</th><th width="10%">Changed</th><th width="10%">Pages</th><th width="10%">Size</th></tr></thead><tbody>';
	
	$despues = '</tbody></table></tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT row_id, filename, creadate, moddate, pages, filesize, file_mark_id FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." and parent_id = ".$chartid.' ORDER BY filename ASC;';
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	echo $antes;
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']=="") {
				//echo $antes;
				echo "<tr><td>You don't have files at this time</td></tr>";
				//echo $despues;
			} else {
				//echo $antes;
				 
				if ($row['code']!=""&&$row['name']!="") {
					$screen = $row['code']." / ".$row['name'];
				} else if ($row['code']=="") {
					$screen = $row['name'];
				} else if ($row['name']=="") {
					$screen = $row['code'];
				}
				
				
					
				echo "<tr><td><a href=\"lib/data/file.download.php?fileid=".$row['row_id']."\" target=\"_blank\">".$row['filename']."</a></td>";
				if($show_file_marker == true) {
					
					echo "<td>";
					echo dropDownButton($row['row_id'], $row['file_mark_id']);
					echo "</td>";
				}
				
				echo "<td>".$row['creadate']."</td><td>".$row['moddate']."</td>";
				echo "<td width=\"100\">".$row['pages']."</td><td width=\"100\">";
				$mbytes = number_format($row['filesize'] / 1024 / 1024,2);
				echo $mbytes." Mb";
				echo "</td></tr>";
				//echo $despues;
			}
		}
	} else {
		//echo $antes;
		echo "<tr><td>";
		echo "You don't have files at this time</td></tr>";
		//echo $despues;
	}
	echo $despues;
	ConnectionFactory::close();
}

function dropDownButton($row_id, $mark_id)
{
	
	global $group_permission;
	
	$objFilemarks = new Filemarks;
	$objUsers = new Users;
	$label = $objFilemarks->getLabelById($mark_id);
	
	if($label == '')
	{
		$label = "(No Mark)";
	}
	
	$drop_down_list = '';
	$disabled_class = "disabled ";
	
    if( $group_permission['use_file_marker']['change'] == 1 ) {
		
			$disabled_class = '';
			
			$filter = " AND global = :global";
			
			$array_bind[':global'] = '1'; //fk_empresa = global share
			
			$res = $objFilemarks->listFilemarks($filter, $array_bind);
			
			$company_filter = " AND fk_empresa = :fk_empresa AND global = :global";
			
			$company_array_bind[':fk_empresa'] =  $objUsers->userCompany();
			
			$company_array_bind[':global'] = '0';
			
			$company_res = $objFilemarks->listFilemarks($company_filter, $company_array_bind);
			
		    
			
			if (count($res) >= 1 || count($company_res) >= 1) {
				
				foreach ($res as $row) {
		
					$drop_down_list .= '<li><a class="set-filemarker" data-set-filemark-id="'.$row_id.'" data-set-filemark-value="'.$row['id'].'">'.$row['label'].'</a></li>';
					
				}
				
				foreach ($company_res as $row) {
					$drop_down_list .= '<li><a class="set-filemarker" data-set-filemark-id="'.$row_id.'" data-set-filemark-value="'.$row['id'].'">'.$row['label'].'</a></li>';
				}
		 
			}
	
	}

	return '<button id="set-filemark-button'.$row_id.'" href="#" data-dropdown="drop'.$row_id.'" aria-controls="drop'.$row_id.'" aria-expanded="false" class="'.$disabled_class.'tiny button dropdown">'.$label.'</button><br>
<ul id="drop'.$row_id.'" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">
  '.$drop_down_list.'
 </ul>';
 

}

?>
<script>
	$(document).foundation();
	
	$( ".set-filemarker" ).unbind("click").bind("click",
			function() {	
				vFileId=$(this).attr('data-set-filemark-id');
				vFilemarkId=$(this).attr('data-set-filemark-value');
			
			originalText = $("#set-filemark-button"+vFileId).html();
			
			if (vFileId.length > 0 && vFilemarkId.length > 0) {
				$("#set-filemark-button"+vFileId).html("Updating...");
				
				$.ajax({
				   type: "GET",
				   url: "lib/data/dFiles.action.ajax.php",
				   data: "action=update&id="+vFileId+"&file_mark_id="+vFilemarkId,
				   success: function(html){
					if(html != "")
					{
						$("#set-filemark-button"+vFileId).html(html);
						$("ul.f-dropdown").removeClass("open");
						$("ul.f-dropdown").css({left: '-99999px' , position:'absolute'});
						 
					}
					else
					{   $("#set-filemark-button"+vFileId).html(originalText);
						alert('error');
					}
				 }
				});
			}

		}
	);
	//
	//$(document).on("click", "a[class*='set-filemarker']", function() {
	//	
	//	
	//	vFileId=$(this).attr('data-set-filemark-id');
	//	vFilemarkId=$(this).attr('data-set-filemark-value');
	// 
	// 
	//    alert(vFileId);
	//	alert(vFilemarkId);
	//	
	//    if (vFileId.length > 0 && vFilemarkId.length > 0) {
	//		
	//		$.ajax({
	//		   type: "GET",
	//		   url: "lib/data/dFiles.action.ajax.php",
	//		   data: "action=update&id="+vFileId+"&file_mark_id="+vFilemarkId,
	//		   success: function(html){
	//			if(html != "")
	//			{
	//				$("#set-filemark-button"+vFileId).html(html);
	//			}
	//			else
	//			{
	//				alert('error');
	//			}
	//		 }
	//		});
	//    }
	//
	//	
	//
	//} );
</script>

