<?php

session_start();

require_once '/var/www/html/config.php';
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
$vOrdPick = "12314";
$pdf->fact_dev( "Pickup Receipt", $vOrdPick );
$pdf->temporaire( "Order Pickup Receipt" );
// Fecha de Pickup
$pdf->addDate( "03/12/2014");
// Codigo de Cliente
$pdf->addClient("CL01");
$pdf->addPageNumber("1");
// Direccion de Cliente
$pdf->addClientAdresse("Ste\nM. XXXX\n3ème étage\n33, rue d'ailleurs\n75000 PARIS");

//$pdf->addReglement("Chèque à réception de facture");
//$pdf->addEcheance("03/12/2003");
//$pdf->addNumTVA("FR888777666");

// User
$vOrdUser = "Ignacio Lucano";
$pdf->addReference("Picked up by user " . $vOrdUser);
// 190
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
$line = array( "ORDER"    => "REF1",
               "BARCODE"  => "Carte Mère MSI 6378\n" .
                                 "Processeur AMD 1Ghz\n" .
                                 "128Mo SDRAM, 30 Go Disque, CD-ROM, Floppy, Carte vidéo",
               "BOX ID"     => "1",
               "BOX NAME"      => "600.00");
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

$line = array( "ORDER"    => "REF2",
               "BARCODE"  => "Câble RS232",
               "BOX ID"     => "1",
               "BOX NAME"      => "10.00");
$size = $pdf->addLine( $y, $line );
$y   += $size + 2;

//$pdf->addCadreTVAs();
        
// invoice = array( "px_unit" => value,
//                  "qte"     => qte,
//                  "tva"     => code_tva );
// tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5, ... );
// params  = array( "RemiseGlobale" => [0|1],
//                      "remise_tva"     => [1|2...],  // {la remise s'applique sur ce code TVA}
//                      "remise"         => value,     // {montant de la remise}
//                      "remise_percent" => percent,   // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => [0|1],
//                      "portTTC"        => value,     // montant des frais de ports TTC
//                                                     // par defaut la TVA = 19.6 %
//                      "portHT"         => value,     // montant des frais de ports HT
//                      "portTVA"        => tva_value, // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => [0|1],
//                      "accompte"         => value    // montant de l'acompte (TTC)
//                      "accompte_percent" => percent  // pourcentage d'acompte (TTC)
//                  "Remarque" => "texte"              // texte
//$tot_prods = array( array ( "px_unit" => 600, "qte" => 1, "tva" => 1 ),
//                    array ( "px_unit" =>  10, "qte" => 1, "tva" => 1 ));
//$tab_tva = array( "1"       => 19.6,
//                  "2"       => 5.5);
//$params  = array( "RemiseGlobale" => 1,
//                      "remise_tva"     => 1,       // {la remise s'applique sur ce code TVA}
//                      "remise"         => 0,       // {montant de la remise}
//                      "remise_percent" => 10,      // {pourcentage de remise sur ce montant de TVA}
//                  "FraisPort"     => 1,
//                      "portTTC"        => 10,      // montant des frais de ports TTC
                                                   // par defaut la TVA = 19.6 %
//                      "portHT"         => 0,       // montant des frais de ports HT
//                      "portTVA"        => 19.6,    // valeur de la TVA a appliquer sur le montant HT
//                  "AccompteExige" => 1,
//                      "accompte"         => 0,     // montant de l'acompte (TTC)
//                      "accompte_percent" => 15,    // pourcentage d'acompte (TTC)
//                  "Remarque" => "Avec un acompte, svp..." );

//$pdf->addTVAs( $params, $tab_tva, $tot_prods);
//$pdf->addCadreEurosFrancs();
$pdf->Output();

?>