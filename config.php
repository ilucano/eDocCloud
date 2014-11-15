<?php

// Leo las variables generales
$arrIni = parse_ini_file('/opt/eDocCloud/general.config');

// Cambios en las variables
$arrIni['foundationurl'] = $arrIni['http'].$_SERVER['SERVER_NAME'].$arrIni['foundationurl'];
$arrIni['logourl'] = $arrIni['http'].$_SERVER['SERVER_NAME'].$arrIni['logourl'];
$arrIni['dbinc'] = $arrIni['http'].$_SERVER['SERVER_NAME'].$arrIni['dbinc'];
$arrIni['base'] = $arrIni['base'];
$inMaint = '';

?>