<?php 

session_start();

require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/checkACL.php'; 
require_once $arrIni['base'].'inc/general.php'; 


?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to eDocCloud - imagingXperts</title>
    <link rel="stylesheet" href="<?php echo $arrIni['foundationurl']; ?>css/foundation.css" />
    <link href="<?php echo $arrIni['foundationurl']; ?>css/docs.css" rel="stylesheet" />
    <link href="<?php echo $arrIni['foundationurl']; ?>css/foundation-icons.css" rel="stylesheet" />
    <script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/modernizr.js"></script>
	<script language="JavaScript">
	<!-- hide from none JavaScript Browsers

	Image3 = new Image(25,25)
	Image3.src = "images/loader.gif"

	// End Hiding -->
	</script>
  </head>
<body>
<?php

$page = 'pick';
require $arrIni['base'].'inc/ADMtopbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<!-- TITULO -->
<div class="row">
	<div class="large-12 columns">
    </h2>Start a Workflow (Create Pickup)</h2><p>
    </div>
</div>
<!-- TABLA -->
<div class="row">
	<div class="large-2 columns">
    </div>
	<div class="large-8 columns" id="grilla">
	<?php
	
	require_once $arrIni['base'].'inc/general.php';
	require_once $arrIni['base'].'lib/db/db.php' ;
	
	function ComboOrden($vName,$vId,$vDis) {
		$strRet = "";
	
		$con = ConnectionFactory::getConnection();
		
		$qry = "SELECT T1.*, (T2.company_name) as company FROM objects T1 INNER JOIN companies T2 ON T1.fk_company = T2.row_id WHERE T1.fk_obj_type = 1 AND T1.fk_status <> 5;";
		
		// Inicio de la seleccion de Ordenes
		$strRet = '<label>Order<select name="'.$vName.'" '.$vDis.'>';
		$res = mysql_query($qry);
		
		if ($_SESSION['VisAdmin']!='X') { $isDis = "disabled"; }
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
				if ($vId=='') {
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
	
	function ComboBarcode($vName) {
		$strRet = "";
	
		$con = ConnectionFactory::getConnection();
		
		echo "<label>Barcodes</label>";
		$qry = "SELECT * FROM barcodes WHERE fk_user = 0 OR fk_user = ".$_SESSION['Vid']." ORDER BY barcode ASC;";
	
		$res = mysql_query($qry);
		$suma = 1;
		
		if (mysql_num_rows($res)) {
			while ($row = mysql_fetch_array($res)) {
				$strRet = $strRet.'<input id="barcode'.$suma.'" name="barcode'.$suma.'" type="checkbox" ><label for="barcode'.$suma.'">'.$row['barcode'].'</label>';
				$strRet = $strRet.'<input type="hidden" name="barcodev'.$suma.'" id="barcodev'.$suma.'" value="'.$row['barcode'].'" />';
				if (($suma % 2)==0) { $strRet = $strRet.'<br>'; }
				$suma = $suma + 1;
			}
		}
		
		echo "<input type=\"hidden\" name=\"qty\" id=\"qty\" value=\"".($suma-1)."\" />";
		
		ConnectionFactory::close();
		
		
		return $strRet;
	}
	
	$antes = "<div class=\"row\"><div class=\"large-6 columns\">";
	$despues = "</div><div class=\"large-6 columns\">&nbsp;</div></div>";
  
	echo '<form name="formulario" id="formulario" data-abide><div class="panel callout">';
	
	// Combo de Orden
	echo $antes;
	$orden =  basename( $_GET['orden'] );
	if ($orden<>"") { $disabled = "disabled"; }
	echo ComboOrden('orden',$orden,$disabled);
	echo $despues;
	
	// Combo de Barcode
	echo $antes;
	echo ComboBarcode('barcode');
	echo $despues;
	
	// Boton
	echo $antes;
	echo "<a href=\"#\" name=\"but\" id=\"but\" class=\"button radius\" data-type=\"create\" data-reveal-id=\"action\">Create Pickup</a>";
	echo $despues;

	echo '</div></form>'; 
	
	?>
    </div>
    <div class="large-2 columns">
    </div>
</div>
<!-- DETAILS -->




<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.tab.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
   	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.abide.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
    <!-- Other JS plugins can be included here -->

  	<script>
    	
		$(document).on("click", "a[data-reveal-id]", function() {
    		
			
			vId=$(this).attr('data-reveal-id');
			vPag=$(this).attr('data-page');
			vVar=$(this).attr('data-type');
			
			buscar(vId, vPag, vVar);
			
			
    	
  		} );
		
		function buscar(vId, vPag, vVar) {
			if (vId=="action")
			{
				if (vVar=="create") {
					$.ajax({
					   type: "GET",
					   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/wf.pickup.php",
					   data: "action="+vVar+"&id="+vPag+"&"+$("#formulario").serialize(),
					   success: function(html){
						if(html!="")
						{
							window.location.assign("http://<?php echo $_SERVER['SERVER_NAME'];?>/admin/wf_pick.php?orden="+html);
						}
						else
						{
							$("#grilla").html('Error');
						}
					},
					   beforeSend:function()
					   {
							$("#grilla").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
					   }
					});
				}
			}
			};
		
		$(document).foundation();
  	</script>

</body>
</html>