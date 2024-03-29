<?php
session_start();

require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/check.php';
require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'lib/db/db.php' ;
require_once $arrIni['base'].'inc/checkACL.php';

require_once $arrIni['base'].'inc/users.class.php';
require_once $arrIni['base'].'inc/filemarks.class.php';

session_start();

$txtSearch =  $_GET['texto'] ;
$pagAct =  $_GET['pagAct'] ;
$limit = 15;
$adj = 2;

if ($pagAct=="") { $pagAct=0; }

$antes = "<div class=\"large-2 columns\"></div><div class=\"large-10 columns\">";
$despues = "</div><div class=\"large-1 columns\"></div></div>";

if ($txtSearch=="") {
	echo $antes;
	echo "Please enter any text to search...";
	echo $despues;
} else {
	
	$objFilemarks = new Filemarks;
		
	echo $antes;
	
	$con = ConnectionFactory::getConnection();
	
	//fix the query
	
	$matchExactAllTerms = addslashes($txtSearch);
	
	$arrayText = explode(" ", $txtSearch);
	
	foreach($arrayText as $singleWord)
	{
		if($singleWord)
		{
			$arrayTextMatchAll[] = "+".$singleWord;
			
		}
	}
	
    
	$objUsers = new Users;
	
	$me = $objUsers->getOwnDetails();
	
	$array_file_permission = json_decode($me['file_permission'], true);
	
	if(count($array_file_permission) >=1)
	{
		$file_in_string = join(", ", $array_file_permission);
		
		$user_file_mark_id_allowed = " OR file_mark_id IN ($file_in_string) ";
	}
	
	
	$filter_file_permission = " AND (file_mark_id IS NULL OR file_mark_id = '' $user_file_mark_id_allowed ) ";
	
	

	$matchAndAllTerms = addslashes(implode(" ", $arrayTextMatchAll));
    
	
	$mainMatchQuery = " MATCH(texto) AGAINST('".$matchAndAllTerms."' IN BOOLEAN MODE) AS Score1, MATCH(texto) AGAINST('" .
	$matchExactAllTerms ."' IN BOOLEAN MODE) AS Score2 FROM files WHERE MATCH(texto) AGAINST ('".$matchAndAllTerms."' IN BOOLEAN MODE) AND fk_empresa = " . $_SESSION['CoCo'] . $filter_file_permission ;
	
	
	$qryCnt = "SELECT COUNT(*) as num, " . $mainMatchQuery;
	
	//echo $qryCnt;
	
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
	
	if ($total_pages>$limit || $pagAct > 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, filename, texto, file_mark_id, " . $mainMatchQuery. " ORDER BY Score2 Desc, Score1 desc LIMIT ".($pagAct * $limit).",".($limit).";";
		} else {
			$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, filename, texto, file_mark_id, " . $mainMatchQuery ." ORDER BY Score2 Desc, Score1 desc LIMIT ".(($pagAct) * $limit).",".($limit).";";
		}
		
	} else {
		$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, filename, texto, file_mark_id, " . $mainMatchQuery ." ORDER BY Score2 Desc, Score1 desc;";
	}
	
	//echo $qryFT;
	
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	
	if (mysql_num_rows($res)) {
		
		$group_permission = GetUserPermission();
		
		$show_file_marker  = ($group_permission['use_file_marker']['view'] == 1 || $group_permission['use_file_marker']['change'] == 1) ? true : false;
		
		if($show_file_marker == true) {
			$marker_header = '<th width="30%">Marks</th>';
		}
			
		echo "<table><tbody><thead><tr><th width=\"25%\">Filename</th>".$marker_header."<th width=\"20%\">Creation Date</th><th width=\"20%\">Modifcation Date</th><th width=\"10%\">Pages</th><th width=\"10%\">Size</th></tr></thead>";
		while ($row = mysql_fetch_array($res)) {
			echo "<tr><td width=25%>";
			
  			echo '<a href="lib/data/file.download.php?fileid='.$row['row_id'].'" target="_blank">'.$row['filename'].'</a></td>';
			
					
			if($show_file_marker == true) {
					
				echo "<td>";
				echo dropDownButton($row['row_id'], $row['file_mark_id']);
				echo "</td>";
			}
			
			$fecha = date("m/d/Y G:i:s",strtotime($row['creadate']));
			echo '<td width="20%">'.$fecha.'</td>';
			$fecha = date("m/d/Y G:i:s",strtotime($row['moddate']));
			echo '<td width="20%">'.$fecha.'</td>';
			echo '<td width="10%">'.$row['pages'].'</td>';
			$bytes = $row['filesize'];

			if ($bytes < 1048576) {
				$bytes = number_format($row['filesize'] / 1024,2).' Kb';
			} else {
				$bytes = number_format($row['filesize'] / 1024 / 1024,2).' Mb';
			}
			//$mbytes = number_format($row['filesize'] / 1024 / 1024,2);
			echo '<td width="10%">'.$bytes.'</td>';
			
			echo "</tr></td>";
		}
		echo "</tbody></table>";
		if ($total_pages>$limit || $pagAct > 0) {
			
			echo "<ul class=\"pagination\">";
			if ($pagAct==0) {
				echo "<li class=\"arrow unavailable\">&laquo;</li>";
			} else {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"0\" data-reveal-id=\"buscar\">&laquo;</a></li>";
			}
			
			$lastpage = ceil($total_pages/$limit);
			
			if ($lastpage<5) {
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"buscar\">".($counter)."</a></li>";
					}
				}
			} else {
				for ($counter = 1; $counter < 1 + ($adj * 2); $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"buscar\">".($counter)."</a></li>";
					}
				}
			}
			//<li class="current"><a href="">1</a></li> <li><a href="">2</a></li> <li><a href="">3</a></li> <li><a href="">4</a></li> <li class="unavailable"><a href="">&hellip;</a></li> <li><a href="">12</a></li> <li><a href="">13</a></li> 
			
			
			
			if ($lastpag==$pagAct) {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"".$lastpage."\" data-reveal-id=\"buscar\">&raquo;</a></li>";
			} else {
				echo "<li class=\"arrow unavailable\">&raquo;</li>";
			}
			
			echo "</ul>";
		}
	} else {
			echo $antes;
			echo "No results";
			echo $despues;
		}
			
	
	echo $despues;
}


function dropDownButton($row_id, $mark_id)
{
	
	$group_permission = GetUserPermission();
	
	$objFilemarks = new Filemarks;
	$objUsers = new Users;
	$label = $objFilemarks->getLabelById($mark_id);
	
	if($label == '')
	{
		$label = "(No Mark)";
	}
	
	$drop_down_list = '';
	$disabled_class = "disabled secondary ";
	
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

				$drop_down_list .= '<li><a class="set-filemarker" data-set-filemark-id="'.$row_id.'" data-set-filemark-value=""> <i>Remove Mark</i></a></li>';
	
			}
			$drop_down_list = '<ul id="drop'.$row_id.'" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">'.$drop_down_list.'</ul>';
	
	}

	return '<button id="set-filemark-button'.$row_id.'" href="#" data-dropdown="drop'.$row_id.'" aria-controls="drop'.$row_id.'" aria-expanded="false" class="'.$disabled_class.'tiny button dropdown">'.$label.'</button><br>' .$drop_down_list;

}


ConnectionFactory::close();

require_once $arrIni['base'].'inc/activity_logs.class.php';

$ActivityLogs = new Activity_Logs();
$ActivityLogs->log();

?>


<script>
	$(document).foundation();
	
	$( ".set-filemarker" ).unbind("click").bind("click",
			function() {	
				vFileId=$(this).attr('data-set-filemark-id');
				vFilemarkId=$(this).attr('data-set-filemark-value');
			
			originalText = $("#set-filemark-button"+vFileId).html();
			
			if (vFileId.length > 0) {
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
 
</script>

