<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "altaUsr":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbusuarios`( `pass`, `nombre`, `user`, `idTipo`) VALUES (?,?,?,?)";
            $sql->execQueryBinders($query,array("sssi",$request->pass,$request->nombre,$request->user,$request->idTipo));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['iduser'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "editarUsr":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbusuarios` SET `pass`= ?,nombre = ?,`user`= ? ,`idTipo`= ?
                     WHERE `iduser` = ?";
            $sql->execQueryBinders($query,array("sssii",$request->pass,$request->nombre,$request->user,$request->idTipo,$request->id));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['iduser'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


            break;
        case "eliminarUsr":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="DELETE FROM `tbusuarios` WHERE `iduser` = ?";
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