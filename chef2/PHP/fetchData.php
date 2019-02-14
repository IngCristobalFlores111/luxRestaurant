<?php
session_start();

include("functions.php");
if(isset($_GET['accion'])){
    $sql = createMysqliConnection();
     switch($_GET['accion']){
        case "init":
            $query="SELECT tbplatillos.idCategoria,tbmesas.nombre AS mesa, tbplatillos.nombre,tbplatillos.imagepath,tbplatillos.descripcion,tbplatillos.precio,tbpedido_platillo.comentarios,tbpedido_platillo.cantidad,tbpedido_platillo.cantidad_terminado,tbpedido_platillo.cantidad_servido,tbpedido_platillo.idPedidoPlatillo AS id,tbpedido_platillo.fecha_llegada FROM `tbpedido_platillo` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo INNER JOIN tbpedidos ON tbpedidos.idpedido = tbpedido_platillo.idpedido INNER JOIN tbmesas ON tbmesas.idmesa = tbpedidos.idmesa WHERE tbplatillos.idCategoria!=3 AND tbpedido_platillo.cantidad_terminado!=tbpedido_platillo.cantidad ORDER BY tbpedido_platillo.fecha_llegada DESC";
     $pedidos =  $sql->executeQuery($query);
           $query ="SELECT * FROM `tbplatillos_categorias` WHERE idCategoria!=3";
           $categorias =  $sql->executeQuery($query);
           $result = array("pedidos"=>$pedidos,"categorias"=>$categorias);
           print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
           $query ="INSERT INTO trigger_update_ui (usuario,flag) VALUES(?,?) ON DUPLICATE KEY UPDATE flag=0";
           $idUsr = $_SESSION['usr']['idusr'];
           $sql->execQueryBinders($query,array("ii",$idUsr,0));
            break;
        case "refreshPedidos":
            $idUsr = $_SESSION['usr']['idusr'];
            /*
            $query ="SELECT flag FROM trigger_update_ui WHERE usuario = ?";
            $result = $sql->get_bind_results($query,array("i",$idUsr));
            if($result[0]['flag']!='0'){
            */
                $query="SELECT tbplatillos.idCategoria,tbmesas.nombre AS mesa, tbplatillos.nombre,tbplatillos.imagepath,tbplatillos.descripcion,tbplatillos.precio,tbpedido_platillo.comentarios,tbpedido_platillo.cantidad,tbpedido_platillo.cantidad_terminado,tbpedido_platillo.cantidad_servido,tbpedido_platillo.idPedidoPlatillo AS id,tbpedido_platillo.fecha_llegada FROM `tbpedido_platillo` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpedido_platillo.idplatillo INNER JOIN tbpedidos ON tbpedidos.idpedido = tbpedido_platillo.idpedido LEFT JOIN tbmesas ON tbmesas.idmesa = tbpedidos.idmesa WHERE tbplatillos.idCategoria!=3 AND tbpedido_platillo.cantidad_terminado!=tbpedido_platillo.cantidad ORDER BY tbpedido_platillo.fecha_llegada DESC";
                $pedidos =  $sql->executeQuery($query);
                print_r(json_encode($pedidos,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
                $query ="INSERT INTO trigger_update_ui (usuario,flag) VALUES(?,?) ON DUPLICATE KEY UPDATE flag=0";
                $sql->execQueryBinders($query,array("ii",$idUsr,0));
           // }else{
             //   echo 0;
            //}

            break;
        case "getUsr":
            if(isset($_SESSION['usr'])){
                $postdata = file_get_contents("php://input");
                $request = json_decode($postdata);
                $idUsr = $_SESSION['usr']['idusr'];
                $query ="SELECT * FROM `tbusuarios` WHERE iduser = ? AND pass = ?";
              $usr =   $sql->get_bind_results($query,array("is",$idUsr,$request->pass));
               
             }



            break;

    }
}


?>