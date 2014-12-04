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
    <link href="<?php echo $arrIni['foundationurl']; ?>css/foundation-icon.css" rel="stylesheet" />
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

$page = 'inproc';
require $arrIni['base'].'inc/ADMtopbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<!-- TITULO -->
<div class="row">
	<div class="large-12 columns">
    </h2>All Workflows in Process</h2><p>
    </div>
</div>
<!-- TABLA -->
<div class="row">
	<div name="grilla" id="grilla" class="row">
	<?php
  
	require $arrIni['base'].'lib/data/dInproc.php'; 
	
	?>
    </div>
</div>
<!-- DETAILS -->




<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.tab.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
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
			if (vId=="grill")
			{
				if (vVar=="pagina") {
					$.ajax({
					   type: "GET",
					   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/dInproc.php",
					   data: "pagAct="+vPag,
					   success: function(html){
						if(html!="")
						{
							$("#grilla").html(html);
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
			} else if (vId=="action")
			{
				$.ajax({
				   type: "GET",
				   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/wf.inproc.php",
				   data: "wfid="+vPag+"&status="+vVar,
				   success: function(html){
					if(html!="")
					{
						$("#grilla").html(html);
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
			};
		
		$(document).foundation();
  	</script>

</body>
</html>