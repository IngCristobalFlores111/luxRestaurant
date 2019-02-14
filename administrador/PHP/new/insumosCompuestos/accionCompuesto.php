<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
       case "agregarCompuesto":
       $postdata = file_get_contents("php://input");
       $request = json_decode($postdata);
       $query="INSERT INTO `tbinsumos_compuestos`(`nombre`, `descripcion`) VALUES (?,?)"; 
      $sql->execQueryBinders($query,array("ss",$request->nombre,$request->descripcion));
        $idCompuesto =  $sql->getLastId();

       $insumos = $request->insumos;
        $query ="";
        foreach($insumos as $i){
        $query.="INSERT INTO `tbinsumos_to_compuestos`(`idInsumo`,
         `idInsumoCompuesto`, `cantidad`) VALUES('".$i->id."','".$idCompuesto."','".$i->cantidad."');";

        }
        $sql->ejecutarNoQuery($query);
        $errores = $sql->getErrorLog();
        $respuesta = array();
        if(count($errores)>0){
            $respuesta['exito'] = false;
            $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
        }else{
            $respuesta['exito'] = true;
            $respuesta['idCompuesto'] = $idCompuesto;
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

       break;
       case "updateInsumoCompuesto":
       $postdata = file_get_contents("php://input");
       $request = json_decode($postdata);
       $insumos = $request->insumos;
       $query ="";
       $idCompuesto = $request->idCompuesto;
       foreach($insumos as $i){
    $query.="INSERT INTO `tbinsumos_to_compuestos`
        (`idInsumo`, `idInsumoCompuesto`, `cantidad`) VALUES( 
        ".$i->id.",".$idCompuesto.",'".$i->cantidad."');";

       }
       $sql->execQueryBinders("DELETE FROM `tbinsumos_to_compuestos` WHERE `idInsumoCompuesto` = ?",array("i",$idCompuesto));
       $sql->ejecutarNoQuery($query);
       $query="UPDATE `tbinsumos_compuestos` SET `nombre` = ?,`descripcion` = ? 
          WHERE `idInsumoCompuesto` = ?";
       $sql->execQueryBinders($query,array("ssi",$request->nombre,$request->descripcion,$idCompuesto));

       $errores = $sql->getErrorLog();
       $respuesta = array();
       if(count($errores)>0){
           $respuesta['exito'] = false;
           $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
       }else{
           $respuesta['exito'] = true;
           $respuesta['idCompuesto'] = $idCompuesto;
       }
       print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

       break;
       case "eliminarCompuesto":
       $postdata = file_get_contents("php://input");
       $request = json_decode($postdata);
       $query="DELETE FROM `tbinsumos_to_compuestos` WHERE `idInsumoCompuesto` = ?";
       $sql->execQueryBinders($query,array("i",$request->id));
       $sql->execQueryBinders("DELETE FROM `tbinsumos_compuestos` WHERE `idInsumoCompuesto` = ?",array("i",$request->id));       
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