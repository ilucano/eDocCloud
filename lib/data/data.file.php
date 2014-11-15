<?
/*
	El File contiene la siguiente estructura:
		- filename	 		$arrChart(0)
		- creadate			$arrChart(1)
		- moddate			$arrChart(2)
		- pages				$arrChart(3)
		- filesize			$arrChart(4)
		- pdf_version		$arrChart(5)
		- fk_empresa		$arrChart(6)
		- parent_id			$arrChart(7)
		- texto				$arrChart(8)
		- path				$arrChart(9)
		
*/




function NewFile($arrFile) {
	//session_start();
	
	//require_once '/var/www/html/config.php';
	
	//require_once $arrIni['base'].'lib/db/db.php';

	$con = ConnectionFactory::getConnection();
	
	$arrFile[8] = mysql_real_escape_string($arrFile[8]);

	$qry = "INSERT INTO files (filename,creadate,moddate,pages,filesize,pdf_version,fk_empresa,parent_id,texto,path) VALUES";
	$qry = $qry." ('$arrFile[0]','$arrFile[1]','$arrFile[2]',$arrFile[3],$arrFile[4],'$arrFile[5]',$arrFile[6],$arrFile[7],'$arrFile[8]','$arrFile[9]')";
	//echo $qry;
	
	$res=mysql_query($qry)
	or die("-1");
	
	return mysql_insert_id();
	
	}

?>