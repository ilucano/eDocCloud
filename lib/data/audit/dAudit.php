<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	if ($arrPerm['view']!='X') { header ("Location: ../noperm.php"); }
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	$pagAct =  $_GET['pagAct'] ;
	$txtSearch = $_GET['txtsearch'] ;
	$limit = 50;
	$adj = 2;
	
	if ($pagAct=="") { $pagAct=0; }
	
	$btnFirst = "<div class=\"row\">";
	$btnCreate = "<div class=\"large-2 columns\"><a href=\"#\" class=\"button tiny\" data-type=\"create\" data-page=\"\" data-reveal-id=\"buttons\">Create</a></div>";
	$btnSearch = "<div class=\"large-6 columns\"><div class=\"row collapse\"><div class=\"small-10 columns\"><input type=\"text\" id=\"txtsearch\" name=\"txtsearch\" placeholder=\"Enter search text here\" value=\"".$txtSearch."\"></div> <div class=\"small-2 columns\"><a href=\"#\" class=\"button postfix\" data-type=\"pagina\" data-page=\"\" data-reveal-id=\"grill\">Search</a></div></div><div class=\"large-4 columns\"></div></div>";
	$btnLast = "</div>";
	$antes = "<div class=\"large-1 columns\">&nbsp;</div><div class=\"large-10 columns\">";
	$despues = "</div><div class=\"large-1 columns\"></div></div>";
	
	echo $antes.$btnFirst;
	if ($arrPerm['create']=='X') { echo $btnCreate; }
	echo $btnSearch.$btnLast;
		
	$con = ConnectionFactory::getConnection();
		
	// COMIENZO DEL CAMBIO
	if ($txtSearch!="") {
		$qryAdd = " WHERE `username` LIKE '%".$txtSearch."%' OR `module` LIKE '%".$txtSearch."%' ";
	}
	$qryCnt = "SELECT COUNT(*) as num FROM activity_logs ".$qryAdd;
	// FIN DEL CAMBIO
		
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
	//echo $total_pages;
		
	// COMIENZO DEL CAMBIO
	if ($total_pages>$limit || $pagAct > 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT *  FROM activity_logs ".$qryAdd . " ORDER BY row_id desc LIMIT ".($pagAct * $limit).",".($limit).";";
		} else {
			$qryFT =  "SELECT *   num FROM activity_logs ".$qryAdd . " ORDER BY row_id desc LIMIT ".(($pagAct) * $limit).",".($limit).";";
		}
	} else {
		$qryFT = "SELECT *  FROM activity_logs ".$qryAdd . " ORDER BY row_id desc";
	}
	// FIN DEL CAMBIO
	
	//echo $qryFT;
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	if (mysql_num_rows($res)) {
		// COMIENZO DEL CAMBIO
		echo "<table><tbody><thead><tr><th width=\"7%\">Id</th><th width=\"30%\">Company</th><th width=\"20%\">Order</th><th width=\"10%\">Barcode</th><th width=\"18%\">Box</th><th width=\"20%\">Actions</th></tr></thead>";
		
		while ($row = mysql_fetch_array($res)) {
			print_r($row);
			echo "<tr><td width=7%>";
			echo $row['row_id'].'</td>';
			echo '<td width="30%">'.$row['empresa'].'</td>';
			echo '<td width="20%">'.$row['orden'].'</td>';
			echo '<td width="10%">'.$row['fk_barcode'].'</td>';
			echo '<td width="18%">'.$row['caja'].'</td>';
			// FIN DEL CAMBIO
			echo '<td width="10%">';
			
			if ($arrPerm['view']=='X') { echo '<a href="#" data-type="view" data-page="'.$row['row_id'].'" data-reveal-id="buttons">View</a>'; }
			if ($arrPerm['edit']=='X') { echo ' | <a href="#" data-type="edit" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Edit</a>'; }
			if ($arrPerm['delete']=='X') { echo ' | <a href="#" data-type="delete" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Delete</a> '; }
			//if ($arrPerm['edit']=='X' && $row['fk_status']!=5) { echo ' | <a href="#" data-type="close" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Close</a> '; echo ' | <a href="#" data-type="sum" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Sum</a> '; }

			echo '</td>';
			
			echo "</tr></td>";
		}
		echo "</tbody></table>";
		if ($total_pages>$limit || $pagAct > 0) {
			
			echo "<ul class=\"pagination\">";
			if ($pagAct==0) {
				echo "<li class=\"arrow unavailable\">&laquo;</li>";
			} else {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"0\" data-reveal-id=\"grill\">&laquo;</a></li>";
			}
			
			$lastpage = ceil($total_pages/$limit);
			
			for ($counter = 1; $counter <= $lastpage; $counter++) {
				if (($counter-1)==$pagAct) {
					echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
				} else {
					echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"grill\">".($counter)."</a></li>";
				}
			}
					
			if (($lastpage-1)!=$pagAct) {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"".($lastpage-1)."\" data-reveal-id=\"grill\">&raquo;</a></li>";
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
	
	ConnectionFactory::close();

	function ConvertToYesNo($vIn) {
	
		if ($vIn=="X") {
			$vRet = "Yes";
		} else {
			$vRet = "No";
		}
		
		return $vRet;
	
	}

?>