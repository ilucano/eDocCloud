<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';
require_once($arrIni['base'].'lib/db/db.php');

session_start();

GetAllFiles($_GET['chartid'], $_GET['boxid'], $_GET['orderid']);

function GetAllFiles($chartid, $boxid, $orderid) {
	
	$antes = '<table><thead><tr><th><a href="#" link-type="order" my-data-reveal-id="'.$orderid.'">Order '.GetName($orderid).'</a> > <a href="#" link-type="box" link-order="'.$orderid.'" link-box="'.$boxid.'" data-reveal-id="'.$boxid.'">Box '.GetName($boxid).'</a> > Your Files in Chart '.GetName($chartid).'</th></tr></thead><tbody><tr><td><table><thead><tr><th width="30%">Filename</th><th width="25%">Creation</th><th width="15%">Changed</th><th width="15%">Pages</th><th width="15%">Size</th></tr></thead><tbody>';
	
	$despues = '</tbody></table></tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT row_id, filename, creadate, moddate, pages, filesize FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." and parent_id = ".$chartid.' ORDER BY filename ASC;';
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qry);
	
	echo $antes;
	if ($res!="") {
		while ($row = mysql_fetch_array($res)) {
			if ($row['row_id']=="") {
				//echo $antes;
				echo "<tr><td>You don't have files at this time</td></tr>";
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
				
				echo "<tr><td width=\"120\"><a href=\"lib/data/file.download.php?fileid=".$row['row_id']."\" target=\"_blank\">".$row['filename']."</a></td><td width=\"90\">".$row['creadate']."</td><td width=\"100\">".$row['moddate']."</td>";
				echo "<td width=\"100\">".$row['pages']."</td><td width=\"100\">";
				$mbytes = number_format($row['filesize'] / 1024 / 1024,2);
				echo $mbytes." Mb";
				echo "</td></tr>";
				//echo $despues;
			}
		}
	} else {
		//echo $antes;
		echo "<tr><td>";
		echo "You don't have files at this time</td></tr>";
		//echo $despues;
	}
	echo $despues;
	ConnectionFactory::close();
}
?>