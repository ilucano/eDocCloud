<?php

session_start();

require_once '/var/www/html/config.php';
require_once $arrIni['base'].'framework/nusoap/nusoap.php';
//require_once $arrIni['base'].'lib/db/dbConn.php';
require_once $arrIni['base'].'lib/db/db.php' ;

//Perform client authentication

//Unauthenticated clients are nottokenallowed to access functionality
function validToken($token) {
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT * FROM apitoken WHERE token = '".$token."' AND ip_addr = '".$_SERVER['REMOTE_ADDR']."' AND dateto > NOW();";
	$res = mysql_query($qry);
	
	$retVal = "-1";
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			$retVal = "0";
			$fecha = date("Y-m-d H:i:s",strtotime('+2 hours'));
			//date_add($fecha, date_interval_create_from_date_string('2 hours'));
			$qry = "UPDATE apitoken SET dateto = '".$fecha."' WHERE token = '".$token."';";
			//return $qry;
			$res=mysql_query($qry) 
				or die("-1");
		}
	}
	return $retVal;
}
 
// Authentication Token
// Logic of the web service
function GetSeparator($strBarcode, $strCompany, $token) {
	//Can query database and any other complex operation
	//return validToken($token);
	
	$isValid = validToken($token);
	
	//return $isValid;
	
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
	
		$qry = "SELECT * FROM separators WHERE row_id = ".$strBarcode." AND fk_customer IN (0,".$strCompany.") ORDER BY fk_customer ASC";
		$res = mysql_query($qry);
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
		//		if ($row['ip_addr']<>'') {
					// The user needs IP Control
					// ToDo: Not implemented
					//return "capoche";
		//		} else {
					//$varRet = genToken($row["apikey"], $row["fk_empresa"], $_SERVER['REMOTE_ADDR']);
					return $row["texto"];
		//		}
			}
		}
	} else {
		return "-1";
	}
	
}
 
	$server = new soap_server();
	$server->configureWSDL("GetSeparator", "urn:separator");
 
	//Register web service function so that clients can access
	$server->register("GetSeparator",
	array("strBarcode" => "xsd:string", "strCompany" => "xsd:string", "token" => "xsd:string"),
	array("return" => "xsd:string"),
	"urn:separator",
	"urn:separator#GetSeparator",
	"rpc",
	"encoded",
	"Get Separator Name");
 
	$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($POST_DATA);
?>