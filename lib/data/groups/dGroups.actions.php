<?php

	session_start();
	$table = 'groups';
	
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
			
			}
		//echo "OK";
	}
	
	function CreateForm($vAction,$vRow) {
		
		$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
		$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
		
		if ($vAction=='view') { $disabled = " disabled "; }
		
		
		// Nombre
		echo $antes;
		$_fName = 'nombre';
		$_fDesc = 'Group Name';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		//echo $value;
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		
		//populate json
		$obj_permission = json_decode($vRow['group_permission']);

		echo $antes;
		echo "<label>Group Permission</label>";
		
	    	
		ShowPermissionCheckboxes($obj_permission);
		
		echo $despues;
 
		
		// Boton
		echo $antes;
		if ($vAction=='edit' || $vAction=='create') {
		echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"".$vAction."\" data-page=\"".$vRow['row_id']."\" data-reveal-id=\"actions\">Save</a>";
		}
		echo $despues;
		
	}
	
	
	function ShowPermissionCheckboxes()
	{
	
		$permissionList = array('application' 	=> array('label' => 'Application',
													   'code' => array('main' => 'Main')
													  ),
								'workflow' 		=> array('label' => 'Workflow',
													'code' => array('pickup' => 'Pickup',
																	'preparation' => 'Preparation',
																	'scan' => 'Scan',
																	'qa' => 'QA',
																	'ocr' => 'OCR')
												   ),
								'reports' 		=> array('label' => 'Reports',
														 'code' => array('all_boxes'  => 'All Boxes',
																		 'group_by_status' => 'Group By Status')
														),
								'admin_menu'	=> array('label' => 'Admin Menu',
														 'code' => array('home' => 'Home',
																		 'company' => 'Company',
																		 'users' => 'Users',
																		 'groups' => 'Groups',
																		 'orders' => 'Orders',
																		 'pickup' => 'Pickup',
																		 'box'	=> 'Box',
																		 'chart' => 'Chart',
																		 'file' => 'File',
																		 'barcode' => 'Barcode')
														 
														 ),
								'user_menu'		=> array('label' => 'User Menu',
														 'code' => array('home' => 'Home',
																		 'orders' => 'Orders',
																		 'search' => 'Search',
																		 'change_password' => 'Change Password')
														 )
								
								
								);
		
		echo "<table id='permission_box'>";
	
		
		foreach($permissionList as $key =>  $list) {
			echo "<tr>";
			echo "<th colspan=2>" .$list['label'] . "</th>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='20%' style='padding-left: 25px;'><ul>";
			
			foreach($list['code'] as $codeKey => $code)
			{
				
				$checkbox_name = $key . '[]';
				$checkbox_id = $key . "_" . $codekey;
				$checkbox_value = $codekey;
				$checkbox_label = $code;
				echo "<ul><li><label for='checkbox1'><input type='checkbox' value='".$checkbox_value."' name='".$checkbox_name."' id='".$checkbox_id."'> ".$checkbox_label."</label></li></ul>";
				
			}
			
			echo "</ul></td>";
			echo "</tr>";

		}
		//echo "<th colspan=2>Application</th>";
		//echo "</tr>";
		//echo "<tr>";
		//echo "<td width='20%' style='padding-left: 25px;'>";
		//echo "<ul><li><label for='checkbox1'><input type='checkbox' id='checkbox1'> Main</label></li></ul>";
		//echo "</td>";
		//echo "</tr>";
		//
		//echo "<tr>";
		//echo "<th colspan=2>Workflow</th>";
		//echo "</tr>";
		//echo "<tr>";
		//echo "<td width='20%' style='padding-left: 25px;'>";
		//echo "<ul><li><label for='checkbox1'><input type='checkbox' id='checkbox1'> Pickup</label></li>";
		//echo "<li><label for='checkbox1'><input type='checkbox' id='checkbox1'> Preparation</label></li></ul>";
		//echo "</td>";
		//echo "</tr>";
		
		echo "</table>";

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