<?php
include ("functions.php");

$nombre_ingrediente = $_POST['ingrediente'];
$nombre_ingrediente = str_replace("'",'"',$nombre_ingrediente);
$nombre_ingrediente = htmlspecialchars($nombre_ingrediente,ENT_COMPAT);
$nombre_ingrediente = strtoupper($nombre_ingrediente);

$idplatillo =$_POST['idplatillo'];


$query = "SELECT `id_ingrediente`,`nombre`,`unidad`,`costo`,cantidad FROM ingrediente WHERE UCASE(`nombre`) LIKE '%$nombre_ingrediente%' AND `id_ingrediente` NOT IN (SELECT id_ingrediente FROM recetas WHERE idplatillo=$idplatillo)";
$result = execResultSet($query);

if(is_null($result))
{
	echo "No se ha encontrado el ingrediente \"$nombre_ingrediente\"";
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