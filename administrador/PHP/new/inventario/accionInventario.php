<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "nuevoAlmacen":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbalmacen`(`nombre`) VALUES (?);";
            $sql->execQueryBinders($query,array("s",$request->nombreAlmacen));
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idAlmacen'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "modificarAlmacen":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbalmacen` SET`nombre`= ? WHERE idAlmacen = ?";
            $sql->execQueryBinders($query,array("si",$request->nombreAlmacen,$request->idAlmacen));
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
        case "eliminarAlmacen":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbalmacen` WHERE idAlmacen = ?;";
            $sql->execQueryBinders($query,array("i",$request->idAlmacen));
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
        case "altaProveedor":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="INSERT INTO `tbproveedores`( `nombre`, `RFC`, `domicilio`, `email`) VALUES (?,?,?,?);";
            $sql->execQueryBinders($query,array("ssss",$request->nombre,$request->RFC,$request->domicilio,$request->email));

            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['idProveedor'] = $sql->getLastId();
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "modificarProveedor":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbproveedores` SET `nombre`= ?,`RFC`=?,`domicilio`=?,
                    `email`=? WHERE `idProveedor` = ?";
            $sql->execQueryBinders($query,array("ssssi",$request->nombre,$request->RFC,$request->domicilio,$request->email,$request->id));
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
        case "altaInsumoAlmacen":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            session_start();
            $idUsr =$_SESSION['usr']["idusr"];
            $ids = array();
            $query="INSERT INTO `tbalmacen_insumos`(razon,idUsrMod,`idAlmacen`, `idInsumo`,idProveedor,
                       `cantidad`, `fecha`) VALUES (?,?,?,?,?,?,NOW())
                        ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
                $sql->execQueryBinders($query,array("siiiidd","Alta Almacen",$idUsr,
                    $request->idAlmacen,$request->idInsumo,
                 $request->idProveedor,$request->cantidad,$request->cantidad));
                $ids['historial'] =$sql->getLastId();

            // verifciar en almacen insumos actual...
            $query ="SELECT * FROM `tbalmacen_insumos_actual`
                     WHERE idInsumo = ? AND idAlmacen = ? AND idProveedor = ?";
            $registro = $sql->get_bind_results($query,array("iii",$request->idInsumo,$request->idAlmacen,$request->idProveedor));
            if(count($registro)>0){
                $registro = $registro[0];
                $query ="UPDATE `tbalmacen_insumos_actual` SET cantidad = cantidad + ?
                         WHERE idAlmacenInsumosActual = ?";
                $sql->execQueryBinders($query,array("di",$request->cantidad,$registro['idAlmacenInsumosActual']));
            }else{
                $query ="INSERT INTO `tbalmacen_insumos_actual`
                        ( idUsrMod,`idAlmacen`, `idInsumo`, `idProveedor`, `cantidad`) VALUES (?,?,?,?,?);";
                $sql->execQueryBinders($query,array("iiiid",$idUsr,$request->idAlmacen,
                   $request->idInsumo,$request->idProveedor,$request->cantidad));
                $ids['actual'] = $sql->getLastId();
            }
            $errores = $sql->getErrorLog();
            $respuesta = array();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
            }else{
                $respuesta['exito'] = true;
                $respuesta['ids'] = $ids;
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "actulizarCantidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            session_start();
            $idUsr =$_SESSION['usr']["idusr"];
            $query="UPDATE `tbalmacen_insumos_actual` SET cantidad = ? , idUsrMod = ?  WHERE idAlmacenInsumosActual = ?";
            $sql->execQueryBinders($query,array("dii",$request->cantidad,$idUsr,$request->id));
             $razon = $request->razon;
            $query="INSERT INTO tbalmacen_insumos
        (idProveedor,razon,idUsrMod,idAlmacen,idInsumo,cantidad,fecha) VALUE(?,?,?,?,?,?,NOW()) ON DUPLICATE KEY UPDATE cantidad = ?;";
            $sql->execQueryBinders($query,array("isiiidd",$request->idProveedor,$razon,$idUsr,$request->idAlmacen,$request->idInsumo,$request->cantidad,$request->cantidad));
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
        case "registrarInventario":
        session_start();
        $idUsr =$_SESSION['usr']["idusr"];
            $query ="SELECT * FROM `tbalmacen_insumos_actual`";
            $result = $sql->executeQuery($query);
            $query ="";
            foreach($result as $insumo){
                $idAlmacen = $insumo['idAlmacen'];
                $idInsumo = $insumo['idInsumo'];
                $cantidad = $insumo['cantidad'];
                $idProveedor = $insumo['idProveedor'];
                $query.="INSERT INTO tbalmacen_insumos
        (razon,idUsrMod,idAlmacen,idInsumo,idProveedor,cantidad,fecha)
        VALUE('Actualizacion de inventario',$idUsr,$idAlmacen,$idInsumo,$idProveedor,$cantidad,NOW())
          ON DUPLICATE KEY UPDATE cantidad = $cantidad;";

            }
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
            case "agregarInventarioUnidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $request->actual = ($request->actual)?'1':'0';
            if($request->actual=='1'){
                $query ="UPDATE `tbinventario_unidad` SET `actual` = 0 WHERE `idPlatillo` = ?";
                $sql->execQueryBinders($query,array("i",$request->idPlatillo));
            }
             $query ="INSERT INTO `tbinventario_unidad`(`cantidad`, `idAlmacen`, 
             `idProveedor`, 
             `idPlatillo`,actual) VALUES 
             (?,?,?,?,?) 
             ON DUPLICATE KEY UPDATE cantidad = cantidad + ?";
              $sql->execQueryBinders($query,array("diiiid",
              $request->cantidad,
              $request->idAlmacen,
              $request->idProveedor,
              $request->idPlatillo,
              $request->actual,
              $request->cantidad));
              $errores = $sql->getErrorLog();
              $respuesta = array();
              if(count($errores)>0){
                  $respuesta['exito'] = false;
                  $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
              }else{
                  $respuesta['exito'] = true;
                  $respuesta['idProducto'] = $sql->getLastId();
              }
              print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
      


            break;
            case "actualizarInventarioUnidadCantidad":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbinventario_unidad` SET `cantidad` = ? WHERE `idInventarioUnidad` = ?";
            $sql->execQueryBinders($query,array("id",$request->cantidad,$request->id));
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
            case "setInventarioUnidadActual":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $actual = $request->actual;
            $query = "UPDATE `tbinventario_unidad` SET `actual`= 0 WHERE `idPlatillo` = ?";
            $sql->execQueryBinders($query,array("i",$request->idPlatillo));
            $query ="UPDATE `tbinventario_unidad` SET actual = ?  WHERE `idInventarioUnidad` = ?";
             $sql->execQueryBinders($query,array("ii",$actual,$request->id));
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
            case "eliminarProvedor":
              $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="DELETE FROM `tbproveedores` WHERE `idProveedor` = ?";
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