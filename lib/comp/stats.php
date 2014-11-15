<?php

require_once('lib/db/db.php');

function GetStats($intCtd) {
	$antes = '<tr><td><table><thead><tr><th width="45%">Order</th><th width="15%">Order Date</th><th width="15%">Status</th><th width="10%">Pages</th><th width="15%">Cost</th></tr></thead><tbody>';
	$despues = '</tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT T1.row_id as row_id, T1.f_code as code, T1.f_name as name, T1.qty, T1.creation, T2.status as status, (T1.qty * T1.ppc) AS price FROM objects T1 INNER JOIN ordstatus T2 ON T2.row_id = T1.fk_status WHERE T1.fk_company = ".$_SESSION['CoCo']." and T1.fk_obj_type = 1 ORDER BY T1.row_id ASC LIMIT 0,".$intCtd;
	
	//$qry = "SELECT T1.row_id as row_id, T1.f_code as code, T1.f_name as name, T1.creation, T2.status as status FROM objects T1 INNER JOIN ordstatus T2 ON T2.row_id = T1.fk_status WHERE T1.fk_company = ".$_SESSION['CoCo']." and T1.fk_obj_type = 1 ORDER BY row_id ASC";
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	$iCnt = 1;
	
	echo $antes;
	if (mysql_num_rows($res)) {
		//echo $antes;
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']=="") {
				
				echo "You don't have orders at this time";
			//	echo $despues;
			} else {
				
				echo "<tr><td width=\"180\">";
				
				echo "<dl class=\"accordion\" data-accordion><dd class=\"accordion-navigation\">";
				echo "<a href=\"#panel".$iCnt."\">".$row['code']." / ".$row['name']."</a>";
				
				$qry2 = "SELECT * FROM invoices WHERE fk_order = ".$row['row_id'];
	
				mysql_query("SET NAMES UTF8");
				$res2 = mysql_query($qry2);
				
				echo "<div id=\"panel".$iCnt."\" class=\"content\">";
				$iCnt = $iCnt + 1;
				if (mysql_num_rows($res2)) {
					while ($row2 = mysql_fetch_array($res2)) {
						//<a href=\"lib/data/file.download.php?fileid=".$row['row_id']."\" target=\"_blank\">
						echo "<a href=\"lib/data/invoice.download.php?fileid=".$row2['row_id']."\" target=\"_new\">".$row2['invoice']."</a>";
					}
    				
				}
				echo "</div>";
				echo "</dd></dl>";
				
				echo "</td><td>".substr($row['creation'],0,10)."</td><td>".$row['status']."</td>";
				echo "<td>".$row['qty']."</td><td>";
				
				setlocale(LC_MONETARY, 'en_US');
				echo money_format('%i', $row['price']) . "\n";
				echo "</td></tr>";
				
				
			}
		}
	} else {
		//echo $antes;
		echo "You don't have notifications at this time";
		//echo $despues;
	}
	echo $despues;
	
	ConnectionFactory::close();
}
?>