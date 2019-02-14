<?php
if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();

switch($_GET['accion']){
case "altaCaja":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="INSERT INTO `tbcaja`(`nombre`, `fecha_inicio`, `fecha_fin`, `total_inicio`, `total_fin`, `idUsr`) VALUES (?,NULL,NULL,NULL,NULL,NULL);";
$sql->execQueryBinders($query,array("s",$request->nombre));
$errores =  $sql->getErrorLog();

$respuesta = array();
if(count($errores)>0){
    $respuesta['exito']= false;
    $respuesta['errores'] = $errores;
}else{
    $respuesta['exito']= true;
    $respuesta['errores'] = null;
    $respuesta['idCaja'] = $sql->getLastId();
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "actualizarCaja":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="UPDATE `tbcaja` SET `nombre`=?,`total_inicio`=?,`total_fin`=? WHERE `idCaja` = ?";
$sql->execQueryBinders($query,array("sddi",
$request->nombre,
$request->total_inicio,
$request->total_fin,
$request->id));
$errores =  $sql->getErrorLog();

$respuesta = array();
if(count($errores)>0){
    $respuesta['exito']= false;
    $respuesta['errores'] = $errores;
}else{
    $respuesta['exito']= true;
    $respuesta['errores'] = null;

}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

break;
case "eliminarCaja":
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$query="DELETE FROM `tbcaja` WHERE `idCaja` = ?";
$sql->execQueryBinders($query,array("i",$request->id));
$errores =  $sql->getErrorLog();

$respuesta = array();
if(count($errores)>0){
    $respuesta['exito']= false;
    $respuesta['errores'] = $errores;
}else{
    $respuesta['exito']= true;
    $respuesta['errores'] = null;

}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


break;




}
}

?>