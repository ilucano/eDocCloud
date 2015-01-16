<?php

require_once '/var/www/html/config.php';
require_once '/var/www/html/framework/nusoap/nusoap.php';
require_once '/var/www/html/lib/db/db.php' ;

$con = ConnectionFactory::getConnection();
	
$qry = "DELETE FROM apitoken WHERE dateto < NOW();";
$res=mysql_query($qry) 
	or die("-1");

?>