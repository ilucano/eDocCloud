<?php

session_start();

require_once '/var/www/html/config.php';
require_once $arrIni['base'].'lib/db/db.php' ;

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
echo "<pre>";
print_r($_SERVER);

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


?>