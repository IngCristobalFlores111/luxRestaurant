
<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
     case "init":
     $query="SELECT tbusuarios.iduser AS id,tbusuarios.nombre,tbtipo_usuario.nombre AS rol FROM `tbusuarios` INNER JOIN tbtipo_usuario ON tbtipo_usuario.idTipo = tbusuarios.idTipo";
     $usuarios= $sql->executeQuery($query);
     $query="SELECT idMetodoPago AS id,nombre FROM `tbmetodos_pago_entradas`";
     $metodos = $sql->executeQuery($query);
     $query="SELECT nombre,idplatillo AS id,precio,descripcion FROM `tbplatillos`";
     $platillos = $sql->executeQuery($query);
     $query="SELECT DISTINCT  tbcuenta.descuento,tbcuenta.idcuenta AS id,tbcuenta.fecha,tbcuenta.total,tbmetodos_pago_entradas.nombre AS metodoPago, tbusuarios.nombre AS usuario,IF(tbcuenta.llevar=0,'NO','SI') AS llevar FROM `tbcuenta` INNER JOIN tbusuarios ON tbusuarios.iduser = tbcuenta.idUsuario INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.idMetodoPago ORDER BY `id` DESC LIMIT 50";
     $cuentas = $sql->executeQuery($query);
     $output = array("cuentas"=>$cuentas,"platillos"=>$platillos,"usuarios"=>$usuarios,"metodosPago"=>$metodos);
     print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
     

     break;
     case "buscarCuentas":
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata,true);

     $fechaInicio = $sql->filter_input($request['fechaInicio']);
     $fechaFin = $sql->filter_input($request['fechaFin']);
     $usuario = $sql->filter_input($request['usr']);
     $platillos= $request['platillos'];
     $monto=$sql->filter_input($request['monto']);
     $metodoPago=$sql->filter_input($request['metodoPago']);
     $idCuenta=(int)$sql->filter_input($request['idCuenta']);
     
if($idCuenta>0){
$query="SELECT DISTINCT  tbcuenta.descuento,tbcuenta.idcuenta AS id,tbcuenta.fecha,tbcuenta.total,tbmetodos_pago_entradas.nombre AS metodoPago, tbusuarios.nombre AS usuario,IF(tbcuenta.llevar=0,'NO','SI') AS llevar  FROM `tbcuenta` INNER JOIN tbusuarios ON tbusuarios.iduser = tbcuenta.idUsuario INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.idMetodoPago 
LEFT JOIN tbtickets ON tbtickets.idCuenta  = tbcuenta.idcuenta
WHERE tbcuenta.idcuenta = ?";
$cuentas = $sql->get_bind_results($query,array("i",$idCuenta));
print_r(json_encode($cuentas,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

}else{

     $paraLlevar = $request['llevar'];
     $paraLlevar = ($paraLlevar==1)?1:0;
     $qPlatillos ="";
     if(count($platillos)>0){
     foreach($platillos AS $p){
         $qPlatillos.=$p['id'].",";
     }
     $qPlatillos = rtrim($qPlatillos, ',');
    }
    
     $query="SELECT DISTINCT  tbcuenta.descuento,tbcuenta.idcuenta AS id,tbcuenta.fecha,tbcuenta.total,tbmetodos_pago_entradas.nombre AS metodoPago, tbusuarios.nombre AS usuario,IF(tbcuenta.llevar=0,'NO','SI') AS llevar  FROM `tbcuenta` INNER JOIN tbusuarios ON tbusuarios.iduser = tbcuenta.idUsuario INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.idMetodoPago 
     LEFT JOIN tbtickets ON tbtickets.idCuenta  = tbcuenta.idcuenta
     WHERE tbcuenta.fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
    if($usuario!="todos"){
        $query.=" AND tbcuenta.idUsuario = '$usuario' ";
    }
    if($qPlatillos!=""){
        $query.=" AND 
        tbtickets.idPlatillo  IN ($qPlatillos) ";
    }
    if((float)$monto>0){
        $query.=" AND tbcuenta.total<= '$monto' ";
    }
    if($metodoPago!="todos"){
        $query.=" AND tbcuenta.idMetodoPago =  $metodoPago ";
    }
    if($paraLlevar==1){
        $query.=" AND tbcuenta.llevar =  1";
        
    }
    $query.=" ORDER BY tbcuenta.idcuenta DESC LIMIT 50";
    $cuentas = $sql->executeQuery($query);
    print_r(json_encode($cuentas,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
} 
    break;
     case "obtenerDetallesCuenta":
     $query="SELECT tbplatillos.nombre,tbplatillos.precio,tbtickets.cantidad,tbtickets.cantidad*tbplatillos.precio AS importe
     FROM tbtickets INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbtickets.idPlatillo
     WHERE tbtickets.idCuenta = ?";
     $detalles = $sql->get_bind_results($query,array("i",$_GET['idCuenta']));
     print_r(json_encode($detalles,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
     

     break;
     case "imprimirTicket":
     $idCuenta = $_GET['idCuenta'];
     $respuesta = array();
     if(file_exists("ticket_".$idCuenta.".pdf")){
         $respuesta['exito'] = true;
         $respuesta['msg'] ="Ticket ya existe";
         $respuesta['ticket'] ="ticket_".$idCuenta.".pdf";
         print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
     }else{
         include("/var/www/luxline.com.mx/phpsandbox/wkWorks/generateTicket.php");
         generateTicket($idCuenta,"0");
 
         
     }



     break;


    }
}
    

?>