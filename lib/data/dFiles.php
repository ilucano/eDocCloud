<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'lib/db/db.php';

session_start();

GetAllCharts($_GET['boxid'], $_GET['orderid']);

function GetAllCharts($boxid, $orderid) {
	
	$antes = '<table><thead><tr><th><a href="#" link-type="order" my-data-reveal-id="'.$orderid.'">Order '.GetName($orderid).'</a> > Your Charts in Box '.GetName($boxid).'</th></tr></thead><tbody><tr><td><table><thead><tr><th width="35%">Chart</th><th width="20%">Marks</th><th width="15%">Chart Date</th><th width="20%">Status</th><th width="10%">Pages</th></tr></thead><tbody>';
	$despues = '</tbody></table></tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT T1.row_id as row_id, T1.qty as qty, T1.f_code as code, T1.f_name as name, T1.creation, T2.status as status FROM objects T1 INNER JOIN ordstatus T2 ON T2.row_id = T1.fk_status WHERE T1.fk_company = ".$_SESSION['CoCo']." and T1.fk_obj_type = 3 and T1.fk_parent = ".$boxid.' ORDER BY f_code, f_name ASC';
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	echo $antes;
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']=="") {
				//echo $antes;
				echo "<tr><td>You don't have charts at this time</td></tr>";
				//echo $despues;
			} else {
				//echo $antes;
				
				if ($row['code']!=""&&$row['name']!="") {
					$screen = $row['code']." / ".$row['name'];
				} else if ($row['code']=="") {
					$screen = $row['name'];
				} else if ($row['name']=="") {
					$screen = $row['code'];
				}
				
				echo "<tr><td width=\"120\"><a href=\"#\" link-type=\"chart\" link-order=\"".$orderid."\" link-box=\"".$boxid."\" my-data-reveal-id=\"".$row['row_id']."\">".$screen."</a></td><td>";
				echo dropDownButton();
				echo "</td><td width=\"90\">".$row['creation']."</td><td width=\"100\">".$row['status']."</td>";
				echo "<td width=\"100\">".$row['qty'];
				//."</td><td width=\"100\">";
				
				//setlocale(LC_MONETARY, 'en_US');
				//echo money_format('%i', $row['price']) . "\n";
				echo "</td></tr>";
				//echo $despues;
			}
		}
	} else {
		//echo $antes;
		echo "<tr><td>";
		echo "You don't have charts at this time</td></tr>";
		//echo $despues;
	}
	echo $despues;
	ConnectionFactory::close();
}
 
function dropDownButton()
{
	
	return '<button href="#" data-dropdown="drop1" aria-controls="drop1" aria-expanded="false" class="button dropdown">Dropdown Button</button><br>
<ul id="drop1" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">
  <li><a href="#">This is a link</a></li>
  <li><a href="#">This is another</a></li>
  <li><a href="#">Yet another</a></li>
</ul>';
}

require_once $arrIni['base'].'inc/activity_logs.class.php';

$ActivityLogs = new Activity_Logs();
$ActivityLogs->log();
