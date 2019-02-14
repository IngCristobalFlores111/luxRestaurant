<?php
include ("functions.php");

$idplatillo = $_POST['id_platillo'];
$idIngrediente = $_POST['id_ingrediente'];

$query = "DELETE FROM recetas WHERE idplatillo=$idplatillo AND id_ingrediente=$idIngrediente";
ejecutarSQLCommand($query);
?>