<?php

session_start();

require_once '/var/www/html/config.php';
require_once $arrIni['base'].'framework/nusoap/nusoap.php';
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

function GetCompanies($token) {
	//Can query database and any other complex operation
	
	$isValid = validToken($token);
		
	//$oRet[0] = array('fk_order'=>"test", 'barcode'=>"loko");
	//$oRet[1] = array('fk_order'=>"test1", 'barcode'=>"loko");
	
	//return $oRet;	
	
	if ($isValid==0) {
		$con = ConnectionFactory::getConnection();
		//$barcode = substr($barcode,6);
		$qry = "SELECT * FROM companies;";
		$res = mysql_query($qry);
		
		$num_rows = mysql_num_rows($res);
    		
		//make a generic array of the size of our result set.
		$oReturn[$num_rows];
		$lCounter = 0;
		
		/*'row_id' => array('name'=>'row_id','type'=>'xsd:integer'),
		'company_name' => array('name'=>'company_name','type'=>'xsd:string'),
		'company_address1' => array('name'=>'company_address1','type'=>'xsd:string'),
		'company_address2' => array('name'=>'company_address2','type'=>'xsd:string'),
		'company_zip' => array('name'=>'company_zip','type'=>'xsd:string'),
		'fk_admin' => array('name'=>'fk_admin','type'=>'xsd:string'),
		'company_phone' => array('name'=>'company_phone','type'=>'xsd:string'),
		'company_fax' => array('name'=>'company_fax','type'=>'xsd:string'),
		'company_email' => array('name'=>'company_email','type'=>'xsd:string'),
		'fk_terms' => array('name'=>'fk_terms','type'=>'xsd:string'),
		'creditlimit' => array('name'=>'creditlimit','type'=>'xsd:string')*/
			
		if ($num_rows>0) {
			while ($row = mysql_fetch_array($res)) {
				$oReturn[$lCounter] = array('row_id'=>$row['row_id'], 'company_name'=>$row['company_name'], 'company_address1'=>$row['company_address1'], 'company_address2'=>$row['company_address2'], 'company_zip'=>$row['company_zip'], 'fk_admin'=>$row['fk_admin'], 'company_phone'=>$row['company_phone'], 'company_fax'=>$row['company_fax'], 'company_email'=>$row['company_email'], 'fk_terms'=>$row['fk_terms'], 'creditlimit'=>$row['creditlimit']);
				$lCounter = $lCounter+1;
			}
		}
		return $oReturn;
	} else {
		$oReturn[0] = array('row_id'=>'-1', 'company_name'=>"Token Error");
		return $oReturn;
	}
	
}
 
	$server = new soap_server();
	$server->configureWSDL("Company", "urn:company");	
 
 	$server->register("GetCompanies",// method name
        array("token" => "xsd:string"),// input parameter - nothing!
        array("return" => "tns:CompanyTable"),// output - object of type MyTableArray.
        "urn:pickupwsdl",
       	"urn:pickupwsdl#GetBoxbyStatus",
        "rpc",// style.. remote procedure call
        false,// use of the call
        "Get all boxes on status."// documentation for people who hook into your service.
    );
	
 // complex types are like 'struct' in C#.... it's a way to bind an object with different properties and variables together    
	$server->wsdl->addComplexType(
		'CompanyType', // the type's name
		'complexType', // yes.. indeed it is a complex type.
		'struct', // php it's a structure. (only other option is array) 
		'all', // compositor.. 
		'',// no restriction
		array(
			'row_id' => array('name'=>'row_id','type'=>'xsd:integer'),
			'company_name' => array('name'=>'company_name','type'=>'xsd:string'),
			'company_address1' => array('name'=>'company_address1','type'=>'xsd:string'),
			'company_address2' => array('name'=>'company_address2','type'=>'xsd:string'),
			'company_zip' => array('name'=>'company_zip','type'=>'xsd:string'),
			'fk_admin' => array('name'=>'fk_admin','type'=>'xsd:string'),
			'company_phone' => array('name'=>'company_phone','type'=>'xsd:string'),
			'company_fax' => array('name'=>'company_fax','type'=>'xsd:string'),
			'company_email' => array('name'=>'company_email','type'=>'xsd:string'),
			'fk_terms' => array('name'=>'fk_terms','type'=>'xsd:string'),
			'creditlimit' => array('name'=>'creditlimit','type'=>'xsd:string')
			//'fk_box' => array('name'=>'fk_box','type'=>'xsd:string')
		)// the elements of the structure.
	);
    
	// Here we need to make another complex type of our last complex type.. but now an array!
	$server->wsdl->addComplexType(
		'CompanyTable',//glorious name
		'complexType',// not a simpletype for sure!
		'array',// oh we are an array now!
		'',// bah. blank
		'SOAP-ENC:Array',
		array(),// our element is an array.
		array(array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:CompanyType[]')),//the attributes of our array.
		'tns:CompanyType'// what type of array is this?  Oh it's an array of mytable data
	);
	
	
	$POST_DATA = isset($GLOBALS['HTTP_RAW_POST_DATA'])? $GLOBALS['HTTP_RAW_POST_DATA'] : '';
	$server->service($POST_DATA);
?>