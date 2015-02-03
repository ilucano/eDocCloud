<?php

	session_start();
	$table = 'users';
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	require_once $arrIni['base'].'inc/filemarks.class.php';
		
	$objUsers = new Users();
	
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
				
				$companyCode = $objUsers->userCompany();
	
				$filter = " AND fk_empresa = :fk_empresa";
	
				$user = $objUsers->getUser($id, $filter, array(':fk_empresa' => $companyCode));

				echo '<form name="formulario" id="formulario"><div class="panel callout">';

				CreateForm($action,$user);

				echo '</div></form>';
				
				break;
			
			case "view":
				
				$companyCode = $objUsers->userCompany();
	
				$filter = " AND fk_empresa = :fk_empresa";
	
				$user = $objUsers->getUser($id, $filter, array(':fk_empresa' => $companyCode));
				
		 
				echo '<form name="formulario" id="formulario"><div class="panel callout">';

				CreateForm($action, $user);

				echo '</div></form>';

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
		
		global $objUsers;
		
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
		$_fDesc = 'Last Name';
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
		//echo $antes;
		//if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_empresa']; }
		//echo ComboCompanies('fk_empresa',$value,$disabled);
		//$value = "";
		//echo $despues;
		//
		
		//echo $antes;
		//$_fName = 'is_admin';
		//$_fDesc = 'Admin';
		//if ($vAction=='edit' || $vAction=='view') { $value = $vRow[$_fName]=="X"; }
		//echo ComboYesNo($_fName, $_fDesc, $value, $disabled);
		//$value = "";
		//echo $despues;
		
		
		
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
		
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['group_id']; }
		echo ComboGroups('group_id',$value,$disabled, $objUsers->userCompany());
		$value = "";
		echo $despues;
		
		
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['file_permission']; }
		echo ShowFilePermissionCheckboxes($value, $vAction);
		$value = "";
		echo $despues;
		
		
		// Boton
		echo $antes;
		if ($vAction=='edit' || $vAction=='create') {
		echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"".$vAction."\" data-page=\"".$vRow['row_id']."\" data-reveal-id=\"actions\">Save</a>";
		}
		echo $despues;
		
		}
	
		
	 
	function ShowFilePermissionCheckboxes($str_user_file_permission, $vAction)
	{
		
		$objUsers = new Users();
		
		echo "<label>File Permissions</label>";
		echo "<table id='permission_box'>";
		echo "<tr><td>";
		
		
		$companyCode = $objUsers->userCompany();
		
		$array_user_file_permission = json_decode($str_user_file_permission);
	 
		$objFilemarks = new Filemarks();
	
		$filter = " AND global = :global";
		
		$array_bind[':global'] = '1'; //fk_empresa = global share
		
		$res = $objFilemarks->listFilemarks($filter, $array_bind);
		
		$company_filter = " AND fk_empresa = :fk_empresa AND global = :global";
		$company_array_bind[':fk_empresa'] =  $objUsers->userCompany();
		
		$company_array_bind[':global'] = '0';
		
		$company_res = $objFilemarks->listFilemarks($company_filter, $company_array_bind);
		
		if($vAction == 'view') {
			$checkbox_disabled = " disabled ";
		}
		if (count($res) >= 1 || count($company_res) >= 1) {
			
			foreach($res as $key =>  $list) {
				$checkbox_id = $list['id'];
				$checkbox_value = $list['id'];
				$checkbox_name = 'file_permission[]';
				$checkbox_label = $list['label'];
				
				$checkedString = (in_array($checkbox_value, $array_user_file_permission)) ? " checked" : "";
				
				echo "<li><label for='".$checkbox_id."'><input type='checkbox' ".$checkedString.$checkbox_disabled." value='".$checkbox_value."' name='".$checkbox_name."' id='".$checkbox_id."'> ".$checkbox_label."</label></li>";
			
			}
			
			foreach($company_res as $key =>  $list) {
				$checkbox_id = $list['id'];
				$checkbox_value = $list['id'];
				$checkbox_name = 'file_permission[]';
				$checkbox_label = $list['label'];
				
				$checkedString = (in_array($checkbox_value, $array_user_file_permission)) ? " checked" : "";
				
				echo "<li><label for='".$checkbox_id."'><input type='checkbox' ".$checkedString.$checkbox_disabled." value='".$checkbox_value."' name='".$checkbox_name."' id='".$checkbox_id."'> ".$checkbox_label."</label></li>";
			
			}
			
		
		}
		 echo "</td></tr>";
	
		echo "</table>";
	
	}
	
?>