<?php
// adherir nuevo platillo a tbcuenta y crear nuevo pedido para cocina 
include ("functions.php");
$idOrder = $_POST['idcuenta'];
$idPlatillo = $_POST['idplatillo'];
$comentario =$_POST['comentario'];
$mesa = $_POST['mesa'];
$comentario= trim($comentario);
//OBTENER NOMBRE Y PRECIO DEL PLATILLO 
$query = "SELECT nombre,precio FROM tbplatillos WHERE idplatillo=$idPlatillo";
$result = execResultSet($query);
//obtenemos el nombre y precio del platillo 
$nombre_platillo = $result[0]['nombre'];
$precio_platillo = $result[0]['precio'];
// insetamos a tabla de pedidos 
$query ="INSERT INTO tbpedidos (`nomplatillo`, `mesa`, `comentario`, `despachado`, `entregado`, `idorden`) VALUES ('$nombre_platillo','$mesa','$comentario','0','0','$idOrder')";
ejecutarSQLCommand($query);
// obtenemos la clave que se genero de pedidos
$query = "SELECT idpedido FROM tbpedidos WHERE nomplatillo='$nombre_platillo' AND idorden='$idOrder' AND comentario ='$comentario'";
$raw = execResultSet($query);
$idPedido = $raw[0]['idpedido'];
//Actualizar la orden con el nuevo platillo ordenado
$query = "SELECT pedidos FROM tbcuenta WHERE idorden=$idOrder";
$rawpedidos = execResultSet($query);
$pedidos = $rawpedidos[0]['pedidos'];
$pedidos=trim($pedidos);
if($pedidos=='')
{
    $newpedidos = $idPedido;

}
else
{
$newpedidos = $pedidos.":".$idPedido;
}
$query = "UPDATE tbcuenta SET pedidos='$newpedidos', despachado=0 WHERE idorden=$idOrder";
ejecutarSQLCommand($query);
// actualizar total de la cuenta

$query = "SELECT nomplatillo FROM tbpedidos WHERE idorden=$idOrder";  // buscar los ids de los pedidos en esa cuenta 
$result = execResultSet($query);
$total = 0;
foreach($result as $platillo)
{
    
    $plat = $platillo['nomplatillo'];
    $query = "SELECT precio FROM tbplatillos WHERE nombre='$plat'";
    $res = execResultSet($query);
    foreach($res as $price)  // iterar precios de platillos
    {
        $total+=$price['precio'];
        
        
        
        
    }

}

$query ="UPDATE tbcuenta SET total = $total WHERE idorden=$idOrder";
ejecutarSQLCommand($query);


?>