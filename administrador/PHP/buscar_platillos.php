<?php
include ("functions.php");
$nombre_platillo = $_POST['nombre_platillo'];
$nombre_platillo = str_replace("'",'"',$nombre_platillo);
$nombre_platillo = htmlspecialchars($nombre_platillo,ENT_COMPAT);
$nombre_platillo = strtoupper($nombre_platillo);
$out ='';
$query = "SELECT idplatillo,nombre,categoria,precio,costo,imagepath FROM `tbplatillos` WHERE UCASE(nombre) LIKE '%$nombre_platillo%'";
$results = execResultSet($query);

if(is_null($results))
{
	echo "No se encontraron resultados";
	exit();
	
}

foreach($results as $platillo)
{
	$id_platillo = $platillo['idplatillo'];
	$nombre = $platillo['nombre'];
	$categoria = $platillo['categoria'];
	$precio = $platillo['precio'];
	$costo = $platillo['costo'];
	$imagepath = $platillo['imagepath'];

	
	$out = $out."<tr id='plat_$id_platillo'>";
	$out = $out."<td>$nombre</td>";
	$out = $out."<td>$categoria</td>";
	$out = $out."<td>$$precio</td>";
	$out = $out."<td>$$costo</td>";
	$out = $out."<td style='text-align:center;'><img class='img-rounded'  width = '150' height='100'  src='../images/$imagepath'  /></td>";

	
	$out = $out."</tr>";
	
}
echo $out;




?>