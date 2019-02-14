<?php
include("functions.php");

$id_ingrediente = $_POST['id_ing'];
$query = "DELETE FROM `ingrediente` WHERE id_ingrediente=$id_ingrediente";
ejecutarSQLCommand($query);
$query = "DELETE FROM `proveedor_ingrediente` WHERE id_ingrediente=$id_ingrediente";
ejecutarSQLCommand($query);


?>