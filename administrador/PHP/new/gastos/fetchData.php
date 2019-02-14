<?php
if(isset($_GET['accion']))
{
    include_once("../functions.php");
$sql = createMysqliConnection();
switch($_GET['accion']){
case "categorias":
$query="SELECT nombre,`idGastoCategoria` AS id FROM `tbgastos_categorias`";
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


break;
case "gastos":
$query="SELECT tbgastos_categorias.idGastoCategoria,tbgastos_categorias.nombre AS categoria,tbgastos.`idGasto`,tbgastos.`nombre`,tbgastos.`descripcion`,tbgastos.`total`,tbgastos.`fecha` FROM `tbgastos` INNER JOIN tbgastos_categorias ON tbgastos_categorias.idGastoCategoria = tbgastos.`idCategoriaGasto`";
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


break;
case "ganancias":
$rango = $_GET['rango'];
if($rango=="0"){
$query="SELECT SUM(gastos) AS gastos,SUM(total)AS total,SUM(ganancia) - SUM(gastos) AS gananciaTotal,fecha FROM (SELECT SUM(total) AS total,SUM(ganancia) AS ganancia,0 AS gastos,fecha FROM `tbventaplatillos` GROUP BY DAY(fecha) UNION SELECT 0 AS total,0 AS ganancia ,SUM(total) AS gastos,fecha FROM tbgastos GROUP BY DAY(fecha) ORDER BY `fecha` DESC) pija GROUP BY DAY(fecha) ORDER BY fecha DESC";
}else{
    $query="SELECT SUM(gastos) AS gastos,SUM(total)AS total,SUM(ganancia)
     - SUM(gastos) AS gananciaTotal,fecha FROM (SELECT SUM(total) AS 
     total,SUM(ganancia) AS ganancia,0 AS gastos,fecha FROM 
     `tbventaplatillos` GROUP BY MONTH(fecha) UNION SELECT 0 AS total,0 
     AS ganancia ,SUM(total) AS gastos,fecha FROM tbgastos
      GROUP BY MONTH(fecha) ORDER BY `fecha` DESC) pija 
      GROUP BY MONTH(fecha) ORDER BY fecha DESC";
    

}
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
break;



}


}



?>