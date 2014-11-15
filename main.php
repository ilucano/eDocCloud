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


<p><br><br></p>
<div class="row">
	<div class="large-7 columns">
    
    <!--Inicia Tabla de Notificaciones -->
    <table>
        <thead>
            <tr>
              <th>Notifications</th>
            </tr>
        </thead>
		<tbody>
            <?php
            
			GetNotifications(5);
			
			?>
        </tbody>
    </table>
    <p><br></p>
    <!--Inicia Tabla de Stats -->
    <table>
        <thead>
            <tr>
              <th>Stats</th>
            </tr>
        </thead>
		<tbody>
            <?php
			
			GetStats(5);
			
            ?>
        </tbody>
    </table>
    
    </div>
	<div class="large-5 columns">
    
    <!--Inician las News -->
    
    <table>
        <thead>
            <tr>
              <th>News</th>
            </tr>
        </thead>
		<tbody>
            <?php
            
			GetNews(4);
			
			?>
        </tbody>
    </table>
    
    </div>
</div>


<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.accordion.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>

  	<!-- Other JS plugins can be included here -->

  	<script>
		
		$(document).foundation({
			accordion: {
			  callback : function (accordion) {
				console.log(accordion);
			  }
			}
  		});
		
		$(document).on("click", "a[data-reveal-id]", function() {
    		
			
			vId=$(this).attr('data-reveal-id');
			vType=$(this).attr('link-type');
			vOrder=$(this).attr('link-order');
			vBox=$(this).attr('link-box');
			vChart=$(this).attr('link-chart');
			
			 if (vType=="file") {
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