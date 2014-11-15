<?php

session_start();

require_once '/var/www/html/config.php';
require_once $arrIni['base'].'lib/db/dbConn.php';

$qty = basename( $_GET['qty'] );
$orden = basename( $_GET['orden'] );

$con = NConnectionFactory::getConnection();

// Traigo datos de la Orden
$strQry = "SELECT T1.*, (T2.company_email) as company_email, (T2.company_name) as company_name, (T2.company_address1) as company_address1, (T2.company_address2) as company_address2, (T2.company_zip) as company_zip  FROM objects T1 INNER JOIN companies T2 ON T1.fk_company = T2.row_id WHERE T1.row_id = ".$orden." ;";

//echo $strQry;

try {
		
	foreach ($con->query($strQry) as $row) {
		$vCompany = $row['fk_company'];	
		
		$vCompEmail = $row['company_email'];	
		$vCompName = $row['company_name'];
		$vCompAddr1 = $row['company_address1'];
		$vCompAddr2 = $row['company_address2'];
		$vCompZip = $row['company_zip'];
		
	}
	
	//echo "Creation successful";
} catch(PDOException $ex) {
	echo "An Error occured!"; //user friendly message
}

$arrBarcode = array();
$arrBox = array();
$arrBoxName = array();

for ($i = 1; $i <= $qty; $i++) {
	
	if (basename( $_GET['barcode'.$i] )=="on") {
		$barc = basename( $_GET['barcodev'.$i] );
	
		$arrBarcode[$i] = $barc;
		
		// Creo un Box por cada Barcode
		$qryBox = "INSERT INTO objects (fk_obj_type, fk_company, f_code, fk_parent, creation, pickup, fk_status) VALUES (2,";
		$qryBox = $qryBox.$vCompany.",'".substr($barc,0,6).substr($barc,9)."',".$orden.",'".date("Y-m-d G:i:s")."','".date("Y-m-d G:i:s")."',1);";
		
		$stmt = $con->prepare($qryBox);
		$stmt->execute();
		$boxId = $con->lastInsertId();
		
		if ($boxId<>"") {
			$arrBox[$i] = $boxId;
			$arrBoxName[$i] = substr($barc,0,6).substr($barc,9);
			
			// Creo un Pickup por cada Barcode
			
			$qryPickup = "INSERT INTO pickup (fk_user, fk_company, fk_order, fk_barcode, fk_box, timestamp) VALUES (";
			$qryPickup = $qryPickup.$_SESSION['Vid'].",".$vCompany.",".$orden.",'".$barc."',".$boxId.",'".date("Y-m-d G:i:s")."');";
			
			$stmt = $con->prepare($qryPickup);
			$stmt->execute();
			$pickupId = $con->lastInsertId();
			
			if ($pickupId<>"") {
				// Creo un WF por cada Barcode
				
				$qryWF = "INSERT INTO workflow (wf_id, fk_status, modify, created, modify_by, created_by) VALUES (";
				$qryWF = $qryWF.$barc.",3,'".date("Y-m-d G:i:s")."','".date("Y-m-d G:i:s")."',".$_SESSION['Vid'].",".$_SESSION['Vid'].");";
				$stmt = $con->prepare($qryWF);
				$stmt->execute();
				
				// Borro el Barcode
				
				$qryDelBC = "DELETE FROM barcodes WHERE barcode = ".$barc.";";
				$stmt = $con->prepare($qryDelBC);
				$stmt->execute();
			}
		}
	}
}

NConnectionFactory::close();

// fpdf object
require_once $arrIni['base'].'framework/pdfgen/receipt.php';

$pdf = new PDF_Invoice( 'P', 'mm', 'Letter' );
$pdf->AddPage();
//Load an image into a variable
$logo = $arrIni['base'].'images/LogoImaging.png';
//Output it
$pdf->Image($logo, 8, 7);

$pdf->addSociete( "imagingXperts LLC",
				  "12250 SW 129 CT\n" .
				  "SUITE 108\n" .
                  "MIAMI, FL 33186\n".
                  "United States of America\n");
// Nro de Pickup
$vOrdPick = $pickupId;
$pdf->fact_dev( "Pickup Receipt", $vOrdPick );
$pdf->temporaire( "  Pickup Receipt" );
// Fecha de Pickup
$pdf->addDate( date("Y-m-d") );
// Codigo de Cliente
$pdf->addClient($vCompany);
$pdf->addPageNumber("1");
// Direccion de Cliente
$pdf->addClientAdresse($vCompName."\n".$vCompAddr1."\n".$vCompAddr2."\n".$vCompZip."\n");

// User
$vOrdUser = $_SESSION['Vfirst_name'].' '.$_SESSION['Vlast_name'];
$pdf->addReference("Picked up by user " . $vOrdUser);

$cols=array( "ORDER"    => 60,
             "BARCODE"  => 40,
             "BOX ID"     => 30,
             "BOX NAME"      => 60);
$pdf->addCols( $cols);

$cols=array( "ORDER"    => "C",
             "BARCODE"  => "C",
             "BOX ID"     => "C",
             "BOX NAME"      => "L");
$pdf->addLineFormat( $cols);
$pdf->addLineFormat($cols);

$y    = 109;

for ($i = 1; $i <= $qty; $i++) {
	if (basename( $_GET['barcode'.$i] )=="on") {
		
		$barc = basename( $_GET['barcodev'.$i] );
		
		$line = array( "ORDER"    => $orden,
					   "BARCODE"  => $arrBarcode[$i],
					   "BOX ID"     => $arrBox[$i],
					   "BOX NAME"      => $arrBoxName[$i]);
		$size = $pdf->addLine( $y, $line );
		$y   += $size + 2;
	}
}

// email stuff (change data below)
	//$vCompName 
$to = $vCompEmail." ,info@imagingxperts.com"; 
$from = '"eDocCloud Team" <info@imagingxperts.com>'; 
$subject = "Your eDocCloud Order was Picked Up"; 
$message = "<p>".$vCompName." administrator, <br>Recently we have picked up some boxes from your office. Please see the attachment for details.</p>";
// a random hash will be necessary to send mixed content
$separator = md5(time());
// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;
// attachment name
$filename = "Order".$orden."Receipt.pdf";
// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output("", "S");
$attachment = chunk_split(base64_encode($pdfdoc));
// main header (multipart mandatory)
$headers  = "From: ".$from.$eol;
$headers .= "MIME-Version: 1.0".$eol; 
$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol; 
$headers .= "Content-Transfer-Encoding: 7bit".$eol;
$headers .= "This is a MIME encoded message.".$eol.$eol;
// message
$headers .= "--".$separator.$eol;
$headers .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
$headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
$headers .= $message.$eol.$eol;
// attachment
$headers .= "--".$separator.$eol;
$headers .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol; 
$headers .= "Content-Transfer-Encoding: base64".$eol;
$headers .= "Content-Disposition: attachment".$eol.$eol;
$headers .= $attachment.$eol.$eol;
$headers .= "--".$separator."--";
// send message
mail($to, $subject, "", $headers);

echo $orden;

?>