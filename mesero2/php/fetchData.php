<?php
if(isset($_GET['accion'])){
    include("functions.php");
    $sql = createMysqliConnection();

    switch($_GET['accion']){
      case "infoLlevar":
      $idPedido = $_GET['idPedido'];
     $idPedidoPlatillo =$_GET['id'];
     $query="SELECT tbpedido_platillo.cantidad,tbplatillos.nombre,tbplatillos.precio,tbplatillos.imagepath,tbpedidos.fecha_llegada,tbclientes_llevar.nombre AS nombreCliente,tbclientes_llevar.domicilio FROM `tbpedidos_llevar` INNER JOIN tbpedidos ON tbpedidos.idpedido = tbpedidos_llevar.idPedido INNER JOIN tbpedido_platillo ON tbpedido_platillo.idpedido = tbpedidos.idpedido INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo INNER JOIN tbclientes_llevar ON tbclientes_llevar.idCliente = tbpedidos_llevar.`idCliente`
     WHERE tbpedidos_llevar.`idPedido` = ? AND tbpedido_platillo.idPedidoPlatillo = ?";
     $result = $sql->get_bind_results($query,array("ii",$idPedido,$idPedidoPlatillo));
     $result = $result[0];
     print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
     

      break;


        case "mesas":
            //tbpedidos.despachado=>  0->pendiente,1->despachado, 2->pagado
            $query="SELECT IFNULL(SUM(tbpedido_platillo.cantidad_servido),0) AS cantidad_servido,IFNULL(SUM(tbpedido_platillo.cantidad),0) AS cantidad,IFNULL(SUM(tbpedido_platillo.cantidad_terminado),0) AS cantidad_terminado,tbpedidos.despachado,tbmesas.idmesa AS id,tbmesas.nombre,tbmesas.ocupado,tbmesas.comensales,COUNT(tbpedido_platillo.idplatillo) AS pedidos FROM tbmesas LEFT JOIN tbpedidos ON tbpedidos.idmesa = tbmesas.idmesa LEFT JOIN tbpedido_platillo ON tbpedido_platillo.idpedido = tbpedidos.idpedido WHERE tbmesas.idmesa!=1  GROUP BY tbmesas.idmesa";
            $result = $sql->executeQuery($query);
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

            break;
        case "pending":
            $force_update = $_GET['force_update'];
            $respuesta = array();

            if($force_update=='1'){
                $query="SELECT tbpedido_platillo.fecha_llegada,tbplatillos.idCategoria ,tbpedido_platillo.idPedidoPlatillo AS id,tbplatillos.nombre,tbpedidos.idmesa,tbpedidos.idpedido,tbmesas.nombre AS mesa,tbpedido_platillo.comentarios,tbpedido_platillo.cantidad,tbpedido_platillo.cantidad_terminado,tbpedido_platillo.cantidad_servido FROM tbpedido_platillo INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo INNER JOIN tbpedidos ON tbpedidos.idpedido = tbpedido_platillo.idpedido LEFT JOIN tbmesas ON tbmesas.idmesa = tbpedidos.idmesa ";
                $result = $sql->executeQuery($query);

               $respuesta['platillos']=$result;
                $respuesta['update'] = true;
                print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
            }
            else{
                $query="SELECT flag FROM `trigger_update_ui` WHERE usuario = ?";
                session_start();
                $idUsr = $_SESSION['usr']['idusr'];
                $result = $sql->get_bind_results($query,array("i",$idUsr));
                if($result[0]['flag']==1){
                    $query="SELECT tbpedidos.idpedido,tbpedido_platillo.fecha_llegada,tbplatillos.idCategoria ,tbpedido_platillo.idPedidoPlatillo AS id,tbplatillos.nombre,tbpedidos.idmesa,tbmesas.nombre AS mesa,tbpedido_platillo.comentarios,tbpedido_platillo.cantidad,tbpedido_platillo.cantidad_terminado,tbpedido_platillo.cantidad_servido FROM tbpedido_platillo INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo INNER JOIN tbpedidos ON tbpedidos.idpedido = tbpedido_platillo.idpedido LEFT JOIN tbmesas ON tbmesas.idmesa = tbpedidos.idmesa";
                    $result = $sql->executeQuery($query);
                    $respuesta['platillos']=$result;
                    $respuesta['update'] = true;
                    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
                    $query="UPDATE `trigger_update_ui` SET flag=0 WHERE usuario = ?";
                    $sql->execQueryBinders($query,array("i",$idUsr));

                }else{

                    $respuesta['update'] = false;
                    print_r(json_encode($respuesta));


                }

            }

            break;
        case "initMesa":
            $query ="SELECT tbpedidos.idpedido,tbpedidos.fecha_llegada,tbmesas.idmesa,tbmesas.nombre,tbmesas.ocupado,tbmesas.comensales FROM `tbmesas` LEFT JOIN tbpedidos ON tbpedidos.idmesa = tbmesas.idmesa WHERE tbmesas.idmesa = ? AND tbpedidos.despachado = 0";

            $idMesa = $_GET['idMesa'];
           $mesa =  $sql->get_bind_results($query,array("i",$idMesa));
           if(count($mesa)==0 ||empty($mesa)||is_null($mesa)){
               $query="SELECT tbpedidos.fecha_llegada,tbmesas.idmesa,tbmesas.nombre,tbmesas.ocupado,tbmesas.comensales FROM `tbmesas` LEFT JOIN tbpedidos ON tbpedidos.idmesa = tbmesas.idmesa WHERE tbmesas.idmesa = ?";
               $mesa =  $sql->get_bind_results($query,array("i",$idMesa));

           }

           $mesa = $mesa[0];
           $query ="SELECT * FROM `tbplatillos` WHERE activado=1";
           $platillos = $sql->executeQuery($query);
           $query="SELECT tbpedido_platillo.idPedidoPlatillo,tbplatillos.idplatillo AS id,tbplatillos.nombre,tbplatillos.precio,tbplatillos.imagepath,tbpedido_platillo.comentarios,tbpedido_platillo.cantidad,tbpedido_platillo.cantidad_servido FROM `tbpedido_platillo` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo INNER JOIN tbpedidos ON tbpedidos.idpedido = tbpedido_platillo.idpedido
                   WHERE tbpedidos.idmesa = ?";
           $platillosMesa = $sql->get_bind_results($query,array("i",$idMesa));

           $data = array("mesa"=>$mesa,"platillos"=>$platillos,"platillosMesa"=>$platillosMesa);
           print_r(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));



            break;
        case "unidades":

            $query="SELECT `idUnidad` AS id,nombre FROM `tbfactura_unidades`";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));


            break;
            case "metodosPago":

            $query="SELECT `nombre`,`idMetodoPago` AS id FROM `tbmetodos_pago_entradas`";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));


            break;


    }
}


?>