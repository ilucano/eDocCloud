<?php

session_start();

if (!(isset($_SESSION['Vusername']) && $_SESSION['Vusername'] != '')) {

header ("Location: ../index.php");

}

?>