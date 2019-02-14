<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
       case "buscarInsumo":
       $q = $_GET['q'];
       $q =   $sql->filter_input($q);
       
$query="SELECT tbinsumos.idInsumo AS id,tbunidades.nombre AS unidad,tbinsumos.nombre,tbinsumos.descripcion,tbinsumos.costo FROM `tbinsumos` INNER JOIN tbunidades ON tbunidades.idUnidad = tbinsumos.`idUnidad` WHERE MATCH(tbinsumos.nombre,tbinsumos.descripcion) AGAINST('*".$q."*' IN BOOLEAN MODE)";
$results = $sql->executeQuery($query);
       print_r(json_encode($results,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
       
       break;
       case"obtenerInsumosCompuestos":
       $query="SELECT SUM(tbinsumos.costo*tbinsumos_to_compuestos.cantidad) AS costo,tbinsumos_compuestos.nombre,tbinsumos_compuestos.`idInsumoCompuesto` AS id, tbinsumos_compuestos.`descripcion` FROM `tbinsumos_compuestos` LEFT JOIN tbinsumos_to_compuestos ON tbinsumos_to_compuestos.idInsumoCompuesto =tbinsumos_compuestos.`idInsumoCompuesto` LEFT JOIN tbinsumos ON tbinsumos.idInsumo = tbinsumos_to_compuestos.idInsumo GROUP BY tbinsumos_compuestos.`idInsumoCompuesto`";
       $results = $sql->executeQuery($query);
       print_r(json_encode($results,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
       
       break;
       case "obtenerInsumosByIdCompuesto":
       $query="SELECT tbinsumos.costo,tbinsumos_to_compuestos.`idInsumo` AS id,tbinsumos.nombre,tbunidades.nombre AS unidad,tbinsumos_to_compuestos.cantidad FROM `tbinsumos_to_compuestos` INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbinsumos_to_compuestos.`idInsumo` INNER JOIN tbunidades ON tbunidades.idUnidad = tbinsumos.idUnidad WHERE `idInsumoCompuesto` = ?";
       $results =$sql->get_bind_results($query,array("i",$_GET['id']));
       print_r(json_encode($results,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
       

       break;
    }

}
?>