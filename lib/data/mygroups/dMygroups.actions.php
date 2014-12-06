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
		$array_permission = json_decode($vRow['group_permission'], true);

		echo $antes;
		echo "<label>Group Permission</label>";
		
	    	
		ShowPermissionCheckboxes($array_permission);
		
		echo $despues;
 
		
		// Boton
		echo $antes;
		if ($vAction=='edit' || $vAction=='create') {
		echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"".$vAction."\" data-page=\"".$vRow['row_id']."\" data-reveal-id=\"actions\">Save</a>";
		}
		echo $despues;
		
	}
	
	
	function ShowPermissionCheckboxes($array_permission)
	{
		global $permissionList; 
		
		echo "<table id='permission_box'>";
	
		
		foreach($permissionList as $key =>  $list) {
			
			echo "<tr>";
			echo "<th colspan=2>$key " .$list['label'] . "</th>";
			echo "</tr>";
			echo "<tr>";
			echo "<td width='20%' style='padding-left: 25px;'><ul>";
			
			$codes = $list['code'];
			 
			foreach($codes as $codeKey => $code)
			{
				
			    
				$checkbox_name = 'group_permission' . '[' . $key .  '][]';
				$checkbox_id = $key . "_" . $codeKey;
				$checkbox_value = $codeKey;
				$checkbox_label = $code;
 
				$checkedString = ($array_permission[$key][$codeKey] == '1') ? " checked" : "";
								
				echo "<li><label for='".$checkbox_id."'><input type='checkbox' ".$checkedString." value='".$checkbox_value."' name='".$checkbox_name."' id='".$checkbox_id."'> ".$checkbox_label."</label></li>";
				
			}
			
			echo "</ul></td>";
			echo "</tr>";

		}

		
		echo "</table>";

	}

?>