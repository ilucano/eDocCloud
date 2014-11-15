<?php
	
	// Solo hay que cambiar esto!
	$table = 'companies';

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	$action =  basename( $_GET['action'] );
	$id =  basename( $_GET['id'] );
	
	$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
	$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
	
	if ($arrPerm[$action]!='X') { 
		echo "You don't have permissions";
	} else {
		//require_once $arrIni['base'].'inc/general.php';
		require_once $arrIni['base'].'lib/db/dbConn.php' ;
		
		$con = NConnectionFactory::getConnection();
		$con2 = NConnectionFactory::getConnection();
		
		switch ($action) {
			case "create":
				$qry1 = "INSERT INTO ".$table." (";
				$qry3 = ") VALUES (";
				$qry5 = ");";
				
				$arr = split("&", str_replace("+"," ",$_SERVER['QUERY_STRING']));
				
				$int = 0;
				
				foreach ($arr as $arrItem) {
					$inArr = split("=",$arrItem);
					if ($inArr[0]!='action' && $inArr[0]!='id') {
						$qry2 = $qry2." ".$inArr[0].",";
						$qry4 = $qry4." :".$inArr[0].",";
						$arrValues[$int][0] = $inArr[0];
						$arrValues[$int][1] = urldecode($inArr[1]);
					}
					$int = $int + 1;
				}
				
				$qry2 = substr($qry2,0,(strlen($qry2)-1));
				$qry4 = substr($qry4,0,(strlen($qry4)-1));
				
				try {
					
					//var_dump($meta);
					
					$stmt = $con->prepare($qry1.$qry2.$qry3.$qry4.$qry5);
					
					//echo var_dump($arrValues);
					
					foreach ($arrValues as $valor) {
						$strQry = "SHOW COLUMNS FROM ".$table." WHERE Field = '".$valor[0]."';";
						//echo $strQry;
						foreach ($con2->query($strQry) as $row) {
							if (substr($row['Type'],0,3)=="int") {
								$stmt->bindValue(':'.$valor[0], $valor[1], PDO::PARAM_INT);
							} else {
								$stmt->bindValue(':'.$valor[0], $valor[1], PDO::PARAM_STR);
							}
						}
					}
									
					$stmt->execute();
					echo "Creation successful";
				} catch(PDOException $ex) {
					echo "An Error occured!"; //user friendly message
				}
				
				break;
				
			case "edit":
				$qry1 = "UPDATE ".$table." SET ";
				$qry3 = " WHERE row_id = ";
				$qry5 = ";";
				
				$arr = split("&", str_replace("+"," ",$_SERVER['QUERY_STRING']));
				
				$int = 0;
				
				foreach ($arr as $arrItem) {
					$inArr = split("=",$arrItem);
					if ($inArr[0]!='action' && $inArr[0]!='id') {
						$qry2 = $qry2."  ".$inArr[0]." = :".$inArr[0]." ,";
						$arrValues[$int][0] = $inArr[0];
						$arrValues[$int][1] = urldecode($inArr[1]);
					}
					$int = $int + 1;
				}
				
				$qry2 = substr($qry2,0,(strlen($qry2)-1));
				//$qry4 = substr($qry4,0,(strlen($qry4)-1));
				
				try {
					
					//var_dump($meta);
					
					$stmt = $con->prepare($qry1.$qry2.$qry3.$id.$qry5);
					//echo $qry1.$qry2.$qry3.$id.$qry5;
					//echo var_dump($arrValues);
					
					foreach ($arrValues as $valor) {
						$strQry = "SHOW COLUMNS FROM ".$table." WHERE Field = '".$valor[0]."';";
						//echo $strQry;
						foreach ($con2->query($strQry) as $row) {
							//echo $valor[1];
							if (substr($row['Type'],0,3)=="int") {
								$stmt->bindValue(':'.$valor[0], urldecode($valor[1]), PDO::PARAM_INT);
							} else {
								$stmt->bindValue(':'.$valor[0], urldecode($valor[1]), PDO::PARAM_STR);
							}
						}
					}
									
					$stmt->execute();
					echo "Record updated...";
				} catch(PDOException $ex) {
					echo "An Error occured!"; //user friendly message
				}
				
				break;
			}
		
		ConnectionFactory::close();
	}
	
	
	
	/*
	;
		
	$qryCnt = "SELECT COUNT(*) as num FROM groups WHERE fk_empresa = ".$_SESSION['CoCo'];
		
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
		
	if ($total_pages>$limit || $pagAct > 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo']." LIMIT ".($pagAct * $limit).",".($limit).";";
		} else {
			$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo']." LIMIT ".(($pagAct) * $limit).",".($limit).";";
		}
	} else {
		$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo'].";";
	}
		
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	if (mysql_num_rows($res)) {
		echo "<table><tbody><thead><tr><th width=\"40%\">Group Name</th><th width=\"40%\">Company</th><th width=\"20%\">Actions</th></tr></thead>";
		while ($row = mysql_fetch_array($res)) {
			echo "<tr><td width=50%>";
			echo $row['nombre'].'</td>';
			echo '<td width="40%">'.$row['empresa'].'</td>';

			echo '<td width="10%">';
			
			if ($arrPerm['view']=='X') { echo '<a href="#">View</a>'; }
			if ($arrPerm['edit']=='X') { echo ' | <a href="#">Edit</a>'; }
			if ($arrPerm['delete']=='X') { echo ' | <a href="#">Delete</a> '; }

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
			
			if ($lastpage<5) {
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"grill\">".($counter)."</a></li>";
					}
				}
			} else {
				for ($counter = 1; $counter < 1 + ($adj * 2); $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"grill\">".($counter)."</a></li>";
					}
				}
			}
			
			if ($lastpag==$pagAct) {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"".$lastpage."\" data-reveal-id=\"grill\">&raquo;</a></li>";
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
	
	
*/

?>