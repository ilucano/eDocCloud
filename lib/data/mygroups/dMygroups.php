<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	if ($arrPerm['view']!='X') { header ("Location: ../noperm.php"); }
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	require_once $arrIni['base'].'lib/db/dbConn.php' ;
	
	require_once $arrIni['base'].'inc/groups.class.php';
	
	$pagAct =  $_GET['pagAct'] ;
    
	$limit = 5;
	$adj = 2;
	
	if ($pagAct=="") { $pagAct=0; }
	
	$btnFirst = "<div class=\"row\">";
	$btnCreate = "<div class=\"large-2 columns\"><a href=\"#\" class=\"button tiny\" data-type=\"create\" data-page=\"\" data-reveal-id=\"buttons\">Create</a></div>";
	//$btnSearch = "<div class=\"large-6 columns\"><div class=\"row collapse\"><div class=\"small-10 columns\"><input type=\"text\" id=\"txtsearch\" name=\"txtsearch\" placeholder=\"Enter search text here\" value=\"".$txtSearch."\"></div> <div class=\"small-2 columns\"><a href=\"#\" class=\"button postfix\" data-type=\"pagina\" data-page=\"\" data-reveal-id=\"grill\">Search</a></div></div><div class=\"large-4 columns\"></div></div>";
	$btnLast = "</div>";
	$antes = "<div class=\"large-1 columns\">&nbsp;</div><div class=\"large-10 columns\">";
	$despues = "</div><div class=\"large-1 columns\"></div></div>";
	
	echo $antes.$btnFirst;
	if ($arrPerm['create']=='X') { echo $btnCreate; }
	echo $btnSearch.$btnLast;
	
	//$pdocon = NConnectionFactory::getConnection();
	
	//$stmt = $pdocon->prepare('SELECT * FROM groups order by row_id');
   // $stmt->execute();
    
	//$group_rows = array();
	//while($row = $stmt->fetch()) {
    //   $group_rows[] = $row;
    //}
 
	
	
	
	$objUsers = new Users();
    
	$companyCode = $objUsers->userCompany();
	
	$objGroups = new Groups;
	
	$filter = " AND fk_empresa = :fk_empresa";
	
	$array_bind[':fk_empresa'] = $companyCode;
	
	$group_rows = $objGroups->listGroups($filter, $array_bind);
	
	
	if (count($group_rows) > 0 ) {
		// COMIENZO DEL CAMBIO
		echo "<table><tbody><thead><tr><th width=\"30%\">Group Name</th><th width=\"30%\">Group Permission</th></tr></thead>";
		
		foreach ($group_rows as $row) {
			echo "<tr><td>";
			echo $row['nombre'].'</td>';
			 
			echo '<td>';
		
			echo '<a href="#" data-type="edit" data-page="'.$row['row_id'].'" data-reveal-id="buttons">View / Edit</a>';

			echo '</td>';
			
			echo "</tr>";
		}
		echo "</tbody></table>";
		 
	} else {
			echo $antes;
			echo "You have not create any group yet";
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