<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	if ($arrPerm['view']!='X') { header ("Location: ../noperm.php"); }
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	$pagAct =  $_GET['pagAct'] ;
	$txtSearch = $_GET['txtsearch'] ;
	$limit = 5;
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
		$qryAdd = " AND nombre LIKE '%".$txtSearch."%' ";
	}
	$qryCnt = "SELECT COUNT(*) as num FROM groups WHERE fk_empresa = ".$_SESSION['CoCo'].$qryAdd;
	// FIN DEL CAMBIO
		
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
	//echo $total_pages;
		
	// COMIENZO DEL CAMBIO
	if ($total_pages>$limit || $pagAct > 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo'].$qryAdd." LIMIT ".($pagAct * $limit).",".($limit).";";
		} else {
			$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo'].$qryAdd." LIMIT ".(($pagAct) * $limit).",".($limit).";";
		}
	} else {
		$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo'].$qryAdd.";";
	}
	// FIN DEL CAMBIO
	
	//echo $qryFT;
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	if (mysql_num_rows($res)) {
		// COMIENZO DEL CAMBIO
		echo "<table><tbody><thead><tr><th width=\"40%\">Group Name</th><th width=\"40%\">Company</th><th width=\"20%\">Actions</th></tr></thead>";
		
		while ($row = mysql_fetch_array($res)) {
			echo "<tr><td width=50%>";
			echo $row['nombre'].'</td>';
			echo '<td width="40%">'.$row['empresa'].'</td>';
			// FIN DEL CAMBIO
			echo '<td width="10%">';
			
			if ($arrPerm['view']=='X') { echo '<a href="#" data-type="view" data-page="'.$row['row_id'].'" data-reveal-id="buttons">View</a>'; }
			if ($arrPerm['edit']=='X') { echo ' | <a href="#" data-type="edit" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Edit</a>'; }
			if ($arrPerm['delete']=='X') { echo ' | <a href="#" data-type="delete" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Delete</a> '; }

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


?>