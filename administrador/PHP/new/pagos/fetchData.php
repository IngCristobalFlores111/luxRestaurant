<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
     case "cuentasPorDia":
       $query="SELECT tbcuenta.idMetodoPago,SUM(tbcuenta.total) AS total,CAST(tbcuenta.fecha AS DATE) AS fecha, tbmetodos_pago_entradas.nombre AS metodoPago FROM `tbcuenta` INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.`idMetodoPago` GROUP BY CAST(tbcuenta.fecha AS DATE) DESC,tbcuenta.`idMetodoPago` LIMIT 50";
       $result = $sql->executeQuery($query);
    
       print_r(json_encode($result,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

     break;
     case "detallesDia":
    $idMetodo = $_GET['idMetodoPago'];
    $fecha = $_GET['fecha'];
$query="SELECT tbusuarios.nombre AS usuario,tbcuenta.total,tbcuenta.`fecha`,tbmetodos_pago_entradas.nombre AS metodoPago FROM `tbcuenta` INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.`idMetodoPago` INNER JOIN tbusuarios ON tbusuarios.iduser = tbcuenta.idUsuario WHERE tbcuenta.`idMetodoPago` = ? AND CAST(tbcuenta.fecha AS DATE) = ? ";
    $result = $sql->get_bind_results($query,array("is",$idMetodo,$fecha));
       print_r(json_encode($result,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

     break;
     case "metodosPago":
$query="SELECT `idMetodoPago` AS id,`nombre` FROM `tbmetodos_pago_entradas`";
$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


     break;
     case "buscarCuentas":
     $idMetodo = $_GET['idMetodoPago'];
     $inicio = $_GET['inicio'];
     $fin = $_GET['fin'];
     $result = array();
     if($idMetodo!=""){     
$query="SELECT tbusuarios.nombre AS usuario,tbcuenta.total,tbcuenta.`fecha`,tbmetodos_pago_entradas.nombre AS metodoPago FROM `tbcuenta` INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.`idMetodoPago` INNER JOIN tbusuarios ON tbusuarios.iduser = tbcuenta.idUsuario WHERE tbcuenta.`idMetodoPago` = ? AND CAST(tbcuenta.fecha AS DATE) BETWEEN ? AND ? ";
$result = $sql->get_bind_results($query,array("iss",$idMetodo,$inicio,$fin));

}else{
    $query="SELECT tbusuarios.nombre AS usuario,tbcuenta.total,tbcuenta.`fecha`,tbmetodos_pago_entradas.nombre AS metodoPago FROM `tbcuenta` INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.`idMetodoPago` INNER JOIN tbusuarios ON tbusuarios.iduser = tbcuenta.idUsuario WHERE CAST(tbcuenta.fecha AS DATE) BETWEEN ? AND ?";
    $result = $sql->get_bind_results($query,array("ss",$inicio,$fin));    
}
print_r(json_encode($result,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


     break;
     case "buscarCuentasPorDia":
     $inicio = $_GET['inicio'];
     $fin = $_GET['fin'];
   
$query="SELECT tbcuenta.idMetodoPago,SUM(tbcuenta.total) AS total,CAST(tbcuenta.fecha AS DATE) AS fecha, tbmetodos_pago_entradas.nombre AS metodoPago FROM `tbcuenta` INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.`idMetodoPago` 
WHERE tbcuenta.fecha BETWEEN ?  AND ?  
 GROUP BY CAST(tbcuenta.fecha AS DATE) DESC,tbcuenta.`idMetodoPago`";
    $result = $sql->get_bind_results($query,array("ss",$inicio,$fin));    
    print_r(json_encode($result,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
    
     break;


    }

}



?>