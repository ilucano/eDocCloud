<?php

	session_start();
	$table = 'objects';
	
	require_once '/var/www/html/config.php';
	
	require_once $arrIni['base'].'inc/checkACL.php';
	
	$action =  basename( $_GET['action'] );
	$id =  basename( $_GET['id'] );
	
	$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
	$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
	
	if ($arrPerm[$action]!='X' && $action!='close') { 
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
//echo $res;
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
				
				$qry = "SELECT * FROM objects WHERE fk_parent = ".$id.";";
	
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
			case "close":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "UPDATE ".$table." SET fk_status = 5 WHERE row_id = ".$id.";";
				
				$res=mysql_query($qry)
					or die("-1");
				
				echo "Record updated...";
				
				ConnectionFactory::close();

				break;
			case "invoiced":
				//require_once $arrIni['base'].'lib/db/dbConn.php' ;
				ConnectionFactory::getConnection();
				
				$qry = "UPDATE ".$table." SET invoiced = 'X' WHERE row_id = ".$id.";";
				//echo $qry;
				$res=mysql_query($qry)
					or die("-1");
				
				echo "Record updated...";
				
				ConnectionFactory::close();

				break;
			}
		//echo "OK";
	}
	
	function CreateForm($vAction,$vRow) {
		
		$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
		$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
		
		if ($vAction=='view') { $disabled = " disabled "; }
		
		// Combo de Empresas
		echo $antes;
		if ($vAction=='edit' || $vAction=='view') { $value = $vRow['fk_company']; }
		echo ComboCompanies('fk_company',$value,$disabled);
		$value = "";
		echo $despues;
		
		// F Code
		echo $antes;
		$_fName = 'f_code';
		$_fDesc = 'Code';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// F Name
		echo $antes;
		$_fName = 'f_name';
		$_fDesc = 'Name';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Price
		echo $antes;
		$_fName = 'ppc';
		$_fDesc = 'Price per Page';
		if ($vAction=='edit' || $vAction=='view') { $value = "value=\"".$vRow[$_fName]."\""; }
		echo "<label>".$_fDesc."<input ".$disabled." type=\"text\" placeholder=\"".$_fDesc."\" name=\"".$_fName."\" id=\"".$_fName."\" ".$value." /></label>";
		$value = "";
		echo $despues;
		
		// Oculto:  Object Type
		echo $antes;
		$_fName = 'fk_obj_type';
		if ($vAction=='edit' || $vAction=='create') { $value = "value=\"2\""; }
		echo "<input type=\"hidden\"  name=\"".$_fName."\" id=\"".$_fName."\" ".$value." />";
		$value = "";
		echo $despues;
		
		// Oculto:  Object Type
		if ($vAction=='create') {
			echo $antes;
			$_fName = 'creation';
			echo "<input type=\"hidden\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".date("Y-m-d G:i:s")."\" />";
			$value = "";
			echo $despues;
		} else {
			echo $antes;
			$_fDesc = 'Creation';
			$_fName = 'creation';
			echo "<label>".$_fDesc."<input disabled type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;	
			
			echo $antes;
			$_fDesc = 'Pickup';
			$_fName = 'pickup';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;	
			
			echo $antes;
			$_fDesc = 'Goods Receipt';
			$_fName = 'enterfac';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;
			
			echo $antes;
			$_fDesc = 'Scan';
			$_fName = 'scan';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;	
			
			echo $antes;
			$_fDesc = 'QA';
			$_fName = 'quality';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;
			
			echo $antes;
			$_fDesc = 'Return';
			$_fName = 'retdate';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;
			
			echo $antes;
			$_fDesc = 'Shred';
			$_fName = 'shred';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;
			
			echo $antes;
			$_fDesc = 'Quantity';
			$_fName = 'qty';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;
			
			echo $antes;
			$_fDesc = 'Integration';
			$_fName = 'integration';
			echo "<label>".$_fDesc."<input ".$disabled." type=\"text\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"".$vRow[$_fName]."\" /></label>";
			$value = "";
			echo $despues;
		}
		
		echo $antes;
		$_fName = 'fk_status';
		echo "<input type=\"hidden\"  name=\"".$_fName."\" id=\"".$_fName."\" value=\"1\" />";
		$value = "";
		echo $despues;
		
		// Boton
		echo $antes;
		if ($vAction=='edit' || $vAction=='create') {
		echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"".$vAction."\" data-page=\"".$vRow['row_id']."\" data-reveal-id=\"actions\">Save</a>";
		}
		echo $despues;
		
		}

?>