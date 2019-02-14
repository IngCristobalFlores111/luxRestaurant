<?php
include ("functions.php");

$query = "SELECT `id_unidad`,`nombre` FROM `unidad` ORDER BY nombre LIMIT 10";	
if(isset($_POST['query']))
{
	$search = $_POST['query'];
	
	$search = clean_string($search);
	$query = "SELECT `id_unidad`,`nombre` FROM `unidad` WHERE nombre='$search' ORDER BY nombre LIMIT 10";	

	
}
$result = execResultSet($query);

if(is_null($result))
{
	echo "<tr><td>No hay unidades que mostrar<td></td></tr>";
	exit();
	
	
}

$output = "";

$method = 0; // es 0 cuando es salida a tabla y 1 cuando es salida a select
if(isset($_POST['s']))  // sera para un select y no para una tabla
{
	$method = 1;
}

foreach($result as $unidad)
{
	$nombre = $unidad['nombre'];
	$id = $unidad['id_unidad'];
	
	
	if($method==1){
		$output = $output."<option>$nombre</option>";
	}
	else
	{
		
		$output = $output."<tr>";
		$output = $output."<td>$id</td>";

		$output = $output."<td>$nombre</td>";
		$output = $output."</tr>";
	}
}

echo $output;



?>