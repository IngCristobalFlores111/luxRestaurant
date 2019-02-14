<?php
include ('new/functions.php');
$sql = createMysqliConnection();

// obtener datos la primera graficas, el total de ventas generadas en la semana
$query = "SELECT SUM(total) AS total,fecha FROM `tbventaplatillos` where fecha between date_sub(now(),INTERVAL 1 WEEK) and now() GROUP BY DAY(fecha) ORDER BY fecha ASC";
$obj1 = $sql->executeQuery($query);
if(count($obj1)==0){
    $obj1 =array();
}
$query = "SELECT p.nombre AS platillo,SUM(v.cantidad) AS cantidad FROM tbventaplatillos v INNER JOIN tbplatillos p ON v.idplatillo = p.idplatillo GROUP BY v.idplatillo ORDER BY v.cantidad DESC LIMIT 5";
$obj2 = $sql->executeQuery($query);  // top5 platillos
if(count($obj2)==0){
    $obj2 =array();
}
$query = "SELECT SUM(total) AS total,SUM(costo) AS costo, SUM(ganancia) AS ganancia,fecha FROM `tbventaplatillos` WHERE MONTH(fecha) = MONTH(now()) GROUP BY fecha ORDER BY fecha ASC";
$obj3 = $sql->executeQuery($query);
if(count($obj3)==0){
    $obj3 =array();
}
//print_r(json_encode(array("ventas_semana"=>$obj1,"top5_platillos"=>$obj2,"ventas_mes"=>$obj3)));

$output = array("ventas_semana"=>$obj1,"top5_platillos"=>$obj2,"ventas_mes"=>$obj3);
print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));




?>