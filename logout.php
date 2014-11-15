<?php
 session_start();
	unset($_SESSION['Vid']);
	unset($_SESSION['Vusername']);
	unset($_SESSION['Vemail']);
	unset($_SESSION['Vfirst_name']);
	unset($_SESSION['Vlast_name']);
	unset($_SESSION['CoCo']);
 header('Location: index.php');
?>