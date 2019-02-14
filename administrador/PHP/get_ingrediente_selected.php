<?php
include ("functions.php");
$id_ingrediente = $_POST['idIng'];
$query ="SELECT cantidad_almacen,cantidad_cocina,`id_ingrediente`,`nombre`,`cantidad`,`unidad`,`costo`,DATE_FORMAT(fecha_caducidad,'%Y-%m-%d') AS fecha_caducidad,`en_almacen` FROM `ingrediente` WHERE `id_ingrediente`=$id_ingrediente";

echo getJSONResultSQL($query);

?>