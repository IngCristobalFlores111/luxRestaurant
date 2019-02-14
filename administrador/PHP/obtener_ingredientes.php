<?php
include ("functions.php");

$idplatillo = $_POST['id_platillo'];

$query = "SELECT i.id_ingrediente,i.nombre,i.costo,i.unidad,r.cantidad FROM `ingrediente` i INNER JOIN recetas r ON r.`id_ingrediente`=i.`id_ingrediente` WHERE r.idplatillo=$idplatillo";
echo getJSONResultSQL($query);




?>