<?php 

session_start();

require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/checkACL.php'; 
require_once $arrIni['base'].'lib/data/dOrders.php'; 
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

$page = 'orders';
require $arrIni['base'].'inc/topbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<div class="row">
	<div class="large-6 columns">
    
    <!--Inicia Tabla de Ordenes -->
    <table>
        <thead>
            <tr>
              <th>Your Orders</th>
            </tr>
        </thead>
		<tbody>
            <?php
			
			GetAllOrders();
			
            ?>
        </tbody>
    </table>
	
    <a href="#" data-dropdown="hover1" data-options="is_hover:true">Has Hover Dropdown</a>

<ul id="hover1" class="f-dropdown" data-dropdown-content>
  <li><a href="#">This is a link</a></li>
  <li><a href="#">This is another</a></li>
  <li><a href="#">Yet another</a></li>
</ul>

    </div>
	<div class="large-6 columns">
		<div class="row" id="contajax">&nbsp;</div>
    </div>
</div>


<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.dropdown.js"></script>
	
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
  	<!-- Other JS plugins can be included here -->

  	<script>
    	$(document).foundation();
		$(document).on("click", "a[my-data-reveal-id]", function() {
    		
			
			vId=$(this).attr('my-data-reveal-id');
			vType=$(this).attr('link-type');
			vOrder=$(this).attr('link-order');
			vBox=$(this).attr('link-box');
			vChart=$(this).attr('link-chart');
			
			if (vType=="order")
			{
				$.ajax({
				   type: "GET",
				   url: "lib/data/dBoxes.php",
				   data: "ordid="+vId,
				   success: function(html){
					if(html!="")
					{
						$("#contajax").html(html);
					}
					else
					{
						$("#contajax").html('Error');
					}
				},
				   beforeSend:function()
				   {
					   	$("#contajax").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				   }
				});
			} else if (vType=="box") {
				$.ajax({
				   type: "GET",
				   url: "lib/data/dFiles.php",
				   data: "boxid="+vId+"&orderid="+vOrder,
				   success: function(html){
					if(html!="")
					{
						$("#contajax").html(html);
					}
					else
					{
						$("#contajax").html('Error');
					}
				},
				   beforeSend:function()
				   {
					   $("#contajax").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				   }
				});
			} else if (vType=="chart") {
				$.ajax({
				   type: "GET",
				   url: "lib/data/dArchivos.php",
				   data: "boxid="+vBox+"&orderid="+vOrder+"&chartid="+vId,
				   success: function(html){
					if(html!="")
					{
						$("#contajax").html(html);
					}
					else
					{
						$("#contajax").html('Error');
					}
				},
				   beforeSend:function()
				   {
					   $("#contajax").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				   }
				});
			}  else if (vType=="file") {
				$.ajax({
				   type: "GET",
				   url: "lib/data/file.download.php",
				   data: "fileid="+vfile,
				   success: function(html){
					if(html!="")
					{
						$("#contajax").html(html);
					}
					else
					{
						$("#contajax").html('Error');
					}
				},
				   beforeSend:function()
				   {
					   $("#contajax").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				   }
				});
			}
			
			
			
    	
  		} );
  	</script>

</body>
</html>