<?php

session_start();

require_once '/var/www/html/config.php';

require_once $arrIni['base'].'inc/checkACL.php'; 

require_once '/var/www/html/framework/pdf/pdf2text.php';
//require_once $arrIni['base'].'lib/db/db.php';
require_once $arrIni['base'].'lib/db/dbConn.php' ;
		
$con = NConnectionFactory::getConnection();
$con2 = NConnectionFactory::getConnection();

$qry = "SELECT * FROM files WHERE texto = '' LIMIT 0, 1";

//mysql_query("SET NAMES UTF8");

$res = mysql_query($qry);

foreach ($con->query($qry) as $row) {
//if ($res!="") {
//	while ($row = mysql_fetch_array($res)) {
		//$db_texto = mysql_real_escape_string(shell_exec("/usr/local/bin/pdftotext -enc UTF-8 \"/opt/eDocCloud/files/".$row[10]."\" -"));
		$db_texto = shell_exec("/usr/local/bin/pdftotext -enc UTF-8 \"/opt/eDocCloud/files/".$row[10]."\" -");
		echo $db_texto;
		//$qry2 = "UPDATE files SET texto = '".$row[1]." - ".$db_texto."' WHERE row_id = ".$row['row_id'];
		$qry2 = "UPDATE files SET texto = :texto WHERE row_id = ".$row['row_id'];
		$stmt2 = $con2->prepare($qry2);
		$stmt2->bindValue(':texto', $row[1]." - ".$db_texto, PDO::PARAM_STR);
		$stmt2->execute();
		//echo var_dump(stmt2);
		//parametro = ".$row[1]." - ".$db_texto."
		//echo $qry2;
		//$res2 = mysql_query($qry2);	
		//echo "Entra";
//	}
}
echo "Finalizado";

NConnectionFactory::close();







// Viejo 
/*
foreach (new DirectoryIterator('/opt/eDocCloud/upload') as $fileInfo) {
    if($fileInfo->isDot()) continue;
	echo "Customer: ".$fileInfo->getFilename() . "<br>\n";
	GetFileOrder($fileInfo->getFilename());
}


function GetFileOrder($fileCoCo) {
	foreach (new DirectoryIterator('/opt/eDocCloud/upload/'.$fileCoCo) as $fileInfo) {
		if($fileInfo->isDot()) continue;
		echo "Order: ".$fileInfo->getFilename() . "<br>\n";
		GetFileBox($fileInfo->getFilename(),$fileCoCo);
	}
}

function GetFileBox($fileOrder,$fileCoCo) {
	
	foreach (new DirectoryIterator('/opt/eDocCloud/upload/'.$fileCoCo.'/'.$fileOrder) as $fileInfo) {
		if($fileInfo->isDot()) continue;
		echo "Box: ".$fileInfo->getFilename() . "<br>\n";
		GetFileChart($fileInfo->getFilename(),$fileCoCo,$fileOrder);
	}
}

function GetFileChart($fileChart,$fileCoCo,$fileOrder) {
	//$dir = '/opt/eDocCloud/upload/'.$fileCoCo.'/'.$fileOrder.'/'.$fileChart;
	$dir = '/opt/eDocCloud/upload/'.$fileCoCo.'/'.$fileOrder.'/'.$fileChart;
	
	echo "<br>Carpeta ".$fileChart." Iniciada";
	
	// Busco el NOCHART.lock
	foreach (glob($dir.'/NOCHART.lock') as $filename) {
    	$nochart = true;
	}
	
	// Busco el CREATE.lock
	foreach (glob($dir.'/CREATE.lock') as $filename) {
    	$create = true;
	}
	
	if ($nochart) {
		// Tengo los archivos directamente sin Chart (un Archivo por Chart, el nombre del archivo es el nombre del chart)
		// Si tambien esta el CREATE.lock debo cargar el Chart a la base de datos
		foreach (scandir($dir) as $file) {
			if ('.' === $file) continue;
        	if ('..' === $file) continue;
			if ('NOCHART.lock' === $file) continue;
        	if ('CREATE.lock' === $file) continue;
			//echo "PASO";
			// Estructura de Variables de Tabla
			//$db_filename - Nombre de Archivo
			//$db_creadate - Fecha de Creacion
			//$db_moddate - Fecha de Modificacion
			//$db_pages - Paginas
			//$db_filesize - Tamaño
			//$db_pdf_version - Version de PDF
			//$db_fk_empresa - Empresa
			//$db_parent_id - Objeto Antecesor
			//$db_texto - Texto completo del PDF
			$db_path = "";
			
			// Con cada archivo:
			// Primero obtengo los datos del archivo
			$arrValores = getPDFPages('"'.$dir.'/'.$file.'"'); 
			
			// Los valores devueltos
			$db_pages = $arrValores[0];
			$db_creadate = $arrValores[1];
			$db_moddate =  $arrValores[2];
			$db_filesize = $arrValores[3];
			$db_pdf_version = $arrValores[4];
			
			
			// Verifico si quieren crear o no el Chart
			if ($create) {
				// Tengo que crear el Chart Primero
				$arrChart[8];
				$arrChart[0] = $fileCoCo;
				$arrChart[1] = "";
				$arrChart[2] = str_replace(".pdf","",$file);
				$arrChart[3] = $fileChart;
				$arrChart[4] = date("Y-m-d G:i:s");
				$arrChart[5] = "";
				$arrChart[6] = "";
				
				$charId = NewChart($arrChart);
				
				// Valores que ya estaban
				$db_filename = $file;
				$db_parent_id = $charId; 
				$db_fk_empresa = $fileCoCo;
				
				// Busco el texto completo
	
				$db_texto = shell_exec("/usr/local/bin/pdftotext -enc UTF-8 \"".$dir.'/'.$file."\" -");
				
				//$a = new PDF2Text();
				//$a->setFilename($dir.'/'.$file); 
				//$a->decodePDF();
				//$db_texto = $a->output(); 
				//echo $db_texto;
				
				$prevdir = "/opt/eDocCloud/files/";
				$fullDir = "arc/2014/";
				
				// Si la carpeta no existe la creo
				if (!file_exists($prevdir.$fullDir.$fileCoCo.'/'.$fileOrder.'/'.$fileChart.'/'.$charId.'/')) {
					//echo "No Existe";
					mkdir($prevdir.$fullDir.$fileCoCo.'/'.$fileOrder.'/'.$charId, 0777, true);
				} 
				echo '<br><br>Procesando...';
				echo '<br>ORIGEN: '.$dir.'/'.$file;
				echo '<br>DESTINO: '.$prevdir.$fullDir.$fileCoCo.'/'.$fileOrder.'/'.$charId.'/'.$file;
				
				// Ahora muevo el documento a su storage definitivo
				copy($dir.'/'.$file, $prevdir.$fullDir.$fileCoCo.'/'.$fileOrder.'/'.$charId.'/'.$file);
				
				// Borro el original
				unlink($dir.'/'.$file);
				
				// Grabo el documento en la base de datos
	
				$arrFile[8];
				
				$arrFile[0] = $file;
				$arrFile[1] = $db_creadate;
				
				if ($db_moddate=="") {
					$arrFile[2] = $db_creadate;	
				} else {
					$arrFile[2] = $db_moddate;
				}
				
				$arrFile[3] = $db_pages;
				$arrFile[4] = $db_filesize;
				$arrFile[5] = $db_pdf_version;
				$arrFile[6] = $fileCoCo ;
				$arrFile[7] = $charId ;
				$arrFile[8] = $db_texto ;
				$arrFile[9] = $fullDir.$fileCoCo.'/'.$fileOrder.'/'.$charId.'/'.$file ;
				
				
				$fileid = NewFile($arrFile);
				
				
			} else {
				// NO tengo que crear el Chart Primero
				
			}
			
		}
		
	} else {
		// Tengo los archivos directamente con Chart 
		foreach (new DirectoryIterator('/opt/eDocCloud/upload/'.$fileCoCo.'/'.$fileOrder.'/'.$fileChart) as $fileInfo) {
			if($fileInfo->isDot()) continue;
			echo $fileInfo->getFilename() . "<br>\n";
			//GetFiles($fileInfo->getFilename(),$fileCoco);
		}
	}
	
	unlink($dir.'/CREATE.lock');
	unlink($dir.'/NOCHART.lock');
	echo "<br><br>Carpeta ".$fileChart." Finalizada<br>";
	
	// Sumo la carpeta
	SumoArriba($fileChart);
	
}

function SumoArriba() {
	
	$con = ConnectionFactory::getConnection();
	
	// Obtengo el Valor de la Orden

	$qry = "INSERT INTO objects (fk_obj_type,fk_company,f_code,f_name,fk_parent,creation,cScan,fk_status) VALUES";
	$qry = $qry." ($fk_object_type,$arrChart[0],'$arrChart[1]','$arrChart[2]',$arrChart[3],'$arrChart[4]','$cScan',$fk_status)";
	echo $qry;
	//echo $arrChart[4];
	
	$res=mysql_query($qry)
	or die("-1");
	
	ConnectionFactory::close();
}

function NewFile($arrFile) {
	//session_start();
	
	//require_once '/var/www/html/config.php';
	
	//require_once $arrIni['base'].'lib/db/db.php';

	$con = ConnectionFactory::getConnection();
	
	$arrFile[8] = mysql_real_escape_string($arrFile[8]);

	$qry = "INSERT INTO files (filename,creadate,moddate,pages,filesize,pdf_version,fk_empresa,parent_id,texto,path) VALUES";
	$qry = $qry." ('$arrFile[0]','$arrFile[1]','$arrFile[2]',$arrFile[3],$arrFile[4],'$arrFile[5]',$arrFile[6],$arrFile[7],'$arrFile[8]','$arrFile[9]')";
	//echo $qry;
	
	$res=mysql_query($qry)
	or die("-1");
	
	return mysql_insert_id();
	
	}

function NewChart($arrChart) {

	$fk_object_type = 3;
	$cScan = 'X';
	$fk_status = 5;

	$con = ConnectionFactory::getConnection();
	
	$qry = "INSERT INTO objects (fk_obj_type,fk_company,f_code,f_name,fk_parent,creation,cScan,fk_status) VALUES";
	$qry = $qry." ($fk_object_type,$arrChart[0],'$arrChart[1]','$arrChart[2]',$arrChart[3],'$arrChart[4]','$cScan',$fk_status)";
	//echo $arrChart[4];
	echo $qry;
	
	$res=mysql_query($qry)
	or die("-1");
	
	return mysql_insert_id();
	
	}

function getPDFPages($document)
{
	
	//   /opt/eDocCloud/upload
    $cmd = "/var/www/html/framework/pdf/pdfinfo";
	
	
    // Parse entire output
    exec("$cmd $document", $output);
	
	$arrDatos[4];
	//echo $document.'<br>'.$output;
	
    // Iterate through lines
    $pagecount = 0;
    foreach($output as $op)
    {
		$var = substr($op,0,6);
		
		//echo $op;
		
		switch ($var)
		{
			case "Pages:":	
				// Ok
				$val = str_replace("Pages: ","",$op);
				$arrDatos[0] = $val;
				//echo "<br>PAGES: ".$val;
				break;
				
			case "Creati":
				$val = str_replace("CreationDate: ","",$op);
				$val = date("Y-m-d G:i:s",strtotime($val));
				$arrDatos[1] = $val;
				//echo "<br>CREATION: ".$val;
				break;
				
			case "ModDat":
				$val = str_replace("ModDate: ","",$op);
				$val = date("Y-m-d G:i:s",strtotime($val));
				$arrDatos[2] = $val;
				//echo "<br>MODIFICATION: ".$val;
				break;
			
			case "File s":
				$val = str_replace("File size: ","",$op);
				$val = str_replace(" bytes","",$val);
				$arrDatos[3] = $val;
				//echo "<br>SIZE: ".$val;
				break;
			
			case "PDF ve":
				$val = str_replace("PDF version: ","",$op);
				$arrDatos[4] = $val;
				//echo "<br>VERSION: ".$val;
				break;
			
			}
    }

    return $arrDatos;
}
*/
?> 