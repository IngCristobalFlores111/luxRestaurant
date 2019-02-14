<?php
include ("functions.php");
$nombre_unidad = $_POST['nombre'];


$nombre_unidad = clean_string($nombre_unidad);

$check_query = "SELECT COUNT(`id_unidad`) AS count FROM `unidad` WHERE UCASE(nombre)=UCASE('$nombre_unidad')";
$check = execResultSet($check_query);

if($check[0]['count']>0)
{
	echo"Esta unidad ya existe";
	exit();
}


$query = "INSERT INTO `dbluxrestaurant`.`unidad` (`id_unidad`, `nombre`) VALUES (NULL, '$nombre_unidad');";

ejecutarSQLCommand($query);

?>