<?php
include ("functions.php");



$idplatillo =$_POST['idplatillo'];


$query ="SELECT i.`id_ingrediente`,i.`nombre`,i.`unidad`,i.`costo`,i.cantidad FROM `recetas` r INNER JOIN ingrediente i ON i.`id_ingrediente`=r.`id_ingrediente` WHERE r.`idplatillo`=$idplatillo";
$result = execResultSet($query);

if(is_null($result))
{
	echo "No se ha encontrado el ingredientes para este platillo";
	exit();
}
$out = '';

foreach($result as $ingrediente)
{
	
	$nombre = $ingrediente['nombre'];
	$unidad = $ingrediente['unidad'];
	$costo = $ingrediente['costo'];
	$cantidad = $ingrediente['cantidad'];
	$id = $ingrediente['id_ingrediente'];
	
	
	$out = $out."<tr id='ing_buscar_$id'>";
	$out = $out."<td>$nombre</td>";
	$out = $out."<td>$unidad</td>";
	
	$out = $out."<td>$costo</td>";
	
	$out = $out."<td>$cantidad</td>";

	
	

	
}

echo $out;




?>