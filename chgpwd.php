<?php 
session_start();

require_once '/var/www/html/lib/db/db.php';
require_once '/var/www/html/config.php';

$varErr = 0;

if (isset($_POST['oldpassword'])) {
	$pwdOld = $_POST['oldpassword'];
	$pwdNew = $_POST['password'];
	
	$con = ConnectionFactory::getConnection();
	
	$qry = "SELECT row_id FROM users WHERE username = '".$_SESSION['Vusername']."' and password = '".$pwdOld."';";
	
	$res = mysql_query($qry);
	$num_row = mysql_num_rows($res);
	
	if ($num_row==1) {
		//echo "OK";
		$qry = "UPDATE users SET password = '".$pwdNew."' WHERE username = '".$_SESSION['Vusername']."';";
		$res = mysql_query($qry);
		if ($res!="") {
			$varErr = 1;
		} else {
			$varErr = -1;
		}
	} else {
		//echo $pwdOld.$_SESSION['Vusername'];
		$varErr = 4;
	}
	
	ConnectionFactory::close();
}

require 'inc/checkACL.php'; 
require 'inc/general.php'; 

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

$page = 'chgpwd';
require 'inc/topbar.php';

?>

<!-- BEGIN OF CONTENT OF THE PAGE -->
<p>
<div class="row">
	<div class="large-12 columns">
    </h2>Change User Password</h2><p>
    </div>
</div>
<div class="row">
<div class="large-1 columns">
</div>
<div class="large-10 columns">

<?php

switch ($varErr) {
	case -1:
		// Error
		echo "Faltal error occured, please try again later";
		break;
	case 1:
		echo "Password sucessfuly changed";
		break;
	case 4:
		echo "Incorrect password, please try again";

?>
<!-- BEGIN OF TAB -->

<form data-abide action="chgpwd.php" method="POST"> 
<div class="password-field"> <label>Old Password <small>required</small> <input type="password" id="oldpassword" name="oldpassword" required pattern="[a-zA-Z]+"> </label> <small class="error">Your password must match the requirements</small> </div>

<div class="password-field"> <label>Password <small>required</small> <input type="password" id="password" name="password" required pattern="[a-zA-Z]+"> </label> <small class="error">Your password must match the requirements</small> </div> 

<div class="password-confirmation-field"> <label>Confirm Password <small>required</small> <input type="password" required pattern="[a-zA-Z]+" data-equalto="password"> </label> <small class="error">The password did not match</small> </div> <button type="submit">Submit</button> </form>

<!-- END OF TAB -->

<?php
		break;
	default:
?>
<!-- BEGIN OF TAB -->

<form data-abide action="chgpwd.php" method="POST"> 
<div class="password-field"> <label>Old Password <small>required</small> <input type="password" id="oldpassword" name="oldpassword" required pattern="[a-zA-Z]+"> </label> <small class="error">Your password must match the requirements</small> </div>

<div class="password-field"> <label>Password <small>required</small> <input type="password" id="password" name="password" required pattern="[a-zA-Z]+"> </label> <small class="error">Your password must match the requirements</small> </div> 

<div class="password-confirmation-field"> <label>Confirm Password <small>required</small> <input type="password" required pattern="[a-zA-Z]+" data-equalto="password"> </label> <small class="error">The password did not match</small> </div> <button type="submit">Submit</button> </form>

<!-- END OF TAB -->

<?php
		break;
	
	}
	

?>

</div>
<div class="large-1 columns">
</div>
</div>



<!-- END OF CONTENT OF THE PAGE -->

	<script src="<?php echo $arrIni['foundationurl']; ?>js/vendor/jquery.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.js"></script>
  	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.topbar.js"></script>
 	<script src="<?php echo $arrIni['foundationurl']; ?>js/foundation/foundation.abide.js"></script>
 	<script src="<?php echo $arrIni['foundationurl']; ?>js/templates.js"></script>
    <script src="<?php echo $arrIni['foundationurl']; ?>js/all.js"></script>
  	<!-- Other JS plugins can be included here -->

  	<script>
    $(document).foundation();
  </script>

</body>
</html>