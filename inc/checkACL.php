<?php

session_start();

require_once '/var/www/html/config.php';
require_once $arrIni['base'].'lib/db/db.php' ;
require_once $arrIni['base'].'lib/db/dbConn.php' ;

if (!(isset($_SESSION['Vusername']) && $_SESSION['Vusername'] != '')) {

header ("Location: ../index.php");

}




if ($_SESSION['VisAdmin']=='X') {
	// Si es admin de sistema ve todo
	$arrPerm['admin'] = 'X';
	$arrPerm['view'] = 'X';
	$arrPerm['create'] = 'X';
	$arrPerm['edit'] = 'X';
	$arrPerm['delete'] = 'X';
	
} elseif ($_SESSION['Vcadm']=='X') {
	// Si es admin de empresa ve todo de la empresa
	$arrPerm['admin'] = '';
	$arrPerm['view'] = 'X';
	$arrPerm['create'] = 'X';
	$arrPerm['edit'] = 'X';
	$arrPerm['delete'] = '';

} else {
	// Usuario Normal, busco permisos
	$arrPerm['admin'] = '';
	$arrPerm['view'] = '';
	$arrPerm['create'] = '';
	$arrPerm['edit'] = '';
	$arrPerm['delete'] = '';
}


//modules permission


$permissionList = array('application' 	=> array('label' => 'Application',
											   'code' => array('main' => 'Main'),
											   'script' => array('main' => '/main.php')
											  ),
						'workflow' 		=> array('label' => 'Workflow',
												'code' => array('pickup' => 'Pickup',
																'preparation' => 'Preparation',
																'scan' => 'Scan',
																'qa' => 'QA',
																'ocr' => 'OCR'),
												'script' => array('pickup' => '/admin/wf_pick.php',
																'preparation' => '/admin/prep.php',
																'scan' => '/admin/scan.php',
																'qa' => '/admin/qa.php',
																'ocr' => '/admin/ocr.php')
										   ),
						'reports' 		=> array('label' => 'Reports',
												 'code' => array('all_boxes'  => 'All Boxes',
																 'group_by_status' => 'Group By Status'),
												 'script' => array('all_boxes'  => '/admin/inproc.php',
																 'group_by_status' => '/admin/report01.php')
												),
						'admin_menu'	=> array('label' => 'Admin Menu',
												 'code' => array('home' => 'Home',
																 'company' => 'Company',
																 'users' => 'Users',
																 'groups' => 'Groups',
																 'orders' => 'Orders',
																 'pickup' => 'Pickup',
																 'box'	=> 'Box',
																 'chart' => 'Chart',
																 'file' => 'File',
																 'barcode' => 'Barcode'),
												 'script' => array('home' => '/admin/main.php',
																 'company' => '/admin/company.php',
																 'users' => '/admin/users.php',
																 'groups' => '/admin/groups.php',
																 'orders' => '/admin/orders.php',
																 'pickup' => '/admin/pickup.php',
																 'box'	=> '/admin/box.php',
																 'chart' => '/admin/chart.php',
																 'file' => '/admin/file.php',
																 'barcode' => '/admin/barcode.php'),
												 
												 ),
						'user_menu'		=> array('label' => 'User Menu',
												 'code' => array('home' => 'Home',
																 'orders' => 'Orders',
																 'search' => 'Search',
																 'change_password' => 'Change Password'),
												  'script' => array('home' => '/main.php',
																'orders' => '/orders.php',
																'search' => '/search.php',
																'change_password' => '/chgpwd.php')
																										
												 )
						
						);

						

$allowed = CheckPagePermission();

if($allowed == false)
{
  header("Location: ../noperm.php");
  exit();
}

function ComboEmpresa() {
	
	$strRet = "";
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT * FROM companies;";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>Company<select name="fk_empresa">';
	$res = mysql_query($qry);
	
	if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']==$_SESSION['CoCo']) {
				$strRet = $strRet.'<option '.$isDis.' selected value="'.$row['row_id'].'">'.$row['company_name'].'</option>';
			} else {
				if ($isDis!="disabled") {
					$strRet = $strRet.'<option value="'.$row['row_id'].'">'.$row['company_name'].'</option>';
				}
			}
		}
	}
	
	$strRet = $strRet.'</select></label>';
	
    ConnectionFactory::close();
	
	
	return $strRet;
}

function ComboEmpresaSel($vId) {
	
	$strRet = "";
	
	$conE = ConnectionFactory::getConnection();
	
	$qryE = "SELECT * FROM companies;";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>Company<select name="fk_empresa">';
	$resE = mysql_query($qryE);
	
	if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
	
	if (mysql_num_rows($resE)) {
		while ($rowE = mysql_fetch_array($resE)) {
			if ($rowE['row_id']==$vId) {
				$strRet = $strRet.'<option '.$isDis.' selected value="'.$rowE['row_id'].'">'.$rowE['company_name'].'</option>';
			} else {
				if ($isDis!="disabled") {
					$strRet = $strRet.'<option value="'.$rowE['row_id'].'">'.$rowE['company_name'].'</option>';
				}
			}
		}
	}
	
	$strRet = $strRet.'</select></label>';
	
    ConnectionFactory::close();
	
	
	return $strRet;
}

function ComboEmpresaSelD($vId) {
	
	$strRet = "";
	
	$conE = ConnectionFactory::getConnection();
	
	$qryE = "SELECT * FROM companies;";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>Company<select name="fk_empresa" disabled>';
	$resE = mysql_query($qryE);
	
	if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
	
	if (mysql_num_rows($resE)) {
		while ($rowE = mysql_fetch_array($resE)) {
			if ($rowE['row_id']==$vId) {
				$strRet = $strRet.'<option selected value="'.$rowE['row_id'].'">'.$rowE['company_name'].'</option>';
			} else {
				if ($isDis!="disabled") {
					$strRet = $strRet.'<option value="'.$rowE['row_id'].'">'.$rowE['company_name'].'</option>';
				}
			}
		}
	}
	
	$strRet = $strRet.'</select></label>';
	
    ConnectionFactory::close();
	
	
	return $strRet;
}

function ComboCompanies($vName,$vId,$vDis) {
	
	$strRet = "";
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT * FROM companies;";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>Company<select name="'.$vName.'" '.$vDis.'>';
	$res = mysql_query($qry);
	
	if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			if ($vId=='') {
				$vNewId = $_SESSION['CoCo'];
			} else {
				$vNewId = $vId;
			}
			if ($row['row_id']==$vNewId) {
				$strRet = $strRet.'<option '.$isDis.' selected value="'.$row['row_id'].'">'.$row['company_name'].'</option>';
			} else {
				if ($isDis!="disabled") {
					$strRet = $strRet.'<option value="'.$row['row_id'].'">'.$row['company_name'].'</option>';
				}
			}
		}
	}
	
	$strRet = $strRet.'</select></label>';
	
    ConnectionFactory::close();
	
	
	return $strRet;
}

function ComboUsers($vName,$vId,$vDis) {
	
	$strRet = "";
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT * FROM users;";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>User<select name="'.$vName.'" '.$vDis.'>';
	$res = mysql_query($qry);
	
	if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			if ($vId=='') {
				$vNewId = $_SESSION['Vid'];
			} else {
				$vNewId = $vId;
			}
			if ($row['row_id']==$vNewId) {
				$strRet = $strRet.'<option '.$isDis.' selected value="'.$row['row_id'].'">'.$row['first_name'].' '.$row['last_name'].' ('.$row['username'].')'.'</option>';
			} else {
				if ($isDis!="disabled") {
					$strRet = $strRet.'<option value="'.$row['row_id'].'">'.$row['first_name'].' '.$row['last_name'].' ('.$row['username'].')'.'</option>';
				}
			}
		}
	}
	
	$strRet = $strRet.'</select></label>';
	
    ConnectionFactory::close();
	
	
	return $strRet;
}



function ComboYesNo($vName,$vDesc,$vId,$vDis) {
	
	$strRet = "";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>'.$vDesc.'<select name="'.$vName.'" '.$vDis.'>';
	
	if ($vId=="X") {
		$strRet = $strRet.'<option '.$isDis.' selected value="X">Yes</option>';
		$strRet = $strRet.'<option '.$isDis.' value="">No</option>';
	} else {
		$strRet = $strRet.'<option '.$isDis.' value="X">Yes</option>';
		$strRet = $strRet.'<option '.$isDis.' selected value="">No</option>';
	}

	$strRet = $strRet.'</select></label>';

	return $strRet;
}




function ComboGroups($vName,$vId,$vDis) {
	
	$strRet = "";
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT * FROM groups ORDER by nombre";
	
	// Inicio de la seleccion de Empresas
	$strRet = '<label>User Role<select name="'.$vName.'" '.$vDis.'><option value=""></option>';
	$res = mysql_query($qry);
	
	if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			 
			$vNewId = $vId;
		 
			if ($row['row_id']==$vNewId) {
				$strRet = $strRet.'<option '.$isDis.' selected value="'.$row['row_id'].'">'.$row['nombre'].'</option>';
			} else {
				if ($isDis!="disabled") {
					$strRet = $strRet.'<option value="'.$row['row_id'].'">'.$row['nombre'].'</option>';
				}
			}
		}
	}
	
	$strRet = $strRet.'</select></label>';
	
    ConnectionFactory::close();
	
	
	return $strRet;
}

function GetUserPermission()
{
	
		
	if (!(isset($_SESSION['Vusername']) && $_SESSION['Vusername'] != '')) {
		return array();
	}
	
	
	$pdocon = NConnectionFactory::getConnection();
	 
	$stmt = $pdocon->prepare('SELECT group_permission FROM users  JOIN groups
								ON users.group_id = groups.row_id
								WHERE username = :username
								LIMIT 1');
	
	$bind_array = array(':username' => $_SESSION['Vusername']);
    $stmt->execute($bind_array);
    
	 
	while($row = $stmt->fetch()) {
		
       $group_permission = json_decode($row['group_permission'], true);
    }
    
	return $group_permission;
	
}



function CheckPagePermission()
{
	global $permissionList;
	
	
    $group_permission = GetUserPermission();
	
 
	foreach($permissionList as $key => $option)
	{
		foreach($option['script'] as $code => $scriptname)
		{   
			if($scriptname == $_SERVER['SCRIPT_NAME']) {
				 
				if($group_permission[$key][$code] == '1')
			    {
					return true;
				}
				else {
					return false;
				}
				 
			}
		}
	}
	
	//by default allow if page not defined in permission list
	return true;

}

require_once $arrIni['base'].'inc/activity_logs.class.php';

$ActivityLogs = new Activity_Logs();
$ActivityLogs->log();
?>