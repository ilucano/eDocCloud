<?php

require_once('/var/www/html/lib/db/db.php');

function GetName($itemid) {
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT f_code as code, f_name as name FROM objects WHERE row_id = ".$itemid;
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
				if ($row['code']!=""&&$row['name']!="") {
					$screen = $row['code'];
				} else if ($row['code']=="") {
					$screen = $row['name'];
				} else if ($row['name']=="") {
					$screen = $row['code'];
				}
			return $screen;
		}
	}
	ConnectionFactory::close();
}


function GetAuth($user,$empresa,$objid,$objtype) {
	// El object Type es 'OB' para objetos 'FI' para archivos
	$con = ConnectionFactory::getConnection();
	
	switch ($objtype) {
		
		case 'OB':
			$qry = "SELECT fk_company FROM objects WHERE row_id = ".$objid;
	//echo $qry;
			$res = mysql_query($qry);
			//echo "PASA".$objid;
			if ($res!="") {
				//echo "PASA".$objid;
				while ($row = mysql_fetch_array($res)) {
					if ($row['fk_company']==$empresa) {
						return 'true';
					} else {
						return 'false';
					}
				}
			} else {
				return 'false';	
			}
			
			break;
			
		case 'FI':
		
			$qry = "SELECT fk_empresa FROM files WHERE row_id = ".$objid;
	
			$res = mysql_query($qry);
			
			if ($res!="") {
				while ($row = mysql_fetch_array($res)) {
					if ($row['fk_empresa']==$empresa) {
						return 'true';
					} else {
						return 'false';
					}
				}
			} else {
				return 'false';	
			}
			
			break;
		
		default:
			return 'false';
			break;
		}
	
	ConnectionFactory::close();
}

?>