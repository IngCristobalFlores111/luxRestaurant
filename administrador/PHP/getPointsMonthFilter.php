<?php
include ('functions.php');
$monthFrom = $_POST['monthFrom'];
$monthTo = $_POST['monthTo'];

$yearFrom = $_POST['yearFrom'];
$yearTo = $_POST['yearTo'];

$query ="SELECT SUM(total) AS total,SUM(costo) AS costo,SUM(ganancia) AS ganancia,MONTH(fecha) AS mes,YEAR(fecha) AS ano FROM `tbventaplatillos` WHERE MONTH(fecha) BETWEEN $monthFrom AND $monthTo AND YEAR(fecha) BETWEEN $yearFrom AND $yearTo GROUP BY mes,ano";
$results = execResultSet($query);

$x='';
$totales = '';
$costos = '';
$ganancias ='';
foreach($results as $value)
{

$ano = $value['ano'];
$mes = $value['mes'];

	$x = $x.$mes."-".$ano.":";
	
	$totales =$totales.$value['total'].":";
	$costos = $costos.$value['costo'].":";
	$ganancias = $ganancias.$value['ganancia'].":";

}
echo $x."&".$totales."&".$costos."&".$ganancias;


?>