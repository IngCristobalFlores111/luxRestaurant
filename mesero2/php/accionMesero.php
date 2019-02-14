<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include("functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "servirPlatillo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="UPDATE `tbpedido_platillo` SET cantidad_servido =cantidad_servido + ? WHERE idPedidoPlatillo = ?";
            $sql->execQueryBinders($query,array("ii",$request->cantidad,$request->id));
            $respuesta =array();
            $errores = $sql->getErrorLog();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito'] = true;
                $respuesta["msg"] ="Platillo Servido Con Exito";
            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "agregarMesa":
              $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query="INSERT INTO `tbmesas`(`nombre`, `ocupado`, `comensales`) VALUES(?,0,0);";
            $sql->execQueryBinders($query,array("s",$request->nombre));
            $respuesta =array();
            $errores = $sql->getErrorLog();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito'] = true;
                $respuesta['idMesa'] = $sql->getLastId();

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "eliminarMesa":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="DELETE FROM `tbmesas` WHERE idmesa = ?";
            $sql->execQueryBinders($query,array("i",$request->id));
            $respuesta =array();
            $errores = $sql->getErrorLog();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito'] = true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "abrirMesa":
            session_start();
            $idUsr = $_SESSION['usr']['idusr'];
            $idMesa = $_GET['idMesa'];
            $comensales = $_GET['comensales'];
            $comentario = $_GET['comentario'];
            $query ="UPDATE `tbmesas` SET ocupado = 1, comensales = ? WHERE idmesa = ?";
            $sql->execQueryBinders($query,array("ii",$comensales,$idMesa));
            $query = "INSERT INTO `tbpedidos`(`idmesa`, `comentario`, `despachado`, `idMesero`,fecha_llegada)
                      VALUES (?,?,0,?,NOW())";
            $sql->execQueryBinders($query,array("isi",$idMesa,$comentario,$idUsr));
            echo $sql->getLastId();

            break;
        case "agregarPlatillo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            // ojo cambiar esta linea de codigo de acuerdo al id de las categorias que apareceran por completadas al instante...
            // verificar si se peude vender platillo
            $query ="SELECT tbinsumos.nombre,tbalmacen_insumos_actual.`cantidad`,tbinsumos_platillo.cantidad AS cantidad_platillo FROM tbalmacen_insumos_actual INNER JOIN tbinsumos_platillo ON tbinsumos_platillo.idInsumo=tbalmacen_insumos_actual.idInsumo INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbalmacen_insumos_actual.idInsumo WHERE tbinsumos_platillo.idplatillo =?";
            $res = $sql->get_bind_results($query,array("i",$request->idplatillo));
            $depleted = array();
            if(!$request->force){
                foreach($res as $a){
                    if($a['cantidad']<=$a['cantidad_platillo']){
                        array_push($depleted,array('cantidad'=>$a['cantidad'],"insumo"=>$a['nombre'],"cantidad_platillo"=>$a['cantidad_platillo']));

                    }

                }
            }
            if(count($depleted)>0){
                $respuesta =array();
                $respuesta['exito'] = false;
                $respuesta['depleted'] = $depleted;
                print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            }else{

                $cantidad_terminado = ($request->preparado==0)?$request->cantidad:'0';
                $query ="INSERT INTO `tbpedido_platillo`( `comentarios`, `cantidad`,
              `cantidad_terminado`, `cantidad_servido`, `idplatillo`, `idpedido`,fecha_llegada)
                   VALUES (?,?,$cantidad_terminado,0,?,?,NOW())";
                $comentario = (isset($request->comentario))?$request->comentario:"";
                $sql->execQueryBinders($query,array("siii",$comentario,$request->cantidad,$request->idplatillo,$request->idpedido));
                $idPedidoPlatillo = $sql->getLastId();
                
                
                $query ="UPDATE `trigger_update_ui` SET flag=1";
                $sql->ejecutarNoQuery($query);

                $respuesta =array();
                $errores = $sql->getErrorLog();
                if(count($errores)>0){
                    $respuesta['exito'] = false;
                    $respuesta['errores'] = $errores;
                }else{
                    $respuesta['exito'] = true;
                    $respuesta['idPedidoPlatillo'] = $idPedidoPlatillo;

                }
                print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            }


            break;
        case "restarPlatillo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="UPDATE `tbpedido_platillo` SET cantidad = cantidad-1 WHERE idPedidoPlatillo = ?";
            $sql->execQueryBinders($query,array("i",$request->id));
            $query = "SELECT cantidad,cantidad_servido FROM tbpedido_platillo WHERE idPedidoPlatillo =?";
           $cantidad =  $sql->get_bind_results($query,array("i",$request->id));
           $eliminado = false;
           if((int)$cantidad[0]['cantidad']==0&& (int)$cantidad[0]['cantidad_servido']==0){
               $query="DELETE FROM tbpedido_platillo WHERE idPedidoPlatillo = ?";
               $sql->execQueryBinders($query,array("i",$request->id));
               $eliminado = true;

           }

            $respuesta =array();
            $errores = $sql->getErrorLog();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito'] = true;
                $respuesta['eliminado'] = $eliminado;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "pagarCuenta":
            session_start();
            $idUsr = $_SESSION['usr']['idusr'];
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $promosMultiUpdate = "";
$query="SELECT 
CASE tbpromociones.idFrecuencia WHEN 1 THEN 
 IF(DAYOFWEEK(NOW())=DAYOFWEEK(tbpromociones.fecha_inicio),true,false) 
 WHEN 2 THEN IF(DAY(tbpromociones.fecha_inicio)=DAY(NOW()),true,false) 
 WHEN 3 THEN IF(DAY(tbpromociones.fecha_inicio)=(DAY(NOW())/2),true,false) END AS activo_promo
,tbpedido_platillo.idplatillo,tbpedido_platillo.cantidad AS cantidad_pedido,tbpromociones.cantidad_promocion FROM `tbpedido_platillo` INNER JOIN tbpromociones ON tbpromociones.idPlatillo = tbpedido_platillo.idplatillo 
        WHERE tbpedido_platillo.idpedido = ? AND tbpromociones.activo=1 AND tbpromociones.cantidad_promocion>0";
            $platillos = $sql->get_bind_results($query,array("i",$request->idPedido));
           if(count($platillos)>0){
            foreach($platillos as $p){
                if((int)$p['activo_promo']==1){
                $cantidad = (int)$p['cantidad_pedido'];
                $cantidadPromo  = (int)$p['cantidad_promocion'];
                if(($cantidadPromo-$cantidad)<0){
                    $promosMultiUpdate.="UPDATE `tbpromociones` SET cantidad_promocion = 0 WHERE idPlatillo = ".$p['idplatillo'].";";
                    
                }else{
             $promosMultiUpdate.="UPDATE `tbpromociones` SET cantidad_promocion = cantidad_promocion - ".$cantidad." WHERE idPlatillo = ".$p['idplatillo'].";";
            }
        }
        }
        }
        if($promosMultiUpdate!=""){
        $sql->ejecutarNoQuery($promosMultiUpdate);
        }
        $idCaja =  $_SESSION['usr']['caja']['idCaja'];
        
            $query ="INSERT INTO `tbcuenta`(idMesa,idCaja,idUsuario,`fecha`, `total`,idMetodoPago,descuento) VALUES (?,?,?,NOW(),?,?,?);";
            $sql->execQueryBinders($query,array("iiidid", $request->mesa,$idCaja,$idUsr,$request->total,$request->metodoPago,$request->descuento));
            $idCuenta = $sql->getLastId();
            $ticketQuery="";
            if(isset($request->platillos)){
                if(count($request->platillos)>0){
            foreach($request->platillos as $platillo){
                if(isset($platillo->idplatillo)){
                $platillo->id = $sql->filter_input($platillo->idplatillo);
                }else{
                    $platillo->id = $sql->filter_input($platillo->id);
                    
                }
                $platillo->cantidad = $sql->filter_input($platillo->cantidad);
                
                $ticketQuery.="INSERT INTO `tbtickets`(`idCuenta`, `idPlatillo`, `cantidad`) 
                VALUES(".$idCuenta.",".$platillo->id.",".$platillo->cantidad.");";

            }
            $sql->ejecutarNoQuery($ticketQuery);

        }
        }else{
            $query="SELECT idplatillo AS id,cantidad FROM `tbpedido_platillo` WHERE idpedido=?";
            $platillos = $sql->get_bind_results($query,array("i",$request->idPedido));
            foreach($platillos as $platillo){
                $ticketQuery.="INSERT INTO `tbtickets`(`idCuenta`, `idPlatillo`, `cantidad`) 
                VALUES(".$idCuenta.",".$platillo['id'].",".$platillo['cantidad'].");";
            }
            $sql->ejecutarNoQuery($ticketQuery);
            

        }

/*
            $query="INSERT INTO `tbcuenta_pedido`(`idcuenta`, `idPedido`) VALUES (?,?);";
            $sql->execQueryBinders($query,array("ii",$idCuenta,$request->idPedido));
            */
            if($request->metodoPago=="3"){
            $query="UPDATE `tbcaja` SET `total_fin` = `total_fin` + ? WHERE `idUsr` = ?";
            $sql->execQueryBinders($query,array("di",$request->total,$idUsr));
            }
            $respuesta =array();
            $errores = $sql->getErrorLog();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito'] = true;
                $respuesta['idCuenta'] =  $idCuenta;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
            break;
        case "terminarCuenta":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
           // $query="UPDATE `tbpedidos` SET despachado = 1 WHERE idpedido = ?";
            //$sql->execQueryBinders($query,array("i",$request->idPedido));
          $res =  descontarInventario($request->idPedido);

          actualizarVentasPlatillo($request->idPedido);
         
           $query="DELETE FROM `tbpedido_platillo` WHERE idpedido = ?";
            $sql->execQueryBinders($query,array("i",$request->idPedido));
            $query ="UPDATE `tbmesas` SET ocupado = 0 ,comensales = 0 WHERE idmesa = ?";
            $sql->execQueryBinders($query,array("i",$request->idMesa));

             $sql->execQueryBinders("DELETE FROM `tbpedidos_llevar` WHERE `idPedido` = ?",array("i",$request->idPedido));
               $query="DELETE FROM `tbpedidos` where idpedido=?";
               $sql->execQueryBinders($query,array("i",$request->idPedido));
            $respuesta =array();
            $errores = $sql->getErrorLog();
            if(count($errores)>0){
                $respuesta['exito'] = false;
                $respuesta['errores'] = $errores;
            }else{
                $respuesta['exito'] = true;

            }
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

            break;
        case "updateUsr":
            session_start();
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