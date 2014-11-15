<?php

session_start();
// Carga variables
require_once '/var/www/html/config.php';

// Verifica si ya se logeo el usuario
if ((isset($_SESSION['Vusername']) && $_SESSION['Vusername']!="")) {
	
	//echo $_SESSION['Vusername'];
	header ("Location: main.php");

}

//echo $arrIni['foundationurl'];

?>

<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to eDocCloud - imagingXperts</title>
    <link rel="stylesheet" href="<?php echo $arrIni['foundationurl']; ?>css/foundation.css" />
    <script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/modernizr.js"></script>

  </head>
<body>
  <br>
  <div class="row">
      <div class="large-12 columns">
        <font size="10">eDocCloud</font> <font size="6"> by imagingXperts</font>
      </div>
  </div>
<div class="row">
      <div class="large-2 columns">
      	<h2><img src="<?php echo $arrIni['logourl']; ?>" width="100" height="100" alt="Logo imagingXperts"></h2>
      </div>
      <div class="large-10 columns">
      	<h3 class="medium-text-left">The Document Management eXperts</h3>
  </div>
   </div>  
  
  	<p><br></p>
  
  	<div name="theForm" class="row">
        	<form id="myForm" data-abide="ajax" action="login.php" method="post">
                	<div class="large-3 columns">
                    &nbsp;
                    </div>
                    <?php
                    if ($inMaint=='Y') {
					?>
                    <div class="large-6 columns">
                    <div data-alert="" class="alert-box info radius">We are currently under maintenance, please try again in a few minutes.</div>
    				
                    <?php
					} else {
					?>
    				<div class="large-6 columns">
                    <div data-alert="" class="alert-box info radius">
  					You are about to access a imagingXperts computer system. Access to this system is restricted to authorized users only. Unauthorized access, use, or modification of this system or of data contained herein, or in transit to/from this system, may constitute a violation of laws.
  <a href="#" class="close">&times;</a>
</div>
	<fieldset>
   	<legend>Login to eDocCloud</legend>
<div class="err" id="add_err"></div>
    <label>Username
		<input type="text" tabindex="0" name="username" id="username" placeholder="Enter User">
    </label>	
    <label>Password
		<input type="password"  name="pass" id="pass" placeholder="Enter Password">
    </label>
    <div role="button small" aria-label="submit form" id="login" name="login" class="button">Login</div>
  	</fieldset>
    				<?php
					}
					?>
    				</div>
                    
                    <div class="large-3 columns">
                    &nbsp;
                    </div>
			</form>
   	</div>
   
   
   	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
   	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/fastclick.js"></script>
	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation.min.js"></script>

<script type="text/javascript">
var varIn = '';
 $('input').on('keydown', function (e) {
	 if (e.which==13) {
		 	if (varIn=='ok') {
				location.href='main.php';
			} else {
		 		$("#login").click();
			}
		 }
	 });
	 
	 
$(document).ready(function(){

	 
 $("#login").click(function(){

  username=$("#username").val();
  password=$("#pass").val();
  $.ajax({
   type: "POST",
   url: "login.php",
   data: "username="+username+"&pass="+password,
   success: function(html){
    if(html=="true")
    {
		varIn='ok';
		$("#username").val("");
		$("#pass").val("");
		$("#add_err").html("<div id=\"firstModal\" class=\"reveal-modal\" data-reveal><h2>Welcome to eDocCloud.</h2><p>Congratulations! You are successfully logged in to your account</code></p><p><a href=\"main.php\" class=\"secondary button\">Go to eDocCloud</a></p></div>");
		$('#firstModal').foundation('reveal', 'open');
		
    }
    else
    {
		$("#username").val("");
		$("#pass").val("");
     $("#add_err").html("<div data-alert class=\"alert-box alert round\">Wrong username or password<a href=\"#\" class=\"close\">&times;</a></div>");
    }
   },
   beforeSend:function()
   {
   }
  });
  return false;
 });
});
</script>

    <script>
  		$(document).foundation();
	</script>
</body>
  </head>