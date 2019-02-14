<?php
session_start();
if(isset($_GET['accion'])){
    include("functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "despachar":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbpedido_platillo` SET cantidad_terminado = cantidad_terminado + 1 
            WHERE idPedidoPlatillo = ?
            AND cantidad>=cantidad_terminado
            ";
            $sql->execQueryBinders($query,array("i",$request->id));
            $query ="UPDATE `trigger_update_ui` SET flag = 1";
            $sql->ejecutarNoQuery($query);

            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores']= $errores;
            }else{
                $respuesta['exito'] = true;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "updateUsr":
            if(isset($_SESSION['usr'])){
                $postdata = file_get_contents("php://input");
                $request = json_decode($postdata);
                $idUsr = $_SESSION['usr']['idusr'];
                $query ="SELECT * FROM `tbusuarios` WHERE iduser = ? AND pass = ?";
                $usr =   $sql->get_bind_results($query,array("is",$idUsr,$request->pass));
                $respuesta = array();
                if(count($usr)==0){
                    $respuesta['exito'] = false;
                }else{
                    $query ="UPDATE `tbusuarios` SET
                             nombre = ?,user = ? WHERE iduser= ?";
                    $sql->execQueryBinders($query,array("ssi",$request->nombre,$request->user,$idUsr));
                    $errores = $sql->getErrorLog();
                    if(count($errores)>0){
                        $respuesta['exito'] = false;
                        $respuesta['errores'] = $errores;

                    }else{
                        $respuesta['exito'] = true;
                        $_SESSION['usr']['nombre'] = $request->nombre;
                        $_SESSION['usr']['usr'] =$request->user;
                    }

                }
                print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            }



            break;
    }
}

?>  