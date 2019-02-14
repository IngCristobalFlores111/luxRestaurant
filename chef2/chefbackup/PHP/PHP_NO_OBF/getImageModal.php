<?php
include ('functions.php');
$idPedido = $_POST['id'];
$query = "SELECT tbplatillos.nombre,tbplatillos.imagepath FROM tbplatillos INNER JOIN tbpedidos ON tbpedidos.nomplatillo = tbplatillos.nombre WHERE idpedido=$idPedido";
$result = execResultSet($query);
echo $result[0]['nombre'].":".$result[0]['imagepath'];


?>