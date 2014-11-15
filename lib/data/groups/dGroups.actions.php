<?php

	session_start();
	$table = 'users';
	
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
				
				CreateForm($action,'');
				
				echo '</div></form>';
				
				break;
			case "edit":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "SELECT * FROM ".$table." WHERE row_id = ".$id.";";
				//echo $qry;
				
				mysql_query("SET NAMES UTF8");
				$res = mysql_query($qry);
//echo $res;
				while ($row = mysql_fetch_array($res)) {
					//echo 'ENTRA';
					echo '<form name="formulario" id="formulario"><div class="panel callout">';

					CreateForm($action,$row);

					echo '</div></form>';

				}
				
				ConnectionFactory::close();

				break;
			case "view":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "SELECT * FROM ".$table." WHERE row_id = ".$id.";";
				//echo $qry;
				
				//mysql_query("SET NAMES UTF8");
				mysql_query("SET NAMES UTF8");
				$res = mysql_query($qry);
//echo $res;
				while ($row = mysql_fetch_array($res)) {
					//echo 'ENTRA';
					echo '<form name="formulario" id="formulario"><div class="panel callout">';

					CreateForm($action,$row);

					echo '</div></form>';

				}

				ConnectionFactory::close();

				break;
				
			case "delete":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "SELECT * FROM companies WHERE fk_admin = ".$id.";";
	
				$res = mysql_query($qry);
				while ($row = mysql_fetch_array($res)) {
					$vRet = "NO";
				}
				
				if ($vRet!="NO") {
					$qry = "DELETE FROM ".$table." WHERE row_id = ".$id.";";
					
					$res=mysql_query($qry)
						or die("-1");
					
					echo "Record updated...";
				} else {
					echo "Record cannot be deleted...";	
				}

				ConnectionFactory::close();

				break;
			}
		//echo "OK";
	}
	
	function CreateForm($vAction,$vRow) {
		
		$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
		$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
		
		if ($vAction=='view') { $disabled = " disabled "; }
		
		// Nombre de Usuario
		echo $antes;
		$_fName = 'username';
		$_fDesc = 'Username';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; $disabledO = "disabled"; }
		//echo $value;
		echo "<label>".$_fDesc."<input ".$disabled.$disabledO." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Direccion 1
		echo $antes;
		$_fName = 'password';
		$_fDesc = 'Password';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Nombre
		echo $antes;
		$_fName = 'first_name';
		$_fDesc = 'First Name';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		//echo $value;
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Apellido
		echo $antes;
		$_fName = 'last_name';
		$_fDesc = 'Last Name 1';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Email
		echo $antes;
		$_fName = 'email';
		$_fDesc = 'Email';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Telefono
		echo $antes;
		$_fName = 'phone';
		$_fDesc = 'Phone';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Combo de Empresas
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_empresa']; }
		echo ComboCompanies('fk_empresa',$value,$disabled);
		$value = "";
		echo $despues;
		
		// Is Admin
		echo $antes;
		$_fName = 'is_admin';
		$_fDesc = 'Admin';
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow[$_fName]=="X"; }
		echo ComboYesNo($_fName, $_fDesc, $value, $disabled);
		$value = "";
		echo $despues;
		
		// Status A:Active I:Inactive
		echo $antes;
		$_fName = 'status';
		$_fDesc = 'Active';
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow[$_fName]=="X"; }
		echo ComboYesNo($_fName, $_fDesc, $value, $disabled);
		$value = "";
		echo $despues;
		
		// Company Admin
		echo $antes;
		$_fName = 'company_admin';
		$_fDesc = 'Company Admin';
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow[$_fName]=="X"; }
		echo ComboYesNo($_fName, $_fDesc, $value, $disabled);
		$value = "";
		echo $despues;
		
		// Boton
		echo $antes;
		if ($vAction=='edit' || $vAction=='create') {
		echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"".$vAction."\" data-page=\"".$vRow['row_id']."\" data-reveal-id=\"actions\">Save</a>";
		}
		echo $despues;
		
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