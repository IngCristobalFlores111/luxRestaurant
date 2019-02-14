<?php
include_once("functions.php");
$sql = createMysqliConnection();


if(isset($_GET['accion'])){
    switch($_GET['accion']){
        case "agregarInsumo":
            $query ="INSERT INTO `tbinsumos`
                     ( `nombre`, `idUnidad`, `descripcion`, `costo`) VALUES (?,?,?,?);";
            $sql->execQueryBinders($query,array("sisd",$_POST['nombre'],$_POST['unidad'],$_POST['descripcion'],$_POST['costo']));
            $idInsumo =  $sql->getLastId();
            $proveedores = $_POST['proveedores'];
            $query ="";
            foreach($proveedores as $prov){
                $query .="INSERT INTO `tbinsumo_proveedores`(`idProveedor`, `idInsumo`) VALUES (".$prov['id'].",".$idInsumo.");";

            }
            $sql->ejecutarNoQuery($query);

            $errores =  $sql->getErrorLog();
           $respuesta = array();
           if(count($errores)>0){
               $respuesta['exito']= false;
               $respuesta['errores'] = $errores;
           }else{
               $respuesta['exito']= true;
               $respuesta['errores'] = null;
               $respuesta['idInsumo'] = $idInsumo;
           }
           print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

            break;
        case "modificarInsumo":
            $idInsumo = $_POST['id'];
            $query ="UPDATE `tbinsumos` SET `nombre`= ? ,
                   `idUnidad`= ?,`descripcion`=?,`costo`= ? WHERE `idInsumo` = ?";
            $sql->execQueryBinders($query,array("sisdi",$_POST['nombre'],$_POST['unidad'],$_POST['descripcion'],$_POST['costo'],$idInsumo));
            $errores =  $sql->getErrorLog();
            $proveedores  = $_POST['proveedores'];
            $sql->execQueryBinders("DELETE FROM `tbinsumo_proveedores` WHERE `idInsumo` = ?",array("i",$idInsumo));
            $query = "";
            foreach($proveedores as $prov){
                $query .="INSERT INTO `tbinsumo_proveedores`(`idProveedor`, `idInsumo`) VALUES (".$prov['id'].",".$idInsumo.");";
            }
            $sql->ejecutarNoQuery($query);

            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
                $respuesta['errores'] = null;
                $respuesta['idInsumo'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

            break;
        case "eliminarInsumo":
            $query ="DELETE FROM `tbinsumos` WHERE `idInsumo` = ?";
            $sql->execQueryBinders($query,array("i",$_POST['id']));
            $errores =  $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito']= true;
                $respuesta['errores'] = null;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));



            break;


    }
}



?>