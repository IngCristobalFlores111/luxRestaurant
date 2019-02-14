<?php
include ("functions.php");

$query = '';
if(isset($_POST['id_ing'])) // si se manda un ingrediente, filtrar por ingrediente
{
	$id_ingrediente = $_POST['id_ing'];
	$query = "SELECT id_ingrediente,nombre,cantidad,unidad,costo FROM `ingrediente` WHERE `id_ingrediente`=$id_ingrediente";
	echo getJSONResultSQL($query);
	
	
}
else  // mandar todos los ingredientes
{
	$query = "SELECT id_ingrediente,nombre,cantidad,unidad,costo FROM `ingrediente` ORDER BY `id_ingrediente` DESC LIMIT 11";
	echo getJSONResultSQL($query);
	
}




?>