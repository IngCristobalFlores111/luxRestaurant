<?php
include('functions.php');
$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];
$query ="SELECT SUM(total) AS total,SUM(costo) AS costo,SUM(ganancia) AS ganancia FROM tbventaplatillos WHERE fecha BETWEEN '$dateFrom' AND '$dateTo'";
$result = execResultSet($query);
$result = $result[0];
print_r(json_encode($result,JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
?>