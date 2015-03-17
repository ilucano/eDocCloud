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

function GetOrders($strCompany, $token) {
	//Can query database and any other complex operation
	
	$isValid = validToken($token);
		
	//$oRet[0] = array('fk_order'=>"test", 'barcode'=>"loko");
	//$oRet[1] = array('fk_order'=>"test1", 'barcode'=>"loko");
	
	//return $oRet;	
	
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
		//$barcode = substr($barcode,6);
		$qry = "SELECT * FROM objects WHERE fk_obj_type = 1 AND fk_company = '".$strCompany."'";
		$res = mysql_query($qry);
		
		$num_rows = mysql_num_rows($res);
    		
		//make a generic array of the size of our result set.
		$oReturn[$num_rows];
		$lCounter = 0;
		
		//$oReturn[0] = array('fk_order'=>$qry, 'barcode'=>$num_rows);
		//return $oReturn;
		if ($num_rows>0) {
			while ($row = mysql_fetch_array($res)) {
				$oReturn[$lCounter] = array('row_id'=>$row['row_id'], 'wf_id'=>$row['wf_id']);
				$lCounter = $lCounter+1;
			}
		} else {
			$oReturn[0] = array('row_id'=>'-1', 'wf_id'=>"No Results");
		}
		return $oReturn;
	} else {
		$oReturn[0] = array('row_id'=>'-1', 'wf_id'=>"Token Error");
		return $oReturn;
	}
	
}

 
	$server = new soap_server();
	$server->configureWSDL("Orders", "urn:orders");	
 
 	$server->register("GetOrders",// method name
        array("strCompany" => "xsd:string", "token" => "xsd:string"),// input parameter - nothing!
        array("return" => "tns:BoxArray"),// output - object of type MyTableArray.
        "urn:orderswsdl",
       	"urn:orderswsdl#GetOrders",
        "rpc",// style.. remote procedure call
        false,// use of the call
        "Get orders for the specified company."// documentation for people who hook into your service.
    );
	
	
 	// complex types are like 'struct' in C#.... it's a way to bind an object with different properties and variables together    
	$server->wsdl->addComplexType(
		'ObjectData', // the type's name
		'complexType', // yes.. indeed it is a complex type.
		'struct', // php it's a structure. (only other option is array) 
		'all', // compositor.. 
		'',// no restriction
		array(
			'row_id' => array('name'=>'row_id','type'=>'xsd:integer'),
			'fk_obj_type' => array('name'=>'fk_obj_type','type'=>'xsd:integer'),
			'fk_company' => array('name'=>'fk_company','type'=>'xsd:integer'),
			'f_code' => array('name'=>'f_code','type'=>'xsd:string'),
			'f_name' => array('name'=>'f_name','type'=>'xsd:string'),
			'fk_parent' => array('name'=>'fk_parent','type'=>'xsd:integer'),
			'qty' => array('name'=>'qty','type'=>'xsd:integer'),
			'fk_status' => array('name'=>'fk_status','type'=>'xsd:integer'),
			'invoiced' => array('name'=>'invoiced','type'=>'xsd:string')
			
		)// the elements of the structure.
	);
    
	// Here we need to make another complex type of our last complex type.. but now an array!
	$server->wsdl->addComplexType(
		'ObjectArray',//glorious name
		'complexType',// not a simpletype for sure!
		'array',// oh we are an array now!
		'',// bah. blank
		'SOAP-ENC:Array',
		array(),// our element is an array.
		array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:ObjectData[]')),//the attributes of our array.
		'tns:ObjectData'// what type of array is this?  Oh it's an array of mytable data
	);
	
	
	$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($POST_DATA);
?>