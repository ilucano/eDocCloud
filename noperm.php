<?php 
require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/check.php'; 
require_once $arrIni['base'].'lib/comp/news.php'; 
require_once $arrIni['base'].'lib/comp/notif.php'; 
require_once $arrIni['base'].'lib/comp/stats.php';


?>
<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to eDocCloud - imagingXperts</title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link href="<?php echo $arrIni['foundationurl']; ?>css/docs.css" rel="stylesheet" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet" />
    <script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/modernizr.js"></script>

  </head>
<body>
<?php
$page = 'main';
require $arrIni['base'].'inc/topbar.php';

?>


<!-- BEGIN OF CONTENT OF THE PAGE -->


<div class="row">You don't have permissions to access the page</div>


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