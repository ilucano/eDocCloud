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
	<link href="<?php echo $arrIni['foundationurl']; ?>css/jquery.dataTables.css" rel="stylesheet" />
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

$page = 'filebrowser';
require $arrIni['base'].'inc/topbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<div class="row">
	<div class="large-12 columns">
    <h3>File Browser</h2><p>
    </div>
</div>

<div class="row">
  <div class="large-12 columns" id="result">

  </div>
</div>
<div class="row">
  <div class="large-12 columns" id="result-year">

  </div>
</div>

<div class="row">
  <div class="large-8 columns" id="result-alphabet">

  </div>
</div>

<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.dataTables.min.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.tab.js"></script>
	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.dropdown.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
  	<!-- Other JS plugins can be included here -->

  	<script>
	  
		  $(document).foundation();
		
		  $(document).ready(function () {
			 $.ajax({
				  type: "GET",
				  url: "lib/filebrowser/browser.php",
				  data: "action=listyear",
				  success: function(html){
				   if(html!="")
				   {
					   $("#result").html(html);
				   }
				   else
				   {
					   $("#result").html('Error');
				   }
			   },
				  beforeSend:function()
				  {
					   $("#result").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				  }
			   })
			
			
		   }
		  );
		
		
		
		$(document).on("click", "a[data-list-year]", function() {
    		
			
			vYear=$(this).attr('data-list-year');
			 
			 $.ajax({
				  type: "GET",
				  url: "lib/filebrowser/browser.php",
				  data: "action=listalphabet&year="+vYear,
				  success: function(html){
				   if(html!="")
				   {
					   $("#result-year").html(html);
				   }
				   else
				   {
					   $("#result-year").html('Error');
				   }
			   },
				  beforeSend:function()
				  {
					   $("#result-year").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				  }
			   })
    	
  		} );
		
		
		$(document).on("click", "a[data-list-file]", function() {
    		
			
			vYear=$(this).attr('data-list-alpha-year');
			 vAlpha=$(this).attr('data-list-file');
			 $.ajax({
				  type: "GET",
				  url: "lib/filebrowser/browser.php",
				  data: "action=listfile&year="+vYear+"&alphabet="+vAlpha,
				  success: function(html){
				   if(html!="")
				   {
					   $("#result-alphabet").html(html);
					    $('#list-file-table').DataTable();
				   }
				   else
				   {
					   $("#result-alphabet").html('Error');
				   }
			   },
				  beforeSend:function()
				  {
					   $("#result-alphabet").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
				  }
			   })
    	
  		} );
		
		
  	</script>

</body>
</html>