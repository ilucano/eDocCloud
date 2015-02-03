<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	if ($arrPerm['view']!='X') { header ("Location: ../noperm.php"); }
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	require_once $arrIni['base'].'inc/companies.class.php';

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
	
	
	$objUsers = new Users();
    
	$companyCode = $objUsers->userCompany();
	
	$filter = " AND fk_empresa = :fk_empresa";
	
	$array_bind[':fk_empresa'] = $companyCode;
	
	$res = $objUsers->listUsers($filter, $array_bind);
	
	$objCompanies = new Companies;
	
	$con = ConnectionFactory::getConnection();
		
	// COMIENZO DEL CAMBIO
	//if ($txtSearch!="") {
	//	$qryAdd = " WHERE first_name LIKE '%".$txtSearch."%' OR last_name LIKE '%".$txtSearch."%'";
	//}
	//$qryCnt = "SELECT COUNT(*) as num FROM users ".$qryAdd;
	// FIN DEL CAMBIO
		
	//$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	//$total_pages = $total_pages['num'];
	//echo $total_pages;
		
	// COMIENZO DEL CAMBIO
	//if ($total_pages>$limit || $pagAct > 0) {
	//	if ($pagAct==0) {
	//		$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM users T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id ".$qryAdd." LIMIT ".($pagAct * $limit).",".($limit).";";
	//	} else {
	//		$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM users T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id ".$qryAdd." LIMIT ".(($pagAct) * $limit).",".($limit).";";
	//	}
	//} else {
	//	$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM users T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id ".$qryAdd.";";
	//}
	// FIN DEL CAMBIO
	
	//echo $qryFT;
	//mysql_query("SET NAMES UTF8");
	//$res = mysql_query($qryFT);
	
	if (count($res) >= 1) {
		// COMIENZO DEL CAMBIO
		echo "<table><tbody><thead><tr><th width=\"20%\">Name</th><th width=\"20%\">Company</th><th width=\"20%\">Email</th><th width=\"7%\">Admin</th><th width=\"7%\">Active</th><th width=\"6%\">Comp. Adm.</th><th width=\"20%\">Actions</th></tr></thead>";
		
		foreach ($res as $row) {
			
			$company = $objCompanies->getCompany($row['fk_empresa']);
	 
			echo "<tr><td width=20%>";
			echo $row['first_name'].' '.$row['last_name'].' ('.$row['username'].')'.'</td>';
			echo '<td width="20%">'.$company['company_name'].'</td>';
			echo '<td width="20%">'.$row['email'].'</td>';
			echo '<td width="7%">'.ConvertToYesNo($row['is_admin']).'</td>';
			echo '<td width="7%">'.ConvertToYesNo($row['status']).'</td>';
			echo '<td width="6%">'.ConvertToYesNo($row['company_admin']).'</td>';
			// FIN DEL CAMBIO
			echo '<td width="10%">';
			
			echo '<a href="#" data-type="view" data-page="'.$row['row_id'].'" data-reveal-id="buttons">View</a>'; 
			echo ' | <a href="#" data-type="edit" data-page="'.$row['row_id'].'" data-reveal-id="buttons">Edit</a>'; 
		 
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
