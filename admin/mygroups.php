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
  
  <style>
  #permission_box li {
	list-style-type: none;
  }
  
  #permission_box  tr, #permission_box td, #permission_box th {
	padding: 3px;
  }
  
  </style>
  </head>
<body>
<?php

$page = 'mygroups';
require $arrIni['base'].'inc/ADMtopbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<!-- TITULO -->
<div class="row">
	<div class="large-12 columns">
    </h2>Administrate Company Groups</h2><p>
    </div>
</div>
<!-- TABLA -->
<div class="row">
	<div name="grilla" id="grilla" class="row">
	<?php
  
	require $arrIni['base'].'lib/data/mygroups/dMygroups.php'; 
	
	?>
    </div>
</div>
<!-- DETAILS -->
<div class="row">
	<div class="row">
    <div class="large-1 columns">&nbsp;</div><div class="large-10 columns"  name="details" id="details" >
    </div>
    <div class="large-1 columns">&nbsp;</div>
    </div>
</div>



<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.tab.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
  	<!-- Other JS plugins can be included here -->

  	<script>
    	
		var varIn = '';
		
		$("#txtsearch").on('keydown', function (e) {
		 if (e.which==13) {
			 	vStr = '';
				vId="grill	";
				vPag=0;
				vVar="pagina";
				buscar(vId, vPag, vVar, vStr);
			 }
		 });
		
		
		$(document).on('keydown', function (e) {
		 if (e.which==13) {
				$("#but").click();
			 }
		 });
		
		$(document).on("click", "a[data-reveal-id]", function() {
    		
			
			vId=$(this).attr('data-reveal-id');
			vPag=$(this).attr('data-page');
			vVar=$(this).attr('data-type');
			vStr='';
			
			buscar(vId, vPag, vVar, vStr);
			
			
    	
  		} );
		
		function buscar(vId, vPag, vVar, vStr) {
			if (vId=="grill")
			{
				if (vVar=="pagina") {
					$.ajax({
					   type: "GET",
					   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/mygroups/dMygroups.php",
					   data: "pagAct="+vPag+"&txtsearch="+vStr,
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
			} else if (vId=="buttons")
			{
				$.ajax({
				   type: "GET",
				   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/mygroups/dMygroups.actions.php",
				   data: "action="+vVar+"&id="+vPag,
				   success: function(html){
					if(html!="")
					{
						$("#details").html(html);
						vText=html; 
						if (vText=="Record updated...") {
							$.ajax({
							   type: "GET",
							   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/mygroups/dMygroups.php",
							   data: "pagAct=0&txtsearch=",
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
					}
					else
					{
						$("#details").html('Error');
					}
				},
				   beforeSend:function()
				   {
						$("#details").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				   }
				});
			} else if (vId=="actions")
			{
				$.ajax({
				   type: "GET",
				   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/mygroups/dMygroups.actions.e.php",
				   data: "action="+vVar+"&id="+vPag+"&"+$("#formulario").serialize(),
				   success: function(html){
					if(html!="")
					{
						$("#details").html(html);
						vText=html; 
						if (vText=="Record updated...") {
							$.ajax({
							   type: "GET",
							   url: "http://<?php echo $_SERVER['SERVER_NAME'];?>/lib/data/mygroups/dMygroups.php",
							   data: "pagAct=0&txtsearch=",
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
					}
					else
					{
						$("#details").html('Error');
					}
				},
				   beforeSend:function()
				   {
						$("#details").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				   }
				});
			} 	
			};
		
		$(document).foundation();
  	</script>

</body>
</html>