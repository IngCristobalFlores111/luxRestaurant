<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();

    switch($_GET['accion']){
        case "agregarInsumo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbinsumos_platillo`(`idInsumo`, `idplatillo`, `cantidad`) VALUES (?,?,?);";
            $sql->execQueryBinders($query,array("iid",$request->idInsumo,$request->idPlatillo,$request->cantidad));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores']=json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito']= true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "actualizarCantidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbinsumos_platillo` SET `cantidad` = ? WHERE idInsumo = ?";
            $sql->execQueryBinders($query,array("di",$request->cantidad,$request->idInsumo));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores']=json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito']= true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "eliminarInsumo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbinsumos_platillo` WHERE `idInsumo` = ? AND `idplatillo` = ?";
            $sql->execQueryBinders($query,array("ii",$request->idInsumo,$request->idPlatillo));
            $query="DELETE FROM `tb_platillo_insumos_prov_actual` WHERE idInsumo = ? AND idPlatillo = ?";
            $sql->execQueryBinders($query,array("ii",$request->idInsumo,$request->idPlatillo));
            
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores']=json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito']= true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "actualizarCostoPlatillo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbplatillos` SET costo = ?  WHERE idplatillo = ?";
            $sql->execQueryBinders($query,array("di",$request->costo,$request->idPlatillo));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores']=json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito']= true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


            break;
        case "establecerValoresActuales":


            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $idPlatillo = $request->idPlatillo;
            $query="DELETE FROM `tb_platillo_insumos_prov_actual` WHERE `idPlatillo`= ?";
            $sql->execQueryBinders($query,array("i",$idPlatillo));
            $query ="";
            foreach($request->data as $insumo){
                $query.="INSERT INTO `tb_platillo_insumos_prov_actual`
                    (`idProveedor`, `idInsumo`, `idPlatillo`, `idAlmacen`)
                    VALUES ('".$insumo->proveedor."','".$insumo->id."','$idPlatillo','".$insumo->almacen."') ON DUPLICATE KEY UPDATE
                    idProveedor = '".$insumo->proveedor."',idAlmacen = '".$insumo->almacen."';";
            }
            $sql->ejecutarNoQuery($query);


            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito']= false;
                $respuesta['errores']=json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito']= true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
            case "setCompuesto":
            
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="SELECT  tbinsumos_to_compuestos.cantidad AS cantidad_bruta,tbinsumos.idInsumo AS id,tbinsumos.nombre,tbinsumos.descripcion,tbinsumos.costo AS costo_unitario,tbunidades.nombre AS unidad FROM tbinsumos_to_compuestos INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbinsumos_to_compuestos.`idInsumo` INNER JOIN tbunidades ON tbunidades.idUnidad = tbinsumos.idUnidad
            WHERE tbinsumos_to_compuestos.`idInsumoCompuesto` = ?";
            $results = $sql->get_bind_results($query,array("i",$request->id));
            $query="";
            $cantidad = (float)$request->cantidad;
            $idPlatillo= $request->idPlatillo;
            $i = 0;
            foreach($results as $insumo){
            $cant = (float)$cantidad*$insumo['cantidad_bruta'];
            $query.="INSERT INTO `tbinsumos_platillo`(`idInsumo`, `idplatillo`, `cantidad`) VALUES( 
              ".$insumo['id'].",".$idPlatillo.",'".$cant."');";  
              $results[$i]['cantidad'] = $cant;
              $i++;
              $results[$i]['costo'] = (float)$cant*$insumo['costo_unitario'];
        }
        $sql->ejecutarNoQuery($query);
        $errores = $sql->getErrorLog();
        $respuesta = array("insumos"=>$results,"dbErrors"=>$errores);
            
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            

            break;

    }

}


?>