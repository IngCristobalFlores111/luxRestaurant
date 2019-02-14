<?php
include ("functions.php");

$nombre = $_POST['nombre'];
$cantidad = $_POST['cantidad'];
$unidad = $_POST['unidad'];
$costo = $_POST['costo'];
$en_almacen = $_POST['en_almacen'];
$fecha_caducidad = $_POST['fecha_caducidad'];

$nombre = str_replace('"',"'",$nombre);
$nombre = htmlspecialchars($nombre,ENT_COMPAT);

$cantidad = str_replace('"',"'",$cantidad);
$cantidad = htmlspecialchars($cantidad,ENT_COMPAT);

$unidad = str_replace('"',"'",$unidad);
$unidad = htmlspecialchars($unidad,ENT_COMPAT);

$costo = str_replace('"',"'",$costo);
$costo = htmlspecialchars($costo,ENT_COMPAT);

$query = "INSERT INTO `dbluxrestaurant`.`ingrediente` (`id_ingrediente`, `nombre`, `cantidad`, `unidad`, `costo`,fecha_caducidad,en_almacen) VALUES (NULL, \"$nombre\", \"$cantidad\", \"$unidad\", \"$costo\",'$fecha_caducidad','$en_almacen');";
ejecutarSQLCommand($query);

if(isset($_POST['id_proveedor']))
{
	$id_prov = $_POST['id_proveedor'];
	// OBTNER ID MAS RECIENTES 
	$result = execResultSet("SELECT `id_ingrediente` FROM `ingrediente` ORDER BY `id_ingrediente` DESC LIMIT 1");
	
	$id_ingrediente = $result[0]['id_ingrediente'];
	
	ejecutarSQLCommand("INSERT INTO `dbluxrestaurant`.`proveedor_ingrediente` (`id_proveedor`, `id_ingrediente`) VALUES ('$id_prov', '$id_ingrediente');");
	
	
	
	
	
}


?>

