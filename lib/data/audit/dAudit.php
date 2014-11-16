<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	if ($arrPerm['view']!='X') { header ("Location: ../noperm.php"); }
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	$pagAct =  $_GET['pagAct'] ;
	$txtSearch = $_GET['txtsearch'] ;
	$limit = 10;
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
		echo "<table><tbody><thead><tr><th width=\"7%\">Id</th><th width=\"15%\">Username</th><th width=\"20%\">Event</th><th width=\"30%\">Parameters</th><th width=\"15%\">IP Address</th><th width=\"15%\">Date</th></tr></thead>";
		
		while ($row = mysql_fetch_array($res)) {
			 
			echo "<tr><td valign=top>";
			echo $row['row_id'].'</td>';
			echo '<td  valign=top>'.$row['username'].'</td>';
			echo '<td valign=top>'.$row['module'].'</td>';
			echo '<td valign=top>';
			
			$arr_params = json_decode($row['parameters']);
			echo "<ul style='list-style-type: none;'>";
			foreach ($arr_params as $param_key => $param_value)
			{
				echo "<li>";
				echo "<span style='padding: 5px;' class=\"success radius label\">".$param_key."</span> : ";
				echo "<span style='padding: 5px;' class=\"radius label\">".$param_value."</span>";
				echo "</li>";
			}
			echo "</ul>";
			echo '</td>';
			echo '<td valign=top>'.$row['ip_address'].'</td>';
			// FIN DEL CAMBIO
			echo '<td valign=top>';
		    echo $row['create_date'];
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