<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';


require_once $arrIni['base'].'lib/db/db.php';

function GetAllOrders() {
	
	$antes = '<tr><td><table><thead><tr><th width="30%">Order</th><th width="25%">Order Date</th><th width="15%">Status</th><th width="15%">Pages</th><th width="15%">Cost</th></tr></thead><tbody>';
	$despues = '</tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT T1.row_id as row_id, T1.f_code as code, T1.f_name as name,T1.qty, T1.creation, T2.status as status, (T1.qty * T1.ppc) AS price FROM objects T1 INNER JOIN ordstatus T2 ON T2.row_id = T1.fk_status WHERE T1.fk_company = ".$_SESSION['CoCo']." and T1.fk_obj_type = 1 ORDER BY row_id ASC";
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	echo $antes;
	if (mysql_num_rows($res)) {
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']=="") {
				echo "You don't have orders at this time";
			} else {
				echo "<tr><td width=\"120\"><a href=\"#\" link-type=\"order\" data-reveal-id=\"".$row['row_id']."\">".$row['code']." / ".$row['name']."</a></td><td width=\"90\">".$row['creation']."</td><td width=\"100\">".$row['status']."</td>";
				echo "<td width=\"100\">".$row['qty']."</td><td width=\"100\">";
				
				setlocale(LC_MONETARY, 'en_US');
				echo money_format('%i', $row['price']) . "\n";
				echo "</td></tr>";
			}
		}
	} else {
		echo "You don't have notifications at this time";
	}
	echo $despues;
	
	ConnectionFactory::close();
}
?>