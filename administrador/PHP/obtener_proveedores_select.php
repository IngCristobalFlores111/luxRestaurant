<?php
include ("functions.php");

$query = "SELECT `id_proveedor`,`nombre`,`RFC` FROM `proveedor` ORDER BY nombre ASC";

$result = execResultSet($query);

if(is_null($result))
{
echo "No hay proveedores que mostrar";	
	exit();
}
$out = '';
foreach($result as $proveedor)
{
	$nombre = $proveedor['nombre'];
	$RFC = $proveedor['RFC'];
	$id = $proveedor['id_proveedor'];

	
	$out = $out."<option value='$id'>$nombre : $RFC</option>";
	
	
	
	
}
echo $out;











?>