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

$page = 'search';
require $arrIni['base'].'inc/topbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<div class="row">
	<div class="large-12 columns">
    <h2>Search for files</h2><p>
    </div>
</div>
<div class="row">
<div class="large-1 columns">
</div>
<div class="large-10 columns">

<!-- BEGIN OF TAB -->

<dl class="tabs" data-tab>
	<dd class="active"><a href="#panel1">Full Text Search</a></dd>
    <dd><a href="#panel2">File Name</a></dd>
</dl>
	<div class="tabs-content">
        <div class="content active" id="panel1">
        <!-- BEGIN OF TAB FULL TEXT SEARCH -->
        	<div class="row">
            <div class="large-1 columns"></div>
            <div class="large-10 columns">
            <!-- <form> -->
            <div class="row collapse postfix-round">
            	<div class="small-2 columns">
                	<span class="prefix">Content</span>
                </div>
                <div class="small-8 columns">
                  <input id="texto" type="text" placeholder="">
                </div>
                <div class="small-2 columns">
                  <a href="#" data-reveal-id="buscar" data-type="fulltext" class="button postfix">Search</a>
                </div>
            </div>
            <!-- </form> -->
            </div>
            <div class="large-1 columns"></div>
            </div>
            <div name="tab1res" id="tab1res" class="row">
            <div class="large-1 columns"></div>
            <div class="large-10 columns">
            
            </div>
            <div class="large-1 columns"></div>
            </div>
        	
        <!-- END OF TAB  FULL TEXT SEARCH -->
        </div>
        <div class="content" id="panel2">
        <!-- BEGIN OF TAB NAME SEARCH -->
        <div class="row">
            <div class="large-1 columns"></div>
            <div class="large-10 columns">
            <!-- <form> -->
            <div class="row collapse postfix-round">
            	<div class="small-2 columns">
                	<span class="prefix">Name</span>
                </div>
                <div class="small-8 columns">
                  <input name="textoN" id="textoN" type="text" placeholder="">
                </div>
                <div class="small-2 columns">
                  <a href="#" data-reveal-id="buscar" data-type="namecode" class="button postfix">Search</a>
                </div>
            </div>
            <!-- </form> -->
            </div>
            <div class="large-1 columns"></div>
            </div>
            <div name="tab2res" id="tab2res" class="row">
            <div class="large-1 columns"></div>
            <div class="large-10 columns">
            
            </div>
            <div class="large-1 columns"></div>
            </div>
            <!-- END OF TAB NAME SEARCH -->
        </div>
    </div>

<!-- END OF TAB -->

</div>
<div class="large-1 columns">
</div>
</div>



<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.tab.js"></script>
	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.dropdown.js"></script>
	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.reveal.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
  	<!-- Other JS plugins can be included here -->

  	<script>
		var varIn = '';
		 $('input').on('keydown', function (e) {
			 if (e.which==13) {
				 	vId='buscar';
					vTexto=$("#texto").val();
					vTextoN=$("#textoN").val();
				 	if (e.currentTarget.id=='texto') {
						vVar='fulltext';
					} else {
						vVar='namecode';
					}
					buscar(vId, vTexto, vTextoN, vVar);
				 }
			 });
    	
		$(document).on("click", "a[data-reveal-id]", function() {
    		
			
			vId=$(this).attr('data-reveal-id');
			vTexto=$("#texto").val();
			vTextoN=$("#textoN").val();
			vVar=$(this).attr('data-type');
			vPag = $(this).attr('data-page');
			buscar(vId, vTexto, vTextoN, vVar, vPag);
			
			
    	
  		} );
		
		function buscar(vId, vTexto, vTextoN, vVar, vPag) {
			if (vId=="buscar")
			{
				if (vVar=="fulltext") {
					$.ajax({
					   type: "GET",
					   url: "lib/search/ftsearch.php",
					   data: "texto="+vTexto+"&pagAct="+vPag,
					   success: function(html){
						if(html!="")
						{
							$("#tab1res").html(html);
						}
						else
						{
							$("#tab1res").html('Error');
						}
					},
					   beforeSend:function()
					   {
							$("#tab1res").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
					   }
					});
				} else if (vVar=="pagina") {
					//vPag=$(this).attr('data-page');
					$.ajax({
					   type: "GET",
					   url: "lib/search/ftsearch.php",
					   data: "texto="+vTexto+"&pagAct="+vPag,
					   success: function(html){
						if(html!="")
						{
							$("#tab1res").html(html);
						}
						else
						{
							$("#tab1res").html('Error');
						}
					},
					   beforeSend:function()
					   {
							$("#tab1res").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
					   }
					});
				}  else if (vVar=="paginaN") {
					//vPag=$(this).attr('data-page');
					$.ajax({
					   type: "GET",
					   url: "lib/search/ftsearchn.php",
					   data: "texto="+vTextoN+"&pagAct="+vPag,
					   success: function(html){
						if(html!="")
						{
							$("#tab2res").html(html);
						}
						else
						{
							$("#tab2res").html('Error');
						}
					},
					   beforeSend:function()
					   {
							$("#tab1res").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
					   }
					});
				} else {
					$.ajax({
					   type: "GET",
					   url: "lib/search/ftsearchn.php",
					   data: "texto="+vTextoN+"&pagAct="+vPag,
					   success: function(html){
						if(html!="")
						{
							$("#tab2res").html(html);
						}
						else
						{
							$("#tab2res").html('Error');
						}
					},
					   beforeSend:function()
					   {
							$("#tab2res").html('&nbsp;&nbsp;<img heigth="25" width="25" src="/images/loader.gif" />  Loading...');
					   }
					});
				}
			} 	
			};
		
		$(document).foundation();
  	</script>

</body>
</html>