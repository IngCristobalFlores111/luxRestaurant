<?php
include_once("functions.php");
$sql = createMysqliConnection();

$fecha_from = $_POST['fecha_from'];
$fecha_to = $_POST['fecha_to'];
$por_platillo = $_POST['por_platillo'];
$platillo = $_POST['idPlatillo'];
$fecha_from = $sql->filter_input($fecha_from);
$fecha_to = $sql->filter_input($fecha_to);
$platillo = $sql->filter_input($platillo);
$extraWhere= '';
if($por_platillo=='1'){
    $extraWhere = "AND tbventaplatillos.idplatillo=".$platillo;
}
$query ="SELECT fecha,SUM(`cantidad`) AS cantidad,SUM(total) AS total,SUM(tbventaplatillos.costo) AS costo,SUM(ganancia) AS ganancia,tbplatillos.nombre FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo WHERE fecha BETWEEN '$fecha_from' AND '$fecha_to' $extraWhere GROUP BY fecha,tbventaplatillos.idplatillo ORDER BY fecha ASC";
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));


?>