<?php

session_start();

require_once '/var/www/html/config.php';
require_once $arrIni['base'].'framework/nusoap/nusoap.php';
//require_once $arrIni['base'].'lib/db/dbConn.php';
require_once $arrIni['base'].'lib/db/db.php' ;

//Perform client authentication

//Unauthenticated clients are not allowed to access functionality
function genToken($apikey, $company, $ip) {
	$con = ConnectionFactory::getConnection();
	$token = md5(uniqid(rand(), true).$apikey.$ip);
	
	$qry = "INSERT INTO apitoken (token, datefrom, dateto, fk_company, ip_addr) VALUES ('".$token."',NOW(),NOW(),".$company.",'".$ip."');";
	
	$res=mysql_query($qry) 
		or die("-1");
    return $token;
	//} else {
	//	return "-1";
	
}
 
// Authentication Token
// Logic of the web service
function GetToken($username, $password, $apikey) {
	//Can query database and any other complex operation
	$con = ConnectionFactory::getConnection();
	$qry = "SELECT * FROM apiauth WHERE username = '".$username."' AND password = '".$password."' AND apikey ='".$apikey."';";
	$res = mysql_query($qry);
	
	//return $qry;
	
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
	//		if ($row['ip_addr']<>'') {
				// The user needs IP Control
				// ToDo: Not implemented
				//return "capoche";
	//		} else {
				$varRet = genToken($row["apikey"], $row["fk_empresa"], $_SERVER['REMOTE_ADDR']);
				return $varRet;
	//		}
		}
	}
	return "noentra";
}
 
	$server = new soap_server();
	$server->configureWSDL("GetToken", "urn:authenticate");
 
	//Register web service function so that clients can access
	$server->register("GetToken",
	array("username" => "xsd:string", "password" => "xsd:string", "apikey" => "xsd:string"),
	array("return" => "xsd:string"),
	"urn:authenticate",
	"urn:authenticate#GetToken",
	"rpc",
	"encoded",
	"Retrieve token for valid connections");
 
	$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($POST_DATA);
?>