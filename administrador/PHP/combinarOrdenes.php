<?php
include("functions.php");
$idCuenta1 = $_POST['cuenta1'];
$idCuenta2 = $_POST['cuenta2'];
// Traer pedidos de la cuenta 1
$query ="SELECT pedidos FROM tbcuenta WHERE idorden=$idCuenta1";
$raw = execResultSet($query);
$pedidos1 = $raw[0]['pedidos'];
// traer pedidos de la cuenta 2
$query ="SELECT pedidos FROM tbcuenta WHERE idorden=$idCuenta2";
$raw = execResultSet($query);
$pedidos2 = $raw[0]['pedidos'];
// obtenemos los pedidos finales
$pedidos = $pedidos1.":".$pedidos2;
// iteramos los pedidos finales para actualizar el nuemro de cuenta de casa uno
$peds = explode(":",$pedidos);
$pedidosFinal = '';
foreach($peds as $pedido)
{
    $pedido = trim($pedido);
    $pedidosFinal = $pedidosFinal.$pedido.":";
    if($pedido!='')
    {
        $query  = "UPDATE tbpedidos SET idorden=$idCuenta2 WHERE idpedido=$pedido";
        ejecutarSQLCommand($query);
    }
}
// obtener total de la cuenta 
$total = obtenerTotalCuenta($idCuenta2);
// eliminar cuenta 1 
$query  ="DELETE FROM tbcuenta WHERE idorden=$idCuenta1";
ejecutarSQLCommand($query);
// actualizar cuenta 1 
$query = "UPDATE tbcuenta SET pedidos='$pedidosFinal',total=$total WHERE idorden=$idCuenta2";
ejecutarSQLCommand($query);









?>