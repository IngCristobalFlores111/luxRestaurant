<?php
include_once("functions.php");
$sql = createMysqliConnection();

$fecha_from = $_POST['fecha_from'];
$fecha_to = $_POST['fecha_to'];
$fecha_from = $sql->filter_input($fecha_from);
$fecha_to = $sql->filter_input($fecha_to);

$query ="SELECT fecha,SUM(`cantidad`) AS cantidad,SUM(total) AS total,SUM(tbventaplatillos.costo) AS costo,SUM(ganancia) AS ganancia,tbplatillos.nombre FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo WHERE fecha BETWEEN '$fecha_from' AND '$fecha_to' GROUP BY tbventaplatillos.idplatillo ORDER BY total DESC LIMIT 6";
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));


?>