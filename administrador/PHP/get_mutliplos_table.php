<?php
include ("functions.php");

$query = "SELECT m.`id_unidad`,m.`nombre`,m.`multiplicador`,u.nombre AS nombre_base FROM `unidad_multiplos` m INNER JOIN unidad u ON u.`id_unidad`=m.`id_unidad` ORDER BY u.nombre DESC LIMIT 15";

	if(isset($_POST['query']))
{
	$search = $_POST['query'];
	
	$search = clean_string($search);
	$query = "SELECT m.`id_unidad`,m.`nombre`,m.`multiplicador`,u.nombre AS nombre_base FROM `unidad_multiplos` m INNER JOIN unidad u ON u.`id_unidad`=m.`id_unidad` WHERE m.nombre='$search' ORDER BY u.nombre DESC LIMIT 15";

	
}
$result = execResultSet($query);

if(is_null($result))
{
	echo "<tr><td>No hay unidades que mostrar<td></td><td></td></tr>";
	exit();
	
	
}

$output = "";



foreach($result as $unidad)
{
	$nombre = $unidad['nombre'];
	$id = $unidad['id_unidad'];
	$mul= $unidad['multiplicador'];
	$nombre_base = $unidad['nombre_base'];
	
	
		
		$output = $output."<tr id='mul_$id'>";
	$output = $output."<td>$nombre_base</td>";

		$output = $output."<td>$nombre</td>";

		$output = $output."<td>$mul</td>";
		$output = $output."</tr>";
	
}

echo $output;



?>