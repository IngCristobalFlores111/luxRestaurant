<?php

if(isset($_GET['accion'])){
    include_once("../../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "agregarProducto":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="INSERT INTO `tbfactura_productos`( `nombre`, `descripcion`, `precio`, `idUnidad`,codigo) VALUES (?,?,?,?,?);";
            $sql->execQueryBinders($query,array("ssdis",$request->nombre,$request->descripcion,$request->precio,$request->idUnidad,$request->codigo));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idProducto']=$sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "modificarProducto":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbfactura_productos` SET `nombre`= ? ,`descripcion`= ?,
                     `precio`= ?,`idUnidad`= ?,codigo=? WHERE `idProducto` = ?";
            $sql->execQueryBinders($query,array("ssdiis",$request->nombre,$request->descripcion,$request->precio,$request->idUnidad,$request->codigo,$request->id));
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
        case "eliminarProducto":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="DELETE FROM `tbfactura_productos` WHERE idProducto = ?";
            $sql->execQueryBinders($query,array("i",$request->idProducto));
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