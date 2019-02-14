<?php
include("functions.php");
$id_prov = $_POST['id_proveedor'];

$query ="SELECT i.id_ingrediente,i.nombre,i.cantidad,i.unidad,i.costo FROM ingrediente i INNER JOIN proveedor_ingrediente pi ON pi.`id_ingrediente` = i.`id_ingrediente` WHERE pi.`id_proveedor`=$id_prov";
$result = execResultSet($query);

if(is_null($result))
{
echo "No hay hay productos que mostrar";
	exit();

	
}

$out = '' ;
foreach($result as $ingrediente)
{
	$idIng = $ingrediente['id_ingrediente'];
	$nombre = $ingrediente['nombre'];
	$cantidad = $ingrediente['cantidad'];
	$unidad = $ingrediente['unidad'];
	$costo = $ingrediente['costo'];
$out = $out."<tr id='ing_prov_$idIng'>";
$out = $out."<td>$nombre</td>";
$out = $out."<td class='cant_prov'>$cantidad</td>";
$out = $out."<td>$unidad</td>";
$out = $out."<td class='costo_prov'>$costo</td>";
$out  = $out ."</tr>";
	
	
	
}
echo $out;





?>