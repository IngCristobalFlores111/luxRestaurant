<?php
if(isset($_GET['accion']))
{
    include_once("../functions.php");
$sql = createMysqliConnection();
switch($_GET['accion']){
case "altaGasto":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="INSERT INTO `tbgastos`(`nombre`, `descripcion`, 
`idCategoriaGasto`, `total`, 
`fecha`) 
VALUES (?,?,?,?,NOW());";
$sql->execQueryBinders($query,array("ssid",$request->nombre,$request->descripcion,
$request->idCategoriaGasto,$request->total));
$errores = $sql->getErrorLog();
$respuesta = array();
if(count($errores)>0){
    $respuesta['exito'] = false;
    $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
}else{
    $respuesta['exito'] = true;
    $respuesta['idGasto'] = $sql->getLastId();
    
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;
case "actualizarGasto":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="UPDATE `tbgastos` SET `total` = ? WHERE `idGasto` = ?";
$sql->execQueryBinders($query,array("di",$request->total,$request->idGasto));
$errores = $sql->getErrorLog();
$respuesta = array();
if(count($errores)>0){
    $respuesta['exito'] = false;
    $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
}else{
    $respuesta['exito'] = true;
    
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "eliminarGasto":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="DELETE FROM `tbgastos` WHERE `idGasto` = ?";
$sql->execQueryBinders($query,array("i",$request->id));
$errores = $sql->getErrorLog();
$respuesta = array();
if(count($errores)>0){
    $respuesta['exito'] = false;
    $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
}else{
    $respuesta['exito'] = true;
    
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
break;




}


}



?>