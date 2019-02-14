<?php
include('functions.php');
$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];
$result = execResultSet("SELECT SUM(total) AS total,SUM(costo) AS costo,SUM(ganancia) AS ganancia FROM tbventaplatillos WHERE fecha BETWEEN '$dateFrom' AND '$dateTo'");
$total = $result[0]['total'];
$costo = $result[0]['costo'];
$ganancia= $result[0]['ganancia'];
echo $total.":".$costo.":".$ganancia;

?>