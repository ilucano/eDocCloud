<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'lib/db/db.php';
require_once $arrIni['base'].'lib/db/dbConn.php' ;

require_once $arrIni['base'].'inc/users.class.php';
require_once $arrIni['base'].'inc/filemarks.class.php';

session_start();

$action = $_GET['action'];


switch ($action) {
	
	case "update":
		
		$row_id = $_GET['id'];
	    $file_mark_id = $_GET['file_mark_id'];
		
		$con = NConnectionFactory::getConnection();
		
		$objUsers = new Users;
		
		$companyCode = $objUsers->userCompany();
		
		$query = "UPDATE `files` SET file_mark_id = :file_mark_id
		          WHERE row_id = :row_id AND fk_empresa = :fk_empresa LIMIT 1";
		
		$array_bind = array(':file_mark_id' => $file_mark_id,
							':row_id'	=> $row_id,
							':fk_empresa' => $companyCode);
		
		$stmt = $con->prepare($query);
						
		$stmt->execute($array_bind);

		$objFilemarks = new Filemarks;
		echo $objFilemarks->getLabelById($file_mark_id);
		
	break;
	
	
} 
  