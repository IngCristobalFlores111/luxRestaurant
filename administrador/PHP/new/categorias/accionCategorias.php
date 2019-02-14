<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "agregarCategoria":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbplatillos_categorias`(`nombre`) VALUES (?)";
            $sql->execQueryBinders($query,array("s",$request->nombre));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idCategoria'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "modficarNombreCategoria":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbplatillos_categorias` SET nombre = ? WHERE idCategoria = ?";
            $sql->execQueryBinders($query,array("si",$request->nombre,$request->idCategoria));
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
        case "eliminarCategoria":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="DELETE FROM `tbplatillos_categorias` WHERE `idCategoria` = ?";
            $sql->execQueryBinders($query,array("i",$request->idCategoria));
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
        case "agregarUnidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="INSERT INTO `tbunidades`(`nombre`) VALUES (?)";
            $sql->execQueryBinders($query,array("s",$request->nombre));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idUnidad'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "modificarUnidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbunidades` SET `nombre`= ? WHERE `idUnidad` = ?";
            $sql->execQueryBinders($query,array("si",$request->nombre,$request->idUnidad));
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
        case "eliminarUnidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbunidades` WHERE `idUnidad` = ?";
            $sql->execQueryBinders($query,array("i",$request->idUnidad));
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
        case "agregarUnidadFact":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbfactura_unidades`(`nombre`) VALUES (?);";
            $sql->execQueryBinders($query,array("s",$request->unidad));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idUnidad'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "modificarUnidadFact":
             $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbfactura_unidades` SET nombre = ? WHERE idUnidad = ?";
            $sql->execQueryBinders($query,array("si",$request->nombre,$request->idUnidad));
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
        case "eliminarUnidadFact":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbfactura_unidades` WHERE idUnidad = ?";
            $sql->execQueryBinders($query,array("i",$request->idUnidad));
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
            case "altaCategoriaGasto":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="INSERT INTO `tbgastos_categorias`(`nombre`) VALUES (?);";
            $sql->execQueryBinders($query,array("s",$request->nombre));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['id'] =  $sql->getLastId();
                
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            
            break;
            case "actualizarCategoriaGasto":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbgastos_categorias` 
            SET nombre=? WHERE idGastoCategoria=?";
            $sql->execQueryBinders($query,array("si",$request->nombre,$request->id));
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
            case "eliminarCategoriaGasto":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
           $query="DELETE FROM `tbgastos_categorias` WHERE `idGastoCategoria` = ?";
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