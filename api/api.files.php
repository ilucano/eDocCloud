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

function GetFiles($strParent, $token) {
	//Can query database and any other complex operation
	
	$isValid = validToken($token);
		
	//$oRet[0] = array('fk_order'=>"test", 'barcode'=>"loko");
	//$oRet[1] = array('fk_order'=>"test1", 'barcode'=>"loko");
	
	//return $oRet;	
	
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
		//$barcode = substr($barcode,6);
		$qry = "SELECT * FROM files WHERE parent_id =  ".$strParent;
		$res = mysql_query($qry);
		
		$num_rows = mysql_num_rows($res);
    		
		//make a generic array of the size of our result set.
		$oReturn[$num_rows];
		$lCounter = 0;
		
		//$oReturn[0] = array('fk_order'=>$qry, 'barcode'=>$num_rows);
		//return $oReturn;
		if ($num_rows>0) {
			while ($row = mysql_fetch_array($res)) {
				$oReturn[$lCounter] = array('row_id'=>$row['row_id'], 'filename'=>$row['filename'], 'pages'=>$row['pages'], 'filesize'=>$row['filesize'], 'fk_empresa'=>$row['fk_empresa'], 'parent_id'=>$row['parent_id'], 'path'=>$row['path']);
				$lCounter = $lCounter+1;
			}
		} else {
			$oReturn[0] = array('row_id'=>'-1', 'f_name'=>"No Results");
		}
		return $oReturn;
	} else {
		$oReturn[0] = array('row_id'=>'-1', 'f_name'=>"Token Error");
		return $oReturn;
	}
	
}


 
	$server = new soap_server();
	$server->configureWSDL("Files", "urn:files");	
 
 	$server->register("GetFiles",// method name
        array("strParent" => "xsd:string", "token" => "xsd:string"),// input parameter - nothing!
        array("return" => "tns:FilesArray"),// output - object of type MyTableArray.
        "urn:fileswsdl",
       	"urn:fileswsdl#GetFiles",
        "rpc",// style.. remote procedure call
        false,// use of the call
        "Get orders for the specified company."// documentation for people who hook into your service.
    );
	
	
 	// complex types are like 'struct' in C#.... it's a way to bind an object with different properties and variables together    
	$server->wsdl->addComplexType(
		'FilesData', // the type's name
		'complexType', // yes.. indeed it is a complex type.
		'struct', // php it's a structure. (only other option is array) 
		'all', // compositor.. 
		'',// no restriction
		array(
			'row_id' => array('name'=>'row_id','type'=>'xsd:integer'),
			'filename' => array('name'=>'filename','type'=>'xsd:string'),
			'pages' => array('name'=>'pages','type'=>'xsd:integer'),
			'filesize' => array('name'=>'filesize','type'=>'xsd:integer'),
			'fk_empresa' => array('name'=>'fk_empresa','type'=>'xsd:integer'),
			'parent_id' => array('name'=>'parent_id','type'=>'xsd:integer'),
			'path' => array('name'=>'path','type'=>'xsd:string')
			
		)// the elements of the structure.
	);
    
	// Here we need to make another complex type of our last complex type.. but now an array!
	$server->wsdl->addComplexType(
		'FilesArray',//glorious name
		'complexType',// not a simpletype for sure!
		'array',// oh we are an array now!
		'',// bah. blank
		'SOAP-ENC:Array',
		array(),// our element is an array.
		array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:FilesData[]')),//the attributes of our array.
		'tns:FilesData'// what type of array is this?  Oh it's an array of mytable data
	);
	
	
	$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($POST_DATA);
?>