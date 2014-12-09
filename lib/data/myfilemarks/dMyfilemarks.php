<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	if ($arrPerm['view']!='X') { header ("Location: ../noperm.php"); }
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
    
	require_once $arrIni['base'].'inc/filemarks.class.php';
	
	$objUsers = new Users;
		
	$pagAct =  $_GET['pagAct'] ;
	$txtSearch = $_GET['txtsearch'] ;
	$limit = 50;
	$adj = 2;
	
	if ($pagAct=="") { $pagAct=0; }
	
	$btnFirst = "<div class=\"row\">";
	$btnCreate = "<div class=\"large-2 columns\"><a href=\"#\" class=\"button tiny\" data-type=\"create\" data-page=\"\" data-reveal-id=\"buttons\">Create</a></div>";
	$btnSearch = "<div  style=\"display:none;\" class=\"large-6 columns\"><div class=\"row collapse\"><div class=\"small-10 columns\"><input type=\"text\" id=\"txtsearch\" name=\"txtsearch\" placeholder=\"Enter search text here\" value=\"".$txtSearch."\"></div> <div class=\"small-2 columns\"><a href=\"#\" class=\"button postfix\" data-type=\"pagina\" data-page=\"\" data-reveal-id=\"grill\">Search</a></div></div><div class=\"large-4 columns\"></div></div>";
	$btnLast = "</div>";
	$antes = "<div class=\"large-1 columns\">&nbsp;</div><div class=\"large-10 columns\">";
	$despues = "</div><div class=\"large-1 columns\"></div></div>";
	
	echo $antes.$btnFirst;
	if ($arrPerm['create']=='X') { echo $btnCreate; }
	echo $btnSearch.$btnLast;
	
	
	$objFilemarks = new Filemarks();

	$filter = " AND global = :global";
	
	$array_bind[':global'] = '1'; //fk_empresa = global share
	
	$res = $objFilemarks->listFilemarks($filter, $array_bind);
    
	$company_filter = " AND fk_empresa = :fk_empresa AND global = :global";
	$company_array_bind[':fk_empresa'] =  $objUsers->userCompany();
	
	$company_array_bind[':global'] = '0';
	
	$company_res = $objFilemarks->listFilemarks($company_filter, $company_array_bind);
	
	if (count($res) >= 1 || count($company_res) >= 1) {
		
		 
		// COMIENZO DEL CAMBIO
		echo "<table><tbody><thead><tr><th width=\"20%\">Mark Label</th><th width=\"20%\">Create Date</th><th width=\"20%\">Actions</th></tr></thead>";
		
		foreach ($res as $row) {

			echo '<td width="20%">'.$row['label'].'</td>';
			echo '<td width="20%">'.$row['create_date'].'</td>';
 
			echo '<td width="20%">';
			
			echo '(Built-in, non-editable)'; 
		 
			echo '</td>';
			
			echo "</tr></td>";
		}
		
		foreach ($company_res as $row) {

			echo '<td width="20%">'.$row['label'].'</td>';
			echo '<td width="20%">'.$row['create_date'].'</td>';
 
			echo '<td width="20%">';
			
			echo '<a href="#" data-type="edit" data-page="'.$row['id'].'" data-reveal-id="buttons">Edit</a>'; 
	 
			echo '</td>';
			
			echo "</tr></td>";
		}
		
		echo "</tbody></table>";
 
	} else {
			echo $antes;
			echo "No results";
			echo $despues;
	}
			
	
	echo $despues;
	
 
	function ConvertToYesNo($vIn) {
	
		if ($vIn=="X") {
			$vRet = "Yes";
		} else {
			$vRet = "No";
		}
		
		return $vRet;
	
	}

?>