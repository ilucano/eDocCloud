<?php

	session_start();
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	$action =  basename( $_GET['action'] );
	$id =  basename( $_GET['id'] );
	
	$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
	$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
	
	if ($arrPerm[$action]!='X') { 
		echo "You don't have permissions";
	} else {
		require_once $arrIni['base'].'inc/general.php';
		require_once $arrIni['base'].'lib/db/db.php' ;
		
		//$con = ConnectionFactory::getConnection();
		
		switch ($action) {
			case "create":
				echo '<form name="formulario" id="formulario"><div class="panel callout">';
				
				// Combo de Empresas
				echo $antes;
				echo ComboEmpresa();
				echo $despues;
				
				// Nombre de Grupo
				echo $antes;
				echo "<label>Group Name<input type=\"text\" placeholder=\"Group Name\" name=\"nombre\" id=\"nombre\" /></label>";
				echo $despues;
				
				echo $antes;
				echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"create\" data-page=\"\" data-reveal-id=\"actions\">Save</a>";
				echo $despues;
				
				echo '</div></form>';
				
				break;
			case "edit":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "SELECT * FROM groups WHERE row_id = ".$id.";";
				//echo $qry;
				
				//mysql_query("SET NAMES UTF8");
				$res = mysql_query($qry);
//echo $res;
				while ($row = mysql_fetch_array($res)) {
					//echo 'ENTRA';
					echo '<form name="formulario" id="formulario"><div class="panel callout">';

					// Combo de Empresas
					echo $antes;
					echo ComboEmpresaSel($row['fk_empresa']);
					echo $despues;

					// Nombre de Grupo
					echo $antes;
					echo "<label>Group Name<input type=\"text\" placeholder=\"Group Name\" name=\"nombre\" id=\"nombre\" value=\"".$row['nombre']."\" /></label>";
					echo $despues;

					echo $antes;
					echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"edit\" data-page=\"".$row['row_id']."\" data-reveal-id=\"actions\">Save</a>";
					echo $despues;

					echo '</div></form>';

				}

				ConnectionFactory::close();

				break;
			case "view":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "SELECT * FROM groups WHERE row_id = ".$id.";";
				//echo $qry;
				
				//mysql_query("SET NAMES UTF8");
				$res = mysql_query($qry);
//echo $res;
				while ($row = mysql_fetch_array($res)) {
					//echo 'ENTRA';
					echo '<form name="formulario" id="formulario"><div class="panel callout">';

					// Combo de Empresas
					echo $antes;
					echo ComboEmpresaSelD($row['fk_empresa']);
					echo $despues;

					// Nombre de Grupo
					echo $antes;
					echo "<label>Group Name<input disabled type=\"text\" placeholder=\"Group Name\" name=\"nombre\" id=\"nombre\" value=\"".$row['nombre']."\" /></label>";
					echo $despues;

					echo $antes;
					//echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"edit\" data-page=\"\" data-reveal-id=\"actions\">Save</a>";
					echo $despues;

					echo '</div></form>';

				}

				ConnectionFactory::close();

				break;
				
			case "delete":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "DELETE FROM groups WHERE row_id = ".$id.";";
				
				$res=mysql_query($qry)
					or die("-1");
				
				echo "Record updated...";

				ConnectionFactory::close();

				break;
			}
		//echo "OK";
	}
	
	
	
	/*
	;
		
	$qryCnt = "SELECT COUNT(*) as num FROM groups WHERE fk_empresa = ".$_SESSION['CoCo'];
		
	$total_pages = mysql_fetch_array(mysql_query($qryCnt));
	$total_pages = $total_pages['num'];
		
	if ($total_pages>$limit || $pagAct > 0) {
		if ($pagAct==0) {
			$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo']." LIMIT ".($pagAct * $limit).",".($limit).";";
		} else {
			$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo']." LIMIT ".(($pagAct) * $limit).",".($limit).";";
		}
	} else {
		$qryFT = "SELECT T1.*, (T2.company_name) as empresa FROM groups T1 INNER JOIN companies T2 ON T1.fk_empresa = T2.row_id WHERE T1.fk_empresa = ".$_SESSION['CoCo'].";";
	}
		
	mysql_query("SET NAMES UTF8");
	$res = mysql_query($qryFT);
	
	if (mysql_num_rows($res)) {
		echo "<table><tbody><thead><tr><th width=\"40%\">Group Name</th><th width=\"40%\">Company</th><th width=\"20%\">Actions</th></tr></thead>";
		while ($row = mysql_fetch_array($res)) {
			echo "<tr><td width=50%>";
			echo $row['nombre'].'</td>';
			echo '<td width="40%">'.$row['empresa'].'</td>';

			echo '<td width="10%">';
			
			if ($arrPerm['view']=='X') { echo '<a href="#">View</a>'; }
			if ($arrPerm['edit']=='X') { echo ' | <a href="#">Edit</a>'; }
			if ($arrPerm['delete']=='X') { echo ' | <a href="#">Delete</a> '; }

			echo '</td>';
			
			echo "</tr></td>";
		}
		echo "</tbody></table>";
		if ($total_pages>$limit || $pagAct > 0) {
			
			echo "<ul class=\"pagination\">";
			if ($pagAct==0) {
				echo "<li class=\"arrow unavailable\">&laquo;</li>";
			} else {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"0\" data-reveal-id=\"grill\">&laquo;</a></li>";
			}
			
			$lastpage = ceil($total_pages/$limit);
			
			if ($lastpage<5) {
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
			
			if ($lastpag==$pagAct) {
				echo "<li class=\"arrow\"><a href=\"#\" data-type=\"pagina\" data-page=\"".$lastpage."\" data-reveal-id=\"grill\">&raquo;</a></li>";
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
	
	
*/

?>