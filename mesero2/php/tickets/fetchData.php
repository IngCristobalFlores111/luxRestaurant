<?php
if(isset($_GET['accion'])){
    include("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
     case "ticketsMesa":
     $idMesa = $_GET['idMesa'];
     $query="SELECT tbcuenta.total,tbcuenta.idcuenta AS id,tbcuenta.fecha,tbcuenta.llevar,tbcuenta.descuento,tbmetodos_pago_entradas.nombre AS metodoPago FROM `tbcuenta` INNER JOIN tbmetodos_pago_entradas ON tbmetodos_pago_entradas.idMetodoPago = tbcuenta.idMetodoPago
     WHERE tbcuenta.idMesa = ? ORDER BY tbcuenta.fecha DESC LIMIT 25";
     $cuentas = $sql->get_bind_results($query,array("i",$idMesa));
     print_r(json_encode($cuentas,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
     
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