<?php
include('functions.php');
$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];




$pointsTotales =  getPointsTotales($dateFrom,$dateTo);
$pointsCostos =  getPointsCostos($dateFrom,$dateTo);
$pointsGanancias =  getPointsGanancias($dateFrom,$dateTo);
$output = $pointsTotales."&".$pointsCostos."&".$pointsGanancias;

echo $output;


?>