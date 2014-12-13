<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/general.php';
require_once($arrIni['base'].'lib/db/db.php');
require_once $arrIni['base'].'inc/users.class.php';
require_once $arrIni['base'].'inc/filemarks.class.php';
session_start();

GetAllFiles($_GET['chartid'], $_GET['boxid'], $_GET['orderid']);

$objFilemarks = new Filemarks;

function GetAllFiles($chartid, $boxid, $orderid) {
	
	$antes = '<table><thead><tr><th><a href="#" link-type="order" my-data-reveal-id="'.$orderid.'">Order '.GetName($orderid).'</a> > <a href="#" link-type="box" link-order="'.$orderid.'" link-box="'.$boxid.'" data-reveal-id="'.$boxid.'">Box '.GetName($boxid).'</a> > Your Files in Chart '.GetName($chartid).'</th></tr></thead><tbody><tr><td><table><thead><tr><th width="20%">Filename</th><th width="30%">Marks</th><th width="20%">Creation</th><th width="10%">Changed</th><th width="10%">Pages</th><th width="10%">Size</th></tr></thead><tbody>';
	
	$despues = '</tbody></table></tbody></table></td></tr>';
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT row_id, filename, creadate, moddate, pages, filesize, file_mark_id FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." and parent_id = ".$chartid.' ORDER BY filename ASC;';
	
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
				
				
					
				echo "<tr><td><a href=\"lib/data/file.download.php?fileid=".$row['row_id']."\" target=\"_blank\">".$row['filename']."</a></td>";
				echo "<td>";
				echo dropDownButton($row['row_id'], $row['file_mark_id']);
				echo "</td>";
				echo "<td>".$row['creadate']."</td><td>".$row['moddate']."</td>";
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

function dropDownButton($row_id, $mark_id)
{
	$objFilemarks = new Filemarks;
	
	$label = $objFilemarks->getLabelById($mark_id);
	
	
	return '<button href="#" data-dropdown="drop'.$row_id.'" aria-controls="drop'.$row_id.'" aria-expanded="false" class="tiny button dropdown">'.$label.'</button><br>
<ul id="drop'.$row_id.'" data-dropdown-content class="f-dropdown" aria-hidden="true" tabindex="-1">
  <li><a data-reveal-id="myModal">
    Click Me For A Modal
</a></li>
  <li><a href="#">This is another</a></li>
  <li><a href="#">Yet another</a></li>
 </ul>';

}

?>

<div id="myModal" class="reveal-modal" data-reveal>
   <h2>Awesome. I have it.</h2>
  <p class="lead">Your couch.  It is mine.</p>
  <p>I'm a cool paragraph that lives inside of an even cooler modal. Wins!</p>
  <a class="close-reveal-modal">&#215;</a>
</div>
