<?php
session_start();

require_once '/var/www/html/config.php';

require_once($arrIni['base'].'lib/db/db.php');

$con = ConnectionFactory::getConnection();

$uName = $_POST['username'];
$pWord = $_POST['pass'];

$qry = "SELECT company_admin, row_id, username, password, first_name, last_name, email, is_admin, fk_empresa FROM users WHERE username='".$uName."' AND password='".$pWord."' AND status IN ('A','X')";
$res = mysql_query($qry);
$num_row = mysql_num_rows($res);
$row=mysql_fetch_assoc($res);
if( $num_row == 1 ) {
	echo 'true';
	$_SESSION['Vid'] = $row['row_id'];
	$_SESSION['Vcadm'] = $row['company_admin'];
	$_SESSION['Vusername'] = $row['username'];
	$_SESSION['Vemail'] = $row['email'];
	$_SESSION['Vfirst_name'] = $row['first_name'];
	$_SESSION['Vlast_name'] = $row['last_name'];
	$_SESSION['VisAdmin'] = $row['is_admin'];
	$_SESSION['CoCo'] = $row['fk_empresa'];
	}
else {
	echo 'false';
}

ConnectionFactory::close();

?>