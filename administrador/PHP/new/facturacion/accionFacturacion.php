<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "altaCliente":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $datos = $request->datos;
           $datos = $sql->filter_json($datos);
           $fields = '';$values ='';
           foreach($datos as $key=>$value){
               $fields.=$key.",";
               $values.="'".$value."',";
           }
           $fields = rtrim($fields, ',');
           $values = rtrim($values, ',');
           $query ="INSERT INTO tbclientes(".$fields.") VALUES (".$values.");";
           $sql->executeCommand($query);
           $errores = $sql->getErrorLog();
           $respuesta = array();
           if(count($errores)>0){
               $respuesta['exito'] = false;
               $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
           }else{
               $respuesta['exito'] = true;
               $respuesta['idCliente'] = $sql->getLastId();
           }
           print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "modCliente":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $datos = $request->datos;
            $datos = $sql->filter_json($datos);
            $query ='UPDATE tbclientes SET '; $values ='';
            foreach($datos as $key=>$value){

                if($key!='id'&& $key!='domicilio'&&$key!="cantidad"){
                    $values .=$key."='".$value."',";
                }
            }
            $values = rtrim($values, ',');
            $query .=$values. " WHERE idCliente=".$datos->id;
            $sql->executeCommand($query);
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
        case "eliminarCliente":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbclientes` WHERE idCliente = ?";
            $sql->execQueryBinders($query,array("i",$request->idCliente));
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
        case "modificarConfig":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $config = $request->config;
            $idConfig = $request->idConfig;
            unset($config->selected);
            unset($config->idConfig);
            $values = '';
            foreach($config as $key=>$value){
                $value = $sql->filter_input($value);
                $values.=$key."='".$value."',";
            }
            $values = rtrim($values, ',');
            $query ="UPDATE `tbfactura_config` SET ".$values." WHERE idConfig=".$idConfig;
            $sql->ejecutarNoQuery($query);
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
        case "nuevaConfig":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $config = $request->config;
            unset($config->selected);
            unset($config->idConfig);
            $values = '';$fields ='';
            foreach($config as $key=>$value){
                $value = $sql->filter_input($value);
                $values.="'".$value."',";
                $fields.=$key.',';
            }
            $values = rtrim($values, ',');
            $fields = rtrim($fields, ',');
            $query ="INSERT INTO `tbfactura_config`(".$fields.") VALUES(".$values.")";
            $sql->executeCommand($query);
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idConfig'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "eliminarConfig":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbfactura_config` WHERE idConfig = ?";
            $sql->execQueryBinders($query,array("i",$request->idConfig));
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
        case "actualizarEmisor":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $emisor = $request->emisor;
            $emisor = $sql->filter_json($emisor);
           $values = '';
           foreach($emisor as $key=>$value)
           {
               if($key!='RFC'){
                   $values .= $key."='".$value."',";
               }
           }
            $values = rtrim($values, ',');
            $query ="UPDATE `tbfactura_emisor` SET ".$values." WHERE idEmisor = 1";
            $sql->executeCommand($query);
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
        case "altaLugar":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $lugar = $request->lugar;
            $lugar = $sql->filter_json($lugar);
            $values = ''; $fields ='';
            foreach($lugar as $key=>$value){
                $fields .= $key.",";
                $values .="'".$value."',";
            }
            $values = rtrim($values, ',');
            $fields = rtrim($fields, ',');
            $query ="INSERT INTO `tbfactura_lugares_expedicion` (".$fields.") VALUES (".$values.")";
            $sql->executeCommand($query);
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idLugar'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "actualizarLugar":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $lugar = $request->lugar;
            $lugar = $sql->filter_json($lugar);
            $values = '';
            foreach($lugar as $key=>$value){
                if($key!="selected")
                $values.=$key."='".$value."',";
            }
            $values = rtrim($values, ',');
            $query ="UPDATE `tbfactura_lugares_expedicion` SET ".$values." WHERE idLugarExpedicion = ".$lugar->idLugarExpedicion;

            $sql->executeCommand($query);
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
        case "eliminarLugar":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="DELETE FROM `tbfactura_lugares_expedicion` WHERE idLugarExpedicion = ?";
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
        case "agregarContactoCliente":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbfactura_clientes_contactos`(`idCliente`, `nombre`, `email`) VALUES (?,?,?);";
            $sql->execQueryBinders($query,array("iss",$request->idCliente,$request->nombre,$request->email));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idContacto'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "modificarContactoCliente":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbfactura_clientes_contactos` SET `nombre`=? ,`email`= ?  WHERE idContacto = ?";
            $sql->execQueryBinders($query,array("ssi",$request->nombre,$request->email,$request->idContacto));
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
        case "eliminarContactoCliente":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbfactura_clientes_contactos` WHERE idContacto = ?";
            $sql->execQueryBinders($query,array("i",$request->idContacto));
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