<?php
include('functions.php');
$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];

$platillo = $_POST["platillo"];
$query ="SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo'";
$ID = execResultSet($query);


$pointsTotales =  getPointsPlatilloTotales($dateFrom,$dateTo,$ID[0]['idplatillo']);
$pointsCostos =  getPointsPlatilloCostos($dateFrom,$dateTo,$ID[0]['idplatillo']);
$pointsGanancias =  getPointsPlatillGanancias($dateFrom,$dateTo,$ID[0]['idplatillo']);
$output = $pointsTotales."&".$pointsCostos."&".$pointsGanancias;

echo $output;


?>