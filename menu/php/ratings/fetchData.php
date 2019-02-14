<?php
if(isset($_GET['accion'])){
    include ("../../../administrador/PHP/new/functions.php");
    $sql= createMysqliConnection();
switch($_GET['accion']){
case "buscarTicket":
$query="SELECT DISTINCT tbplatillos.idplatillo AS id, tbplatillos.descripcion, tbplatillos.imagepath AS img, tbplatillos.nombre, tbplatillos.precio FROM `tbtickets` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbtickets.idPlatillo WHERE tbtickets.idCuenta = ? 
AND tbtickets.idplatillo NOT 
IN( SELECT idPlatillo FROM tb_platillo_ticket_rating 
WHERE idCuenta = ? )";
$platillos = $sql->get_bind_results($query,array("ii",$_GET['idTicket'],$_GET['idTicket']));
print_r(json_encode($platillos,JSON_UNESCAPED_UNICODE));
break;

}
}

?>