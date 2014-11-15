    <?php

	session_start();
	$table = 'pickup';
	
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
				
				echo '<form name="formulario" id="formulario" data-abide><div class="panel callout">';
				
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

				while ($row = mysql_fetch_array($res)) {
					//echo 'ENTRA';
					echo '<form name="formulario" id="formulario" data-abide><div class="panel callout">';

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
					echo '<form name="formulario" id="formulario" data-abide><div class="panel callout">';

					CreateForm($action,$row);

					echo '</div></form>';

				}

				ConnectionFactory::close();

				break;
				
			case "delete":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				
				ConnectionFactory::getConnection();
				
				$qry = "SELECT T1.* FROM pickup T1 INNER JOIN objects T2 ON T1.fk_box = T2.row_id WHERE T1.row_id = ".$id.";";
	
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
	
	function ComboCaja($vName,$vId,$vDis,$vParId) {
		$strRet = "";
	
		$con = ConnectionFactory::getConnection();
		
		$qry = "SELECT T1.*, (T2.company_name) as company FROM objects T1 INNER JOIN companies T2 ON T1.fk_company = T2.row_id WHERE T1.fk_obj_type = 2 AND fk_parent = ".$vParId.";";
		
		// Inicio de la seleccion de Ordenes
		$strRet = '<label>Box<select name="'.$vName.'" '.$vDis.'>';
		$res = mysql_query($qry);
		
		if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
				if ($vId=='') {
					$vNewId = $_SESSION['Vid'];
				} else {
					$vNewId = $vId;
				}
				if ($row['row_id']==$vNewId) {	
					$strRet = $strRet.'<option '.$isDis.' selected value="'.$row['row_id'].'">'.$row['f_code'].' '.$row['f_name'].' ('.$row['company'].')'.'</option>';
				} else {
					if ($isDis!="disabled") {
						$strRet = $strRet.'<option value="'.$row['row_id'].'">'.$row['f_code'].' '.$row['f_name'].' ('.$row['company'].')'.'</option>';
					}
				}
			}
		}
		
		$strRet = $strRet.'</select></label>';
		
		ConnectionFactory::close();
		
		
		return $strRet;
	}
	
	function ComboOrden($vName,$vId,$vDis) {
		$strRet = "";
	
		$con = ConnectionFactory::getConnection();
		
		$qry = "SELECT T1.*, (T2.company_name) as company FROM objects T1 INNER JOIN companies T2 ON T1.fk_company = T2.row_id WHERE T1.fk_obj_type = 1;";
		
		// Inicio de la seleccion de Ordenes
		$strRet = '<label>Order<select name="'.$vName.'" '.$vDis.'>';
		$res = mysql_query($qry);
		
		if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
				if ($vId=='') {
					$strRet = $strRet.'<option '.$isDis.' selected value=""></option>';
					$vNewId = "";
				} else {
					$vNewId = $vId;
				}
				if ($row['row_id']==$vNewId) {
					$strRet = $strRet.'<option '.$isDis.' selected value="'.$row['row_id'].'">'.$row['f_code'].' '.$row['f_name'].' ('.$row['company'].')'.'</option>';
				} else {
					if ($isDis!="disabled") {
						$strRet = $strRet.'<option value="'.$row['row_id'].'">'.$row['f_code'].' '.$row['f_name'].' ('.$row['company'].')'.'</option>';
					}
				}
			}
		}
		
		$strRet = $strRet.'</select></label>';
		
		ConnectionFactory::close();
		
		
		return $strRet;
	}
	
	function CreateForm($vAction,$vRow) {
		
		$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
		$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
		
		if ($vAction=='view') { $disabled = " disabled "; }
		
		// Combo de Usuarios
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_user']; }
		echo ComboUsers('fk_user',$value,$disabled);
		$value = "";
		echo $despues;
		
		// Combo de Empresas
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_company']; }
		echo ComboCompanies('fk_company',$value,$disabled);
		$value = "";
		echo $despues;
		
		// Combo de Orden
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_order']; }
		echo ComboOrden('fk_order',$value,$disabled);
		$value = "";
		echo $despues;
		
		// Combo de Barcode
		echo $antes;
		$_fDesc = 'Barcode';
		$_fName = 'fk_barcode';
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
		$value = "";
		echo $despues;
		
		// Combo de Caja
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_box']; }
		echo ComboCaja('fk_box',$value,$disabled,$vRow['fk_order']);
		$value = "";
		echo $despues;
		
		// Oculto:  Timestamp
		if ($vAction=='create') {
			echo $antes;
			$_fName = 'timestamp';
			echo "<input type=\"hidden\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".date("Y-m-d G:i:s")."\" />";
			$value = "";
			echo $despues;
		} else {
			echo $antes;
			$_fDesc = 'Creation';
			$_fName = 'timestamp';
			echo "<label>".$_fDesc."<input disabled type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;	
		}
		
		// Boton
		echo $antes;
		if ($vAction=='edit' || $vAction=='create') {
		echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"".$vAction."\" data-page=\"".$vRow['row_id']."\" data-reveal-id=\"actions\">Save</a>";
		}
		echo $despues;
		
		}
		

?>