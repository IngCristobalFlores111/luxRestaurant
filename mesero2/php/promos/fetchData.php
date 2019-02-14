<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
include("../functions.php");
$sql = createMysqliConnection();
    switch($_GET['accion']){
    case "promos":
    $query="SET lc_time_names = 'es_MX';";
    $results = $sql->executeQuery($query);

$query="SELECT tbplatillos.preparado,tbplatillos.idCategoria,tbplatillos.imagepath,DAYNAME(tbpromociones.fecha_inicio) AS nombre_dia,DAY(tbpromociones.fecha_inicio) AS dia_mes_promo,tbpromociones.idFrecuencia, DAYOFWEEK(tbpromociones.fecha_inicio) AS dia_promo, CASE tbpromociones.idFrecuencia WHEN 1 THEN IF(DAYOFWEEK(NOW())=DAYOFWEEK(tbpromociones.fecha_inicio),true,false) WHEN 2 THEN IF(DAY(tbpromociones.fecha_inicio)=DAY(NOW()),true,false) WHEN 3 THEN IF(DAY(tbpromociones.fecha_inicio)=(DAY(NOW())/2),true,false) END AS activo, tbpromociones.idPlatillo,tbpromociones.descripcion,tbpromociones.cantidad,tbpromociones.descuento,tbpromociones.idPromocion AS id,tbplatillos.nombre,tbplatillos.precio FROM `tbpromociones` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpromociones.idPlatillo WHERE tbpromociones.activo = 1 AND tbpromociones.cantidad_promocion>0 AND NOW() BETWEEN tbpromociones.fecha_inicio AND tbpromociones.fecha_fin ORDER BY `tbpromociones`.`descripcion` ASC";

    $results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

    
    break;
    case "obtenerTotal":
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
$query="SELECT tbpedido_platillo.idplatillo,
tbpromociones.cantidad_promocion - tbpedido_platillo.cantidad AS 
limitante,tbpedido_platillo.cantidad AS cantidad_pedido,
IF(tbpromociones.cantidad_promocion - tbpedido_platillo.cantidad < 0,
(tbpromociones.descuento*tbplatillos.precio)*(tbpromociones.cantidad_promocion), 
SUM(tbpedido_platillo.cantidad)*tbpromociones.descuento*tbplatillos.precio) AS descuento , 
DAYOFWEEK(tbpromociones.fecha_inicio) AS dia_promo,
 CASE tbpromociones.idFrecuencia WHEN 1 THEN 
 IF(DAYOFWEEK(NOW())=DAYOFWEEK(tbpromociones.fecha_inicio),true,false) 
 WHEN 2 THEN IF(DAY(tbpromociones.fecha_inicio)=DAY(NOW()),true,false) 
 WHEN 3 THEN IF(DAY(tbpromociones.fecha_inicio)=(DAY(NOW())/2),true,false) END AS activo
  FROM `tbpedido_platillo` INNER JOIN tbpromociones ON tbpromociones.idPlatillo = tbpedido_platillo.idplatillo INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo WHERE tbpedido_platillo.idpedido = ? AND tbpromociones.activo = 1 
AND  NOW() BETWEEN tbpromociones.fecha_inicio AND tbpromociones.fecha_fin
 GROUP BY tbpedido_platillo.idplatillo";

    $promos = $sql->get_bind_results($query,array("i",$request->idPedido));
    $totalDescuento = 0;
    $cantidadPlatillos = 0;
    $multiUpdatePromocion ="";
    if(count($promos)>0){
       foreach($promos as $p){
           if($p['activo']==1){
               $totalDescuento +=(float)$p['descuento'];
            

           }

       }
       
    }
$query="SELECT SUM(tbplatillos.precio*tbpedido_platillo.cantidad) AS total FROM `tbpedido_platillo` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo WHERE tbpedido_platillo.idpedido = ? GROUP BY tbpedido_platillo.idplatillo";
$total = $sql->get_bind_results($query,array("i",$request->idPedido));
$total = (float)$total[0]['total'] - $totalDescuento;
$respuesta = array("total"=>$total,"descuento"=>$totalDescuento);

print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
// ajustar cantidades de promocion



    break;
    case "obtenerDescuentoItem":
    $idPlatillo = $_GET['idPlatillo'];
    $cantidad = (float)$_GET['cantidad'];
    $precio = (float)$_GET['precio'];
    $query="SELECT tbpromociones.cantidad_promocion,tbpromociones.descuento,tbpromociones.cantidad , DAYOFWEEK(tbpromociones.fecha_inicio) AS dia_promo,
    CASE tbpromociones.idFrecuencia WHEN 1 THEN IF(DAYOFWEEK(NOW())=DAYOFWEEK(tbpromociones.fecha_inicio),true,false) WHEN 2 THEN IF(DAY(tbpromociones.fecha_inicio)=DAY(NOW()),true,false) WHEN 3 THEN IF(DAY(tbpromociones.fecha_inicio)=(DAY(NOW())/2),true,false) END AS activo
    
    FROM tbpromociones INNER JOIN tbpedido_platillo ON tbpedido_platillo.idplatillo= tbpromociones.idPlatillo WHERE tbpromociones.idPlatillo = ?
    AND  NOW() BETWEEN tbpromociones.fecha_inicio AND tbpromociones.fecha_fin
    ";
    $promos = $sql->get_bind_results($query,array("i",$idPlatillo));
    $descuento = 0;
    $total = $precio*$cantidad;
    if(count($promos)>0){
    foreach($promos as $p){
        if($p['activo']==1){
            $cant_aplicar = $cantidad;
            $cantidad_promocion = (int)$p['cantidad_promocion'];
            if(($cantidad_promocion-$cantidad)<0){
             $cant_aplicar = $cantidad_promocion;
            }
      $unit_descuento = (float)$p['descuento']*$cant_aplicar*$precio;
      $descuento+=$unit_descuento;
        }
    }
}
    $respuesta = array("total"=>$total,"descuento"=>$descuento);
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
    


    break;

}

}


?>