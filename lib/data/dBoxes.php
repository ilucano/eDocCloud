<?php
session_start();
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'lib/db/db.php';

GetAllBoxes($_GET['ordid']);

function GetAllBoxes($ordid) {
	
	$antes = '<table><thead><tr><th>Your Boxes</th></tr></thead><tbody><tr><td><table><thead><tr><th width="30%">Box</th><th width="20%">Box Date</th><th width="20%">Status</th><th width="15%">Pages</th><th width="15%">Cost</th></tr></thead><tbody>';
	$despues = '</tbody></table></tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT T1.row_id as row_id, T1.qty as qty, (T3.ppc * T1.qty) as price, T1.f_code as code, T1.f_name as name, T1.creation, T2.status as status FROM objects T1 INNER JOIN ordstatus T2 ON T2.row_id = T1.fk_status INNER JOIN objects T3 ON T3.row_id = T1.fk_parent WHERE T1.fk_company = ".$_SESSION['CoCo']." and T1.fk_obj_type = 2 and T1.fk_parent = ".$ordid.' ORDER BY T1.f_code, T1.f_name ASC;';
	//echo $qry;
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	echo $antes;
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']=="") {
				echo "<tr><td>You don't have boxes at this time</td></tr>";
			} else {
				
				if ($row['code']!=""&&$row['name']!="") {
					$screen = $row['code']." / ".$row['name'];
				} else if ($row['code']=="") {
					$screen = $row['name'];
				} else if ($row['name']=="") {
					$screen = $row['code'];
				}
				
				$qryStatus = "SELECT T1.*, (T3.status) as estatus FROM workflow T1 INNER JOIN pickup T2 ON T2.fk_barcode = T1.wf_id INNER JOIN wf_status T3 ON T3.row_id = T1.fk_status WHERE T2.fk_box = ".$row['row_id'].";";
				
				$resSt = mysql_query($qryStatus);
				$elStatus = "FINISH";
				if ($resSt!="") {
					while ($rowSt = mysql_fetch_array($resSt)) {
						$elStatus = $rowSt['estatus'];
					}
				}
				
				//$row['status']
				
				echo "<tr><td width=\"120\"><a href=\"#\" link-type=\"box\" link-order=\"".$ordid."\" my-data-reveal-id=\"".$row['row_id']."\">".$screen."</a></td><td width=\"90\">".$row['creation']."</td><td width=\"100\">".$elStatus."</td>";
				echo "<td width=\"100\">".$row['qty']."</td><td width=\"100\">";
				
				setlocale(LC_MONETARY, 'en_US');
				echo money_format('%i', $row['price']) . "\n";
				echo "</td></tr>";
			}
		}
	} else {
		echo "<tr><td>";
		echo "You don't have boxes at this time</td></tr>";
	}
	echo $despues;
	
	ConnectionFactory::close();
}


require_once $arrIni['base'].'inc/activity_logs.class.php';

$ActivityLogs = new Activity_Logs();
$ActivityLogs->log();
