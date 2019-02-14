<?php
include ('functions.php');
$monthFrom = $_POST['monthFrom'];
$monthTo = $_POST['monthTo'];

$yearFrom = $_POST['yearFrom'];
$yearTo = $_POST['yearTo'];
$nombrePlatillo = $_POST['nomPlatillo'];
// obtener id del platillo





$query ="SELECT SUM(cantidad) AS cantidad,MONTH(fecha) AS mes,YEAR(fecha) AS ano FROM `tbventaplatillos` WHERE MONTH(fecha) BETWEEN $monthFrom AND $monthTo AND YEAR(fecha) BETWEEN $yearFrom AND $yearTo AND `idplatillo`=(SELECT idplatillo FROM tbplatillos WHERE nombre='$nombrePlatillo') GROUP BY mes,ano";
$results = execResultSet($query);

$x='';
$cantidad = '';
foreach($results as $value)
{

	$ano = $value['ano'];
	$mes = $value['mes'];

	$x = $x.$mes."-".$ano.":";
	
	$cantidad = $cantidad.$value['cantidad'].":";

}
echo $x."&".$cantidad;


?>