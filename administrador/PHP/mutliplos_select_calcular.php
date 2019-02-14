<?php
include ("functions.php");
$nombre_unidad_base = $_POST['nombre_base'];
$nombre_unidad_base = clean_string($nombre_unidad_base);

$query = "SELECT `id_unidad` FROM `unidad` WHERE nombre='$nombre_unidad_base'";

$r = execResultSet($query);
$id_unidad_base = $r[0]['id_unidad'];


$query = "SELECT `id_multiplo`,`nombre`,multiplicador FROM `unidad_multiplos` WHERE `id_unidad`=$id_unidad_base";
$result = execResultSet($query);
if(is_null($result))
{
	echo "<option>No hay unidades que mostrar</option>";
	exit();
}



$output ='';
foreach($result as $multiplo)
{
	$id_multiplo = $multiplo['id_multiplo'];
	$nombre_multiplo = $multiplo['nombre'];
	$multiplicador = $multiplo['multiplicador'];
	$output = $output."<option value='$multiplicador'>$nombre_multiplo</option>";
}
echo $output;







?>