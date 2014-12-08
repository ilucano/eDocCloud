<?php
	
	// Solo hay que cambiar esto!
	$table = 'users';

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	require_once $arrIni['base'].'inc/filemarks.class.php';
	
			
	$objFilemarks = new Filemarks;
	
	$objUsers = new Users;
	
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
				
				$filter = " `label` = :label AND global = 1";
				
				$label = trim($_GET['label']);
				
				$array_bind = array(':label' => $label);
				
				$exist = $objFilemarks->getRecordByFilter($filter, $array_bind);
				
				if(is_array($exist) && $exist['id'])
				{
					echo "File marker already exist."; //user friendly message
					
				}
				else {
				   
				    $companyCode = $objUsers->userCompany();
				
					$data['label'] = $_GET['label'];
					$data['fk_empresa'] = $companyCode;
					
					$data['global'] = 1;
					
					$data['create_date'] = date("Y-m-d H:i:s");
					
					$objFilemarks->insertRecord($data);
					
					echo "Creation successful";
					
				}
				
			break;
 
				
			case "edit":
 
				$data['label'] = $_GET['label'];
				
				$objFilemarks->updateRecord($data, $_GET['id']);
				
			    echo "Record updated...";
			 
				break;
			}
		
		ConnectionFactory::close();
	}

?>