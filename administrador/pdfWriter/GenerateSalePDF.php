<?php

require_once('fpdf.php');
require_once('fpdi.php');


$idcliente = $_POST['idcliente'];
$idcliente = trim($idcliente);
$idcliente =htmlspecialchars($idcliente);
$idcliente = str_replace("'","''",$idcliente);

$nombre = $_POST['nombre'];
$nombre = trim($nombre);
$nombre =htmlspecialchars($nombre);
$nombre = str_replace("'","''",$nombre);

$apellido = $_POST['apellido'];
$apellido = trim($apellido);
$apellido =htmlspecialchars($apellido);
$apellido = str_replace("'","''",$apellido);

$email = $_POST['email'];
$email = trim($email);
$email =htmlspecialchars($email);
$email = str_replace("'","''",$email);

$telefono = $_POST['telefono'];
$telefono = trim($telefono);
$telefono =htmlspecialchars($telefono);
$telefono = str_replace("'","''",$telefono);

$ciudad = $_POST['ciudad'];
$ciudad = trim($ciudad);
$ciudad =htmlspecialchars($ciudad);
$ciudad = str_replace("'","''",$ciudad);

$folio = $_POST['folio'];
$folio= trim($folio);
$folio=htmlspecialchars($folio);
$folio= str_replace("'","''",$folio);

$fecha_expedido = $_POST['fecha_expedido'];
$fecha_expedido = trim($fecha_expedido);
$fecha_expedido =htmlspecialchars($fecha_expedido);
$fecha_expedido = str_replace("'","''",$fecha_expedido);

$fecha_partida = $_POST['fecha_partida'];
$fecha_partida = trim($fecha_partida);
$fecha_partida =htmlspecialchars($fecha_partida);
$fecha_partida = str_replace("'","''",$fecha_partida);

$lenguaje = $_POST['lenguaje'];
$lenguaje = trim($lenguaje);
$lenguaje =htmlspecialchars($lenguaje);
$lenguaje = str_replace("'","''",$lenguaje);

$hora_partida = $_POST['hora_partida'];
$hora_partida = trim($hora_partida);
$hora_partida =htmlspecialchars($hora_partida);
$hora_partida = str_replace("'","''",$hora_partida);

$hotel = $_POST['hotel'];
$hotel = trim($hotel);
$hotel =htmlspecialchars($hotel);
$hotel = str_replace("'","''",$hotel);

$cuarto = $_POST['cuarto'];
$cuarto = trim($cuarto);
$cuarto =htmlspecialchars($cuarto);
$cuarto = str_replace("'","''",$cuarto);

$nombre_tour = $_POST['nombre_tour'];
$nombre_tour= trim($nombre_tour);
$nombre_tour=htmlspecialchars($nombre_tour);
$nombre_tour= str_replace("'","''",$nombre_tour);

$pax = $_POST['pax'];
$pax= trim($pax);
$pax=htmlspecialchars($pax);
$pax= str_replace("'","''",$pax);

$rep = $_POST['rep'];
$rep= trim($rep);
$rep=htmlspecialchars($rep);
$rep= str_replace("'","''",$rep);

$deposito = $_POST['deposito'];
$deposito= trim($deposito);
$deposito=htmlspecialchars($deposito);
$deposito= str_replace("'","''",$deposito);

$aceptado = $_POST['aceptado'];
$aceptado= trim($aceptado);
$aceptado=htmlspecialchars($aceptado);
$aceptado= str_replace("'","''",$aceptado);


$total = $_POST['total'];
$total= trim($total);
$total=htmlspecialchars($total);
$total= str_replace("'","''",$total);


$balance = $_POST['balance'];
$balance= trim($balance);
$balance=htmlspecialchars($balance);
$balance= str_replace("'","''",$balance);




$pdf = new FPDI();
// add a page
$pdf->AddPage();
// set the source file
$pdf->setSourceFile("Nota Clovertoursnocurvas.pdf");
// import page 1
$tplIdx = $pdf->importPage(1);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplIdx, 10, 10, 200);

// now write some text above the imported page
$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(255, 0, 0);
$pdf->SetXY(172,17);
$pdf->Write(0, $folio);


$pdf->SetFont('Arial','B',15);
$pdf->SetTextColor(0, 0, 102);
$pdf->SetXY(74,62.5);
$pdf->Write(0, $fecha_expedido);


$pdf->SetXY(53,71);
$pdf->Write(0, $nombre);

$pdf->SetXY(31.5,78.5);
$pdf->Write(0, $hotel);

$pdf->SetXY(61.5,87.5);
$pdf->Write(0,$nombre_tour);

$pdf->SetXY(48,95.5);
$pdf->Write(0, $hora_partida);

$pdf->SetXY(41,103);
$pdf->Write(0, $deposito);

$pdf->SetXY(32,111);
$pdf->Write(0, $total);

$pdf->SetXY(84.5,111);
$pdf->Write(0, $balance);

$pdf->SetXY(166,62.5);
$pdf->Write(0, $fecha_partida);

$pdf->SetXY(144,71);
$pdf->Write(0, $lenguaje);

$pdf->SetXY(146,79);
$pdf->Write(0, $cuarto);

$pdf->SetXY(128,88);
$pdf->Write(0, $pax);

$pdf->SetXY(128,95.5);
$pdf->Write(0, $rep);

$pdf->SetXY(143,102);
$pdf->Write(0, $aceptado);









$pdf->Output();






?>