<?php
if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();

switch($_GET['accion']){
case "cajas":
$query="SELECT tbcaja.idUsr,tbusuarios.nombre AS usuario,tbcaja.`idCaja` AS id,tbcaja.`nombre`,tbcaja.`fecha_inicio`,tbcaja.`fecha_fin`,tbcaja.`total_inicio`,tbcaja.`total_fin` FROM `tbcaja` LEFT JOIN tbusuarios ON tbusuarios.iduser= tbcaja.`idUsr`";
$results = $sql->executeQuery($query);
$query="SELECT tbcaja_historial.idUsr,tbusuarios.nombre AS usuario,tbcaja_historial.`idCaja` AS id,tbcaja.`nombre`,tbcaja_historial.`fecha_inicio`,tbcaja_historial.`fecha_fin`,tbcaja_historial.`total_inicio`,tbcaja_historial.`total_fin` FROM tbcaja_historial LEFT JOIN tbusuarios ON tbusuarios.iduser= tbcaja_historial.`idUsr` INNER JOIN tbcaja ON tbcaja.`idCaja` = tbcaja_historial.`idCaja`";
$results2 = $sql->executeQuery($query);
$respuesta = array("cajas"=>$results,"historial"=>$results2);
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));



break;

}


}

?>