<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "agregarPlatillo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbplatillos`(`nombre`, `precio`, `costo`,
                `descripcion`, `idCategoria`, `imagepath`, `activado`,preparado)
                VALUES (?,?,?,?,?,?,?,?);";
            $sql->execQueryBinders($query,array("sddsisii",$request->nombre,$request->precio,$request->costo,
                $request->descripcion,$request->idCategoria,$request->img,$request->activado,$request->preparado));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idPlatillo'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "modificarPlatillo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbplatillos` SET nombre = ?,precio = ?,costo = ?,
                     descripcion = ?,idCategoria = ?,imagepath = ?,activado = ?,preparado=?
                     WHERE idplatillo = ?";
            $sql->execQueryBinders($query,array("sddsisiii",$request->nombre,$request->precio,$request->costo,$request->descripcion,
                $request->idCategoria,$request->img,$request->activado,$request->preparado,$request->id));
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
        case "eliminarPlatillo";
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="DELETE FROM `tbplatillos` WHERE `idplatillo` = ?";
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