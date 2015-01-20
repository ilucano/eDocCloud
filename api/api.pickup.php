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
function GetBoxCompany($strBox, $token) {
	//Can query database and any other complex operation
	//return validToken($token);
	
	$isValid = validToken($token);
	
	//return $isValid;
	
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
		//$barcode = substr($barcode,6);
		$qry = "SELECT * FROM pickup WHERE fk_barcode = '201431".$strBox."'";
		$res = mysql_query($qry);
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
		//		if ($row['ip_addr']<>'') {
					// The user needs IP Control
					// ToDo: Not implemented
					//return "capoche";
		//		} else {
					//$varRet = genToken($row["apikey"], $row["fk_empresa"], $_SERVER['REMOTE_ADDR']);
					return $row["fk_company"];
		//		}
			}
		}
	} else {
		return "-1";
	}
	
}

function ChangeStatus($strBox, $strStatus, $userid, $token) {	
	$isValid = validToken($token);
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
		
		$strQry = "SELECT * FROM workflow WHERE wf_id = ".$strBox." ;";
		$res = mysql_query($strQry);
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
				$qry = "INSERT INTO wf_history (wf_id, fk_status, created, modify, created_by, modify_by) VALUES ('".$row['wf_id']."',".$row['fk_status'].",'".$row['created']."','".$row['modify']."',".$row['created_by'].",".$row['modify_by'].");";
				$res=mysql_query($qry) 
				or die("-1");
			}
		}

		$qry = "UPDATE workflow SET fk_status = ".$strStatus.", created = '".date("Y-m-d G:i:s")."', modify = '".date("Y-m-d G:i:s")."', created_by = ".$userid.", modify_by = ".$userid." WHERE wf_id = ".$strBox.";";
		$res=mysql_query($qry) 
			or die("-1");
		return "0";
	} else {
		return "-1";
	}
	
}

function GetBoxbyStatus($strStatus, $token) {
	//Can query database and any other complex operation
	
	$isValid = validToken($token);
		
	//$oRet[0] = array('fk_order'=>"test", 'barcode'=>"loko");
	//$oRet[1] = array('fk_order'=>"test1", 'barcode'=>"loko");
	
	//return $oRet;	
	
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
		//$barcode = substr($barcode,6);
		$qry = "SELECT * FROM workflow WHERE fk_status = ".$strStatus;
		$res = mysql_query($qry);
		
		$num_rows = mysql_num_rows($res);
    		
		//make a generic array of the size of our result set.
		$oReturn[$num_rows];
		$lCounter = 0;
		
		//$oReturn[0] = array('fk_order'=>$qry, 'barcode'=>$num_rows);
		//return $oReturn;
		if ($num_rows>0) {
			while ($row = mysql_fetch_array($res)) {
				$oReturn[$lCounter] = array('fk_order'=>$row['row_id'], 'barcode'=>$row['wf_id']);
				$lCounter = $lCounter+1;
			}
		}
		return $oReturn;
	} else {
		$oReturn[0] = array('fk_order'=>'-1', 'barcode'=>"Token Error");
		return $oReturn;
	}
	
}
 
	$server = new soap_server();
	$server->configureWSDL("Pickup", "urn:pickup");	
 
 	$server->register("GetBoxbyStatus",// method name
        array("strStatus" => "xsd:string", "token" => "xsd:string"),// input parameter - nothing!
        array("return" => "tns:BoxArray"),// output - object of type MyTableArray.
        "urn:pickupwsdl",
       	"urn:pickupwsdl#GetBoxbyStatus",
        "rpc",// style.. remote procedure call
        false,// use of the call
        "Get all boxes on status."// documentation for people who hook into your service.
    );
	
 // complex types are like 'struct' in C#.... it's a way to bind an object with different properties and variables together    
	$server->wsdl->addComplexType(
		'BoxData', // the type's name
		'complexType', // yes.. indeed it is a complex type.
		'struct', // php it's a structure. (only other option is array) 
		'all', // compositor.. 
		'',// no restriction
		array(
			'fk_order' => array('name'=>'fk_order','type'=>'xsd:string'),
			'barcode' => array('name'=>'barcode','type'=>'xsd:string') //,
			//'fk_box' => array('name'=>'fk_box','type'=>'xsd:string')
		)// the elements of the structure.
	);
    
	// Here we need to make another complex type of our last complex type.. but now an array!
	$server->wsdl->addComplexType(
		'BoxArray',//glorious name
		'complexType',// not a simpletype for sure!
		'array',// oh we are an array now!
		'',// bah. blank
		'SOAP-ENC:Array',
		array(),// our element is an array.
		array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:BoxData[]')),//the attributes of our array.
		'tns:BoxData'// what type of array is this?  Oh it's an array of mytable data
	);
	
 
 	//$server->configureWSDL("GetBoxCompany", "urn:pickup");
	
	
	//Register web service function so that clients can access
	$server->register("GetBoxCompany",
	array("strBox" => "xsd:string", "token" => "xsd:string"),
	array("return" => "xsd:string"),
	"urn:pickupwsdl",
	"urn:pickupwsdl#GetBoxCompany",
	"rpc",
	"encoded",
	"Get Company for Box");
	
	
	//$server->configureWSDL("ChangeStatus", "urn:pickup");
	$server->register("ChangeStatus",
		array("strBox" => "xsd:string", "strStatus" => "xsd:string", "userid" => "xsd:string", "token" => "xsd:string"),
		array("return" => "xsd:string"),
		"urn:pickupwsdl",
		"urn:pickupwsdl#ChangeStatus",
		"rpc",
		"encoded",
		"Change status of a Box"
	);
	
	$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($POST_DATA);
?>