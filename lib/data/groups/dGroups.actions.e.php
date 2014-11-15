<?php
	
	// Solo hay que cambiar esto!
	$table = 'groups';

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
						if ($inArr[0]=="first_name"){ $vFirst_name = $inArr[1];}
						if ($inArr[0]=="last_name"){ $vLast_name = $inArr[1];}
						if ($inArr[0]=="email"){ $vEmail = urldecode($inArr[1]);}
						if ($inArr[0]=="username"){ $vUsername = urldecode($inArr[1]);}
						if ($inArr[0]=="password"){ $vPassword = urldecode($inArr[1]);}
						
						$qry2 = $qry2." ".$inArr[0].",";
						$qry4 = $qry4." :".$inArr[0].",";
						$arrValues[$int][0] = $inArr[0];
						$arrValues[$int][1] = urldecode($inArr[1]);
					}
					$int = $int + 1;
				}
				
				$to = "\"".$vFirst_name.' '.$vLast_name."\" <".$vEmail.">";
				$vUser = $row['username'];
				$vPass = $row['password'];
				
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

					require_once $arrIni['base'].'framework/email/email.php' ;
					
					SendEmail($to,$vUsername,$vPassword, $vFirst_name.' '.$vLast_name);

					echo "Creation successful";
				} catch(PDOException $ex) {
					echo "An Error occured!"; //user friendly message
				}
				
				break;
				
			case "edit":
			 
				try {
					
					//var_dump($meta)
					
					foreach($_GET['group_permission'] as $key => $arrCode)
					{
						foreach($arrCode as  $code) {
							$array_permission[$key][$code] = 1;
						}
					}
					$json_group_permission = json_encode($array_permission);
					
					
					$update_query = "UPDATE groups ";
					$update_query .= "SET nombre = '" . addslashes($_GET['nombre']) . "' ";
					$update_query .= ", group_permission = '" . addslashes($json_group_permission) . "' ";
					$update_query .= " WHERE row_id = '".addslashes($_GET['id']) ."'";
				    $stmt = $con->prepare($update_query);
							
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