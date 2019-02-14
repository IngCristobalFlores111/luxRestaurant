<?php

if(isset($_GET['accion'])){
    include ("../../administrador/PHP/new/functions.php");
    $sql = createMysqliConnection();

switch($_GET['accion']){
case "clientes":
  $result = $sql->executeQuery("SELECT * FROM `tbclientes_llevar`");
  print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

break;
case "platillos":
$query="SELECT * FROM `tbplatillos` WHERE `activado` = 1";
$result = $sql->executeQuery($query);
print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
break;
case "pedidos":
$query ="SELECT tbclientes_llevar.nombre AS nombrCliente,tbclientes_llevar.domicilio,tbpedidos_llevar.`idPedido`,`idPedidosLlevar` AS id FROM `tbpedidos_llevar` INNER JOIN tbclientes_llevar ON tbclientes_llevar.idCliente = tbpedidos_llevar.`idCliente`";
$result = $sql->executeQuery($query);
$llevar = array();
foreach($result as $pedido){
$query="SELECT * FROM `tbpedido_platillo` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo WHERE `idpedido` = ".$pedido['idPedido'];
$resul2 = $sql->executeQuery($query);
array_push($llevar,array("pedido"=>$pedido,"platillos"=>$resul2));
}
print_r(json_encode($llevar,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
break;
case "metodosPago":
$query="SELECT idMetodoPago AS id,nombre FROM `tbmetodos_pago_entradas`";
$metodos = $sql->executeQuery($query);
print_r(json_encode($metodos,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

break;


}

}else{
echo "No action recived";

}

?>