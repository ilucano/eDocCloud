<?php

session_start();

require_once '/var/www/html/config.php';
//require_once $arrIni['base'].'lib/db/db.php' ;
require_once $arrIni['base'].'lib/db/dbConn.php';

$wfid = basename( $_GET['wfid'] );
$status = basename( $_GET['status'] );

$status = $status + 1;

$con = NConnectionFactory::getConnection();


$strQry = "SELECT * FROM workflow WHERE row_id = ".$wfid." ;";

try {
	foreach ($con->query($strQry) as $row) {
		$qry = "INSERT INTO wf_history (wf_id, fk_status, created, modify, created_by, modify_by) VALUES ('".$row['wf_id']."',".$row['fk_status'].",'".$row['created']."','".$row['modify']."',".$row['created_by'].",".$row['modify_by'].");";
		$stmt = $con->prepare($qry);
		$stmt->execute();
	}
} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
}

$qry = "UPDATE workflow SET fk_status = ".$status.", created = '".date("Y-m-d G:i:s")."', modify = '".date("Y-m-d G:i:s")."',created_by = ".$_SESSION['Vid'].",modify_by = ".$_SESSION['Vid']." WHERE row_id = ".$wfid.";";
$stmt = $con->prepare($qry);
$stmt->execute();

NConnectionFactory::close();

header ("Location: dPrep.php");

?>