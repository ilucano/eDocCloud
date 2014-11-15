<?php

function SendEmail($to, $vUser, $vPass, $vName) {
// multiple recipients
//$to  = 'ilucano@cwcorp.us';// . ', '; // note the comma
//$to .= 'wez@example.com';

// subject
$subject = 'Welcome to eDocCloud by imagingXperts!';

// message
$message = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<style type="text/css">
.Centerbo {
	text-align: center;
}
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="width : 700px; table-layout: fixed;">
  <tr>
    <td width="10%" nowrap="nowrap">&nbsp;</td>
    <td width="80%" nowrap="nowrap" class="Centerbo"><img src="http://<?php echo $_SERVER['SERVER_NAME'];?>/images/banerMail.jpg" width="600" height="75" /></td>
    <td width="10%" nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap">&nbsp;</td>
  </tr>
  <tr>
    <td nowrap="nowrap">&nbsp;</td>
    <td nowrap="nowrap" style="width: 700px; overflow: hidden;">Dear '.$vName.',
      </p>
      <p>Your company is registered to use <span class="Bold">eDocCloud</span> the eDocument platform by imagingXperts.</p>
      <p>This email is to confirm that your account has been activated and now you are able
        to access your records anytime, anywhere, and from any device. The very essence of <span class="Bold">eDocCloud</span> is to make documents available in seconds in a secure and responsive environment.</p>
      <p>Start using <span class="Bold">eDocCloud</span> now! Below you\'ll find your login and password informatiom. We strongly
        recommend to change it the first time you use it.</p>
      <p><span class="Bold">URL:</span> <a href="http://<?php echo $_SERVER['SERVER_NAME'];?>"><?php echo $_SERVER['SERVER_NAME'];?></a></p>
      <p><span class="Bold">Username:</span> '.$vUser.'</p>
      <p><span class="Bold">Password:</span> '.$vPass.'</p>
      <p>We are constantly improving our services and we would love to hear from you!</p>
      <p>Please call us or send us an email if you think there is anythingwe could do to improve eDocCloud functionality.</p>
      <p>&nbsp;</p>
      <p>The Customer Support team at imagingXperts.</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p><span class="Bold">Email us:</span> <a href="mailto:support@imagingxperts.com">support@imagingxperts.com</a></p>
      <p><span class="Bold">Web:</span> <a href="http://www.imagingxperts.com">www.imagingXperts.com</a></p>
    <p><span class="Bold">Phone:</span> 305-571-1790</p>
    </td>
    <td nowrap="nowrap">&nbsp;</td>
  </tr>
</table>
</body>
</html>

';

// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

// Additional headers
//$headers .= 'To: ' . $to . "\r\n";
$headers .= 'From: "The eDocCloud Team" <info@imagingxperts.com>' . "\r\n";

// Mail it
mail($to, $subject, $message, $headers);

}

?>
