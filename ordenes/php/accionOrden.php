<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include ("../../mesero2/php/functions.php");
    $sql = createMysqliConnection();

switch($_GET['accion']){
case "ordenar":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);
session_start();
$idUsr=$_SESSION['usr']['idusr'];
$query="INSERT INTO `tbpedidos`(`idmesa`, `comentario`, `despachado`, 
`fecha_llegada`, `idMesero`) VALUES (1,'para llevar',0,NOW(),'$idUsr')";
$sql->ejecutarNoQuery($query);
$idPedido = $sql->getLastId();
$query="";
$platillos = $request['platillos'];
foreach($platillos as $p){
    $cantidad_terminado = ($p['preparado']=='0')?$p['cantidad']:'0';

$query.="INSERT INTO `tbpedido_platillo`(`comentarios`, `cantidad`, `cantidad_terminado`,
 `cantidad_servido`, `idplatillo`, `idpedido`, `fecha_llegada`) VALUES 
('".$p['comentarios']."',".$p['cantidad'].",".$cantidad_terminado.",0,'".$p['idplatillo']."',$idPedido,NOW());";

}
$idCliente = $request['idCliente'];
$query.="INSERT INTO `tbpedidos_llevar`(`idCliente`, `idPedido`) VALUES ($idCliente,$idPedido);";

$sql->ejecutarNoQuery($query);
$errores= $sql->getErrorLog();
$respuesta = array();
if(count($errores)>0){
$respuesta['exito'] = false;
$respuesta['error'] =$errores;
}else{
$respuesta['exito']=true;
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case"agregarCliente":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata,true);
$query="INSERT INTO `tbclientes_llevar`(`nombre`, `domicilio`) VALUES (?,?);";
$sql->execQueryBinders($query,array("ss",$request['nombre'],$request['domicilio']));
$idCliente = $sql->getLastId();

$errores= $sql->getErrorLog();
$respuesta = array();
if(count($errores)>0){
$respuesta['exito'] = false;
$respuesta['error'] =$errores;
}else{
$respuesta['exito']=true;
$request['idCliente'] = $idCliente;
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "pagar":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
session_start();
$idUsr=$_SESSION['usr']['idusr'];
$query="INSERT INTO `tbcuenta`(idMetodoPago,descuento,
`idUsuario`, `fecha`, `total`,llevar) 
VALUE (?,?,?,NOW(),?,1);";
$sql->execQueryBinders($query,array("idid",$request->metodoPago,$request->descuento,$idUsr,$request->total));
$idCuenta = $sql->getLastId();

/*
$query="INSERT INTO `tbcuenta_pedido`(`idcuenta`, `idPedido`) VALUES (?,?);";
$sql->execQueryBinders($query,array("ii",$idCuenta,$request->idPedido));
$sql->execQueryBinders("UPDATE `tbpedidos` SET `despachado` = 1 WHERE `idpedido` = ?",array("i",$request->idPedido));
*/
$ticketQuery ="";
$query="SELECT idplatillo AS id,cantidad FROM `tbpedido_platillo` WHERE idpedido=?";
$platillos = $sql->get_bind_results($query,array("i",$request->idPedido));
foreach($platillos as $platillo){
    $ticketQuery.="INSERT INTO `tbtickets`(`idCuenta`, `idPlatillo`, `cantidad`) 
    VALUES(".$idCuenta.",".$platillo['id'].",".$platillo['cantidad'].");";
}
if($ticketQuery!=""){
$sql->ejecutarNoQuery($ticketQuery);
}
$respuesta = array();
$errores= $sql->getErrorLog();

if(count($errores)>0){
$respuesta['exito'] = false;
$respuesta['error'] =$errores;
}else{
$respuesta['exito']=true;
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;
case "terminar":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="DELETE FROM `tbpedido_platillo` WHERE `idpedido` = ?";
$sql->execQueryBinders($query,array("i",$request->idPedido));
$query="DELETE FROM `tbpedidos_llevar` WHERE `idPedido` = ?";
$sql->execQueryBinders($query,array("i",$request->idPedido));
descontarInventario($request->idPedido);
$respuesta = array();
$errores= $sql->getErrorLog();

if(count($errores)>0){
$respuesta['exito'] = false;
$respuesta['error'] =$errores;
}else{
$respuesta['exito']=true;
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;



}

}else{
echo "No action recived";

}

?>