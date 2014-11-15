<?php 
session_start();

require_once $arrIni['base'].'/var/www/html/config.php';
require_once $arrIni['base'].'inc/general.php';
require_once $arrIni['base'].'inc/checkACL.php'; 

?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to eDocCloud - imagingXperts</title>
    <link rel="stylesheet" href="<?php echo $arrIni['foundationurl']; ?>css/foundation.css" />
    <link href="<?php echo $arrIni['foundationurl']; ?>css/docs.css" rel="stylesheet" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" />
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

$page = 'none';
require $arrIni['base'].'inc/topbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<div class="row">
	<div class="large-2 columns">&nbsp;</div>
    <div class="large-8 columns">
    <!--Inicia News -->
	<?php
	
	require_once($arrIni['base'].'lib/db/db.php');
	
	$con = ConnectionFactory::getConnection();
    
	mysql_query("SET NAMES UTF8");
	$qry = "SELECT * FROM news WHERE row_id = ".$_GET['id'].";";
	
	$res = mysql_query($qry);
	$num_row = mysql_num_rows($res);
	
	if ($num_row==1) {
		while ($row = mysql_fetch_array($res)) {
	
	?>
    
	<div class="row"><h1><?php echo $row['title'];?></h1>
    </div>
    <div class="row"><h5 class="subheader">Author: <?php echo $row['author'];?></h5>
    </div><br>
    <div class="row"><img src="<?php echo $row['url'];?>">
    </div><br>
    <div class="row"><p class="text-justify"><?php echo $row['texto'];?></p>
    </div><br>
    
	<?php
	
		}
	}
	
	ConnectionFactory::close();
	
	?>
    <!--Finaliza News -->
    </div>
    <div class="large-2 columns">&nbsp;</div>
</div>


<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
  	<!-- Other JS plugins can be included here -->

  	<script>
    	$(document).foundation();
  	</script>

</body>
</html>