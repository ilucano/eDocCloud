<?php
session_start();
$uId = $_GET['id'];
$uUrl = $_GET['url'];

$_SESSION['CoCo'] = $uId;
echo $_SESSION['CoCo'];

header("Location: ".$uUrl);
exit;

?>