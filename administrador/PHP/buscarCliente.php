<?php
include ('functions.php');
$search_string = $_POST['query'];
$search_string = str_replace('"',"'",$search_string);
$search_string = htmlspecialchars($search_string,ENT_COMPAT);
$search_string = strtoupper($search_string);

$query = "SELECT `idcliente`,`email`,`RFC`,`nombre`,CONCAT(`calle`,' #',`noExterior`, ' ',`noInterior`,' ',`colonia`, ' ',`CodigoPostal`,' ',`municipio`,' ',`estado`,' ',`pais`) as domicilio_fiscal FROM `clientes_facturacion`  WHERE remove_accents(UCASE(CONCAT(nombre,\" \",email,\" \",calle,\" \",RFC, \" \",estado,\" \",municipio,\" \",estado, \" \",colonia))) 
LIKE \"%$search_string%\" ORDER BY `nombre` ASC LIMIT 10";
$result = execResultSet($query);


if(is_null($result))
{
	echo "No se encontraron resultados";
	exit();
}
$output = '';

foreach($result as $cliente)
{
	
	$output = $output."<tr id=".$cliente['idcliente'].">";
	$output = $output."<td>";
	$output = $output.$cliente['nombre'];
	$output = $output."</td>";
	$output = $output."<td>";
	$output = $output.$cliente['RFC'];
	$output = $output."</td>";
	$output = $output."<td>";
	$output = $output.$cliente['email'];
	$output = $output."</td>";
	$output = $output."<td>";
	$output = $output.$cliente['domicilio_fiscal'];
	$output = $output."</td>";
	
	$output = $output."</tr>";
	
}
echo $output;










?>