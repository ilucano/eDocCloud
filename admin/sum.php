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
	
	$qry = "SELECT * FROM objects WHERE fk_obj_type = 3 AND fk_status = 5 AND qty = 0";
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
			
			$qry2 = "UPDATE objects SET qty = (SELECT SUM(pages) FROM files WHERE parent_id = ".$row['row_id'].") WHERE row_id = ".$row['row_id'];
			$res2 = mysql_query($qry2);
			$arrBoxes[$int] = $row['fk_parent'];
			//echo "Id: ".$int."|RowId: ".$arrCharts[$int];
			$int = $int + 1;
			
		}
	}
	$intCnt = 0;
	
	// Ahora paso a las cajas que traigo de los charts y las cierro
	foreach ($arrBoxes as $arr) {
		//echo $arr."<br>";
		
		$qry2 = "SELECT SUM(qty) as qty FROM objects WHERE fk_parent = ".$arr;
		$res2 = mysql_query($qry2);
	
		if ($res2!="") {
			while ($row2 = mysql_fetch_array($res2)) {
				$qry3 = "UPDATE objects SET qty = ".$row2['qty']." , fk_status = 5 WHERE row_id = ".$arr;
				$res3 = mysql_query($qry3);
			}
		}
	}
		
		
	// Por ultimo las ordenes pero no las cierro
	$qry = "SELECT * FROM objects WHERE fk_obj_type = 1 AND fk_status <> 5;";
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
	
			$qry2 = "SELECT SUM(qty) as qty FROM objects WHERE fk_parent = ".$row['row_id'];
			$res2 = mysql_query($qry2);
	
			if ($res2!="") {
				while ($row2 = mysql_fetch_array($res2)) {
					$qry3 = "UPDATE objects SET qty = ".$row2['qty']." , fk_status = 3 WHERE row_id = ".$row['row_id'];
					$res3 = mysql_query($qry3);
				}
			}
		}
	}
	
	ConnectionFactory::close();

?> 