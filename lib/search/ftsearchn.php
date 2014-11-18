<?php
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/check.php';
require_once $arrIni['base'].'lib/db/db.php' ;
require_once $arrIni['base'].'inc/general.php';

session_start();

$txtSearch =  $_GET['texto'] ;
$pagAct =  $_GET['pagAct'] ;
$limit = 15;
$adj = 2;

if ($pagAct=="") { $pagAct=0; }

$antes = "<div class=\"large-1 columns\"></div><div class=\"large-10 columns\">";
$despues = "</div><div class=\"large-1 columns\"></div></div>";

if ($txtSearch=="") {
	echo $antes;
	echo "Please enter any text to search...";
	echo $despues;
} else {
	echo $antes;
	
	$con = ConnectionFactory::getConnection();
	
	$qryCnt = "SELECT COUNT(*) as num, FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." AND filename LIKE '%".$txtSearch."%'";
	//echo $qryCnt;
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
	
	if ($total_pages>$limit || $pagAct > 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, row_id, filename, texto FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." AND filename LIKE '%".$txtSearch."%' LIMIT ".($pagAct * $limit).",".($limit).";";
			
			//$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, filename, texto, MATCH(texto) AGAINST('".$txtSearch."' IN BOOLEAN MODE) AS Score FROM files WHERE MATCH(texto) AGAINST ('".$txtSearch."' IN BOOLEAN MODE) LIMIT ".($pagAct * $limit).",".($limit).";";
		} else {
			$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, row_id, filename, texto FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." AND  filename LIKE '%".$txtSearch."%' LIMIT ".(($pagAct) * $limit).",".($limit).";";
			//echo $qryFT;
			//$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, filename, texto, MATCH(texto) AGAINST('".$txtSearch."' IN BOOLEAN MODE) AS Score FROM files WHERE MATCH(texto) AGAINST ('".$txtSearch."' IN BOOLEAN MODE) LIMIT ".(($pagAct - 1) * $limit).",".($limit).";";
		}
		//echo $qryFT;
	} else {
		$qryFT = "SELECT row_id, creadate, pages, filesize, moddate, row_id, filename, texto FROM files WHERE fk_empresa = ".$_SESSION['CoCo']." AND  filename LIKE '%".$txtSearch."%';";
	}
	
	//echo $qryFT;
	
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	if (mysql_num_rows($res)) {
		echo "<table><tbody><thead><tr><th width=\"40%\">Filename</th><th width=\"20%\">Creation Date</th><th width=\"20%\">Modifcation Date</th><th width=\"10%\">Pages</th><th width=\"10%\">Size</th></tr></thead>";
		while ($row = mysql_fetch_array($res)) {
			echo "<tr><td width=40%>";
			
  			echo '<a href="lib/data/file.download.php?fileid='.$row['row_id'].'" target="_blank">'.$row['filename'].'</a></td>';
			$fecha = date("m/d/Y G:i:s",strtotime($row['creadate']));
			echo '<td width="20%">'.$fecha.'</td>';
			$fecha = date("m/d/Y G:i:s",strtotime($row['moddate']));
			echo '<td width="20%">'.$fecha.'</td>';
			echo '<td width="10%">'.$row['pages'].'</td>';
			$bytes = $row['filesize'];

			if ($bytes < 1048576) {
				$bytes = number_format($row['filesize'] / 1024,2).' Kb';
			} else {
				$bytes = number_format($row['filesize'] / 1024 / 1024,2).' Mb';
			}
			//$mbytes = number_format($row['filesize'] / 1024 / 1024,2);
			echo '<td width="10%">'.$bytes.'</td>';
			
			echo "</tr></td>";
		}
		echo "</tbody></table>";
		if ($total_pages>$limit || $pagAct > 0) {
			
			echo "<ul class=\"pagination\">";
			if ($pagAct==0) {
				echo "<li class=\"arrow unavailable\">&laquo;</li>";
			} else {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"paginaN\" data-page=\"0\" data-reveal-id=\"buscar\">&laquo;</a></li>";
			}
			
			$lastpage = ceil($total_pages/$limit);
			
			if ($lastpage<5) {
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"paginaN\" data-page=\"".($counter-1)."\" data-reveal-id=\"buscar\">".($counter)."</a></li>";
					}
				}
			} else {
				for ($counter = 1; $counter < 1 + ($adj * 2); $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"paginaN\" data-page=\"".($counter-1)."\" data-reveal-id=\"buscar\">".($counter)."</a></li>";
					}
				}
			}
			//<li class="current"><a href="">1</a></li> <li><a href="">2</a></li> <li><a href="">3</a></li> <li><a href="">4</a></li> <li class="unavailable"><a href="">&hellip;</a></li> <li><a href="">12</a></li> <li><a href="">13</a></li> 
			
			
			
			if ($lastpag==$pagAct) {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"paginaN\" data-page=\"".$lastpage."\" data-reveal-id=\"buscar\">&raquo;</a></li>";
			} else {
				echo "<li class=\"arrow unavailable\">&raquo;</li>";
			}
			
			echo "</ul>";
		}
	} else {
		echo $antes;
		echo "No results";
		echo $despues;
	}
			
	
	echo $despues;
}

ConnectionFactory::close();


?>