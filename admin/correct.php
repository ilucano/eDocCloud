<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	require_once $arrIni['base'].'inc/checkACL.php'; 
	require_once $arrIni['base'].'lib/db/db.php';
	
	$arrCharts[1];
	$arrBoxes[1];
	$int = 0;
	
	// Primero busco los chart que estan finalizados y sin page
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT * FROM files WHERE path LIKE '/opt/eDocCloud/files%'";
	
	//mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
			$newPath = $row['path'];
			$newPath = str_replace("/opt/eDocCloud/files/","",$newPath);
			echo "Change: ".$row['path']." to ".$newPath."<br>";
			$qry2 = "UPDATE files SET path = '" . $newPath . "' WHERE row_id = ".$row['row_id'];
			//echo $qry2;
			$res2 = mysql_query($qry2)
					or die("-1");
			//$arrBoxes[$int] = $row['fk_parent'];
			//echo "Id: ".$int."|RowId: ".$arrCharts[$int];
			$int = $int + 1;
			
		}
		echo $int;
	}
	$intCnt = 0;
	
	
	ConnectionFactory::close();

?> 