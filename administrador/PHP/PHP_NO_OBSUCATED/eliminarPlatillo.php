<?php
include ("functions.php");
$idpedido =$_POST['idpedido'];

// BUSCAR a que orden pertenece el pedido
$query  ="SELECT idorden FROM tbpedidos WHERE idpedido=$idpedido";
$raworden = execResultSet($query);
$idOrder  = $raworden[0]['idorden'];

//elimnar de tabla pedidos
$query  ="DELETE FROM tbpedidos WHERE idpedido=$idpedido";
ejecutarSQLCommand($query);
// obtener pedidos de esa cuenta
$query ="SELECT pedidos FROM tbcuenta WHERE idorden=$idOrder";
$res = execResultSet($query);
$pedidos = explode(":",$res[0]['pedidos']);
$newpedidos ="";

foreach($pedidos as $pedido)
{
 

    if(strcmp($pedido,$idpedido)==0)// si el pedido a eliminar es igual a algun pedido dentro de los pedidos de la cuenta 
    {
     
    
    }
    else
    {
        $newpedidos=$newpedidos.$pedido.":";
    }
    


}
// actualizar pedidos de la cuenta 

$query ="UPDATE tbcuenta SET pedidos='$newpedidos' WHERE idorden=$idOrder";
echo $query;
ejecutarSQLCommand($query);

?>