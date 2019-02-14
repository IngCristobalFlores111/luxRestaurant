<?php
include("functions.php");
$idOrder = $_POST['idcuenta'];

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
echo $total;







?>

