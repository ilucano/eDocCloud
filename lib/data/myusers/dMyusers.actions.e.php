<?php
	
	// Solo hay que cambiar esto!
	$table = 'users';

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
			
	$objUsers  = new Users;
	
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
				
				$exist = $objUsers->getUserByUsername($_GET['username']);
				
				if(count($exist) >= 1)
				{
					echo "Username already exist."; //user friendly message
					
				}
				else {
				 
					$data['username'] = $_GET['username'];
					$data['password'] = $_GET['password'];
					$data['first_name'] = $_GET['first_name'];
					$data['last_name'] = $_GET['last_name'];
					$data['email'] = $_GET['email'];
					$data['phone'] = $_GET['phone'];
					$data['status'] = $_GET['status'];
					$data['company_admin'] = $_GET['company_admin'];
					$data['fk_empresa'] = $companyCode;
					$data['group_id'] = $_GET['group_id'];
					
					$objUsers->insertUser($data, $_GET['id'], $custom_where);
					
					require_once $arrIni['base'].'framework/email/email.php' ;
					
					$to = "\"".$_GET['first_name'].' '.$_GET['last_name']."\" <".$_GET['email'].">";
					
					SendEmail($to,$_GET['username'],$_GET['password'], $_GET['first_name'].' '.$_GET['last_name']);

					echo "Creation successful";
					
				}
				
			break;
 
				
			case "edit":
 
				$companyCode = $objUsers->userCompany();
				
				$custom_where = " AND fk_empresa = $companyCode";
				
				$data['password'] = $_GET['password'];
				$data['first_name'] = $_GET['first_name'];
				$data['last_name'] = $_GET['last_name'];
				$data['email'] = $_GET['email'];
				$data['phone'] = $_GET['phone'];
				$data['status'] = $_GET['status'];
				$data['company_admin'] = $_GET['company_admin'];
				$data['group_id'] = $_GET['group_id'];
				
				$objUsers->updateUser($data, $_GET['id'], $custom_where);
				
			    echo "Record updated...";
			 
				break;
			}
		
		ConnectionFactory::close();
	}

?>