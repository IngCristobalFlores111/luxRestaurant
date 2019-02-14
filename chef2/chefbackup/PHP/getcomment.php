<?php

include ('functions.php');
$id = $_POST['idplatillo'];
$result = execResultSet("SELECT comentario,nomplatillo FROM tbpedidos WHERE idpedido=$id");
$nombre = $result[0]['nomplatillo'];
$comentario =  $result[0]['comentario'];
echo $nombre.":".$comentario;
?>