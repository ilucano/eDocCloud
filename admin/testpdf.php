<?php
	

	$document = '"/opt/eDocCloud/upload/3/838/1016/Vignau, Pierre 2.pdf"';
	
	$result = shell_exec("/usr/local/bin/pdftotext $document -");
	
	echo $result;
	
	//$tmpfile = '"/opt/eDocCloud/temp/tempfile"';
	
    // Parse entire output
//    exec("pdftotext $document /opt/eDocCloud/temp/tempfile", $output);
//	echo "pdftotext $document /opt/eDocCloud/temp/tempfile";
	
	//$myfile = fopen($tmpfile, "r") or die("Unable to open file!");
	//echo fread($myfile,filesize($tmpfile));
	//fclose($myfile);
	
	//echo $myfile;


?>