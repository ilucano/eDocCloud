<?php
	
	// Solo hay que cambiar esto!
	$table = 'groups';

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	$objUsers = new Users();
	
	
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
				
				foreach($_GET['group_permission'] as $key => $arrCode)
				{
					foreach($arrCode as  $code) {
						$array_permission[$key][$code] = 1;
					}
				}
				$json_group_permission = json_encode($array_permission);
				
				
				$companyCode = $objUsers->userCompany();
				
				$insert_query = "INSERT INTO groups (`nombre`, `fk_empresa`, `group_permission`)
				                 VALUES (:nombre, :fk_empresa, :group_permission)";
								 
				$bind_array = array(':nombre' => $_GET['nombre'],
							        ':fk_empresa' => $companyCode,
									':group_permission' => $json_group_permission);
				
				
				try {
					
					$stmt = $con->prepare($insert_query);
						
					$stmt->execute($bind_array);
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