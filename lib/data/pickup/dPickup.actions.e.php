<?php
	
	// Solo hay que cambiar esto!
	$table = 'pickup';

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
							//echo $valor[0].' '.$row['Type'];
							if (substr($row['Type'],0,3)=="int") {
								$stmt->bindValue(':'.$valor[0], $valor[1], PDO::PARAM_INT);
							} else if (substr($row['Type'],0,6)=="bigint") {
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
				//echo "OK";
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
							if (substr($row['Type'],0,3)=="int") {
								//echo "int:".$valor[1];
								$stmt->bindValue(':'.$valor[0], urldecode($valor[1]), PDO::PARAM_INT);
							} else if (substr($row['Type'],0,6)=="bigint") {
								//echo "bigint:".$valor[1];
								$stmt->bindValue(':'.$valor[0], $valor[1], PDO::PARAM_INT);
							} else {
								//echo "str:".$valor[1];
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
	
?>