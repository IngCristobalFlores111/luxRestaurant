<?php

require_once('fpdf.php');
require_once('fpdi.php');
$pdf = new FPDI();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("Nota Clovertoursnocurvas.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 10, 10, 200);
$pdf->Image("SAM_4407.jpg",30,120,25);

$pdf->Output("jesu.pdf");

?>