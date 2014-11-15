<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/check.php';
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	$pagAct =  $_GET['pagAct'] ;
	$limit = 15;
	$adj = 2
	;
	
	if ($pagAct=="") { $pagAct=0; }
	
	$antes = "<div class=\"large-1 columns\">&nbsp;</div><div class=\"large-10 columns\">";
	$despues = "</div><div class=\"large-1 columns\"></div></div>";
	
	echo $antes;
	
	$con = ConnectionFactory::getConnection();
		
	$qryCnt = "SELECT COUNT(*) as num FROM workflow WHERE fk_status IN (2) OR (T1.fk_status = 3 AND created_by = ".$_SESSION['Vid'].") ";
		
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
	
	if ($total_pages>$limit || $pagAct >= 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT T1.*, (T2.status) as status, (SELECT company_name FROM companies WHERE row_id = T4.fk_company) as empresa FROM workflow T1 INNER JOIN wf_status T2 ON T1.fk_status = T2.row_id INNER JOIN pickup T3 ON T1.wf_id = T3.fk_barcode LEFT JOIN objects T4 ON T3.fk_box = T4.row_id WHERE T1.fk_status IN (2) OR (T1.fk_status = 3 AND created_by = ".$_SESSION['Vid'].") ORDER BY T2.status DESC, T3.fk_barcode LIMIT 0,".($limit).";";
		} else {
			$qryFT = "SELECT T1.*, (T2.status) as status, (SELECT company_name FROM companies WHERE row_id = T4.fk_company) as empresa FROM workflow T1 INNER JOIN wf_status T2 ON T1.fk_status = T2.row_id INNER JOIN pickup T3 ON T1.wf_id = T3.fk_barcode LEFT JOIN objects T4 ON T3.fk_box = T4.row_id WHERE T1.fk_status IN 2) OR (T1.fk_status = 3 AND created_by = ".$_SESSION['Vid'].")  ORDER BY T2.status DESC, T3.fk_barcode LIMIT ".((($pagAct) * $limit)+ 1).",".($limit).";";
		}
	} else {
		$qryFT = "SELECT T1.*, (T2.status) as status, (SELECT company_name FROM companies WHERE row_id = T4.fk_company) as empresa FROM workflow T1 INNER JOIN wf_status T2 ON T1.fk_status = T2.row_id INNER JOIN pickup T3 ON T1.wf_id = T3.fk_barcode LEFT JOIN objects T4 ON T3.fk_box = T4.row_id WHERE T1.fk_status IN (2) OR (T1.fk_status = 3 AND created_by = ".$_SESSION['Vid'].")  ORDER BY T2.status DESC, T3.fk_barcode;";
	}
		
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	if (mysql_num_rows($res)) {
		echo "<table><tbody><thead><tr><th width=\"20%\">Box</th><th width=\"30%\">Customer</th><th width=\"20%\">Status</th><th width=\"30%\">Actions</th></tr></thead>";
		while ($row = mysql_fetch_array($res)) {
			echo "<tr><td width=20%>";
			echo substr($row['wf_id'],6).'</td>';
			echo '<td width="30%">'.$row['empresa'].'</td>';
			echo '<td width="20%">'.$row['status'].'</td>';

			echo '<div id="myModal" class="reveal-modal" data-reveal>
				  <h2>Please enter the information required</h2>
				  <form name="formulario" id="formulario"><div class="panel callout">
				  <div class="row">
				  <div class="large-7 columns">
				  <label>Quantity<input type="text" name="txtQty" id="txtQty" placeholder="Qty" /></label>
				  </div></div>
				  <div class="row">
				  <div class="large-9 columns">
				  <label>Comments<textarea name="txtText" id="txtText" placeholder="Put here your comments"></textarea></label>
				  </div></div>
				  <a class="close-reveal-modal">&#215;</a>
				  <div class="row">
				  <div class="large-9 columns">
				  <a href="#" class="button tiny alert" data-type="5" data-page="'.$row['row_id'].'" data-reveal-id="action">End Prepare</a>
				  </div></div>
				  </form>
				  </div>';

			echo '<td width="30%">';
			
			if ($row['fk_status']==2) 
			{
				//<a href="#" class="button [tiny small large]">Default Button</a>
				echo "<a href=\"#\" class=\"button tiny\" data-type=\"4\" data-page=\"".$row['row_id']."\" data-reveal-id=\"action\">Start Indexing</a>";
				//echo " | End Prepare";
			} else {
				//echo "Start Prepare";
				echo "<a href=\"#\" class=\"button tiny alert\" data-reveal-id=\"myModal\">End Indexing</a>";
			}
			
			
			echo '</td>';
			
			echo "</tr></td>";
		}
		echo "</tbody></table>";
		
		if ($total_pages>$limit || $pagAct >= 0) {
			
			echo "<ul class=\"pagination\">";
			if ($pagAct==0) {
				echo "<li class=\"arrow unavailable\">&laquo;</li>";
			} else {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"0\" data-reveal-id=\"grill\">&laquo;</a></li>";
			}
			
			$lastpage = ceil($total_pages/$limit) ;
			
			if ($lastpage<(($adj*2)+3)) {
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"grill\">".($counter)."</a></li>";
					}
				}
			} else {
				for ($counter = 1; $counter < 1 + ($adj * 2); $counter++) {
					if (($counter-1)==$pagAct) {
						echo "<li class=\"current\"><a href=\"#\">".($counter)."</a></li>";
					} else {
						echo "<li><a href=\"#\" data-type=\"pagina\" data-page=\"".($counter-1)."\" data-reveal-id=\"grill\">".($counter)."</a></li>";
					}
				}
			}
			if ($lastpage==($pagAct+1)) {
				echo "<li class=\"arrow unavailable\">&raquo;</li>";
			} else {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"".($lastpage-1)."\" data-reveal-id=\"grill\">&raquo;</a></li>";
			}
			
			echo "</ul>";
		}
	} else {
			echo $antes;
			echo "No results";
			echo $despues;
	}
			
	
	echo $despues;
	
	ConnectionFactory::close();


?>