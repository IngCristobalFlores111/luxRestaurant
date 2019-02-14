<?php
require('fpdf.php');
require('makefont/makefont.php');
$pdf = new FPDF();
$pdf->MakeFont('C:\Users\Dell\Desktop\General Stuff/attachment_14_50b55c3c07f0b.pdf','cp1252');
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'¡Hola, Mundo!');
$pdf->Output();

?>