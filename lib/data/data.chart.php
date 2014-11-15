<?
/*
	El Chart contiene la siguiente estructura:
		- fk_object_type 	es fijo 3
		- fk_company		$arrChart(0)
		- f_code			$arrChart(1)
		- f_name			$arrChart(2)
		- fk_parent			$arrChart(3)
		- creation			$arrChart(4)
		- pickup			No se usa, a nivel Orden o Caja
		- scan				$arrChart(5)
		- quality			$arrChart(6)
		- return			No se usa, a nivel Orden o Caja
		- shred				No se usa, a nivel Orden o Caja
		- qty				Lo carga un Job
		- integration		No se usa por ahora
		- cPickup			No se usa, a nivel Orden o Caja
		- cScan				es fijo X
		- cReturn			No se usa, a nivel Orden o Caja
		- cShred			No se usa, a nivel Orden o Caja
		- fk_status			es fijo 3
		- ppc				Lo carga un Job

*/


function NewChart($arrChart) {
	echo "ENTRO";
	//session_start();
	
	//require_once '/var/www/html/config.php';
	
	//require_once $arrIni['base'].'lib/db/db.php';

	$fk_object_type = 3;
	$cScan = 'X';
	$fk_status = 3;
echo "ENTRO";
	$con = ConnectionFactory::getConnection();
	
	$qry = "INSERT INTO objects (fk_obj_type,fk_company,f_code,f_name,fk_parent,creation,cScan,fk_status) VALUES";
	$qry = $qry." ($fk_object_type,$arrChart[0],'$arrChart[1]','$arrChart[2]',$arrChart[3],'$arrChart[4]','$cScan',$fk_status)";
	//echo $arrChart[4];
	
	$res=mysql_query($qry)
	or die("-1");
	
	return mysql_insert_id();
	
	}

?>