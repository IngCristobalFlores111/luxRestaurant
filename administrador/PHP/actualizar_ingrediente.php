<?php
include("functions.php");
$id_ingrediente = $_POST['idingrediente'];
$cantidad = $_POST['cantidad'];
$unidad = $_POST['unidad'];
$costo = $_POST['costo'];

$cantidad = str_replace("'",'"',$cantidad);
$unidad = str_replace("'",'"',$unidad);
$costo = str_replace("'",'"',$costo);

$cantidad = htmlspecialchars($cantidad,ENT_COMPAT);
$unidad = htmlspecialchars($unidad,ENT_COMPAT);
$costo = htmlspecialchars($costo,ENT_COMPAT);

$nuevos_provedoresRAW = $_POST['provedores'];
$proveedores = explode(":",$nuevos_provedoresRAW);



$en_almacen = $_POST['en_almacen'];
$fech_caducidad = $_POST['fecha_caducidad'];


$en_almacen = str_replace("'",'"',$en_almacen);
$en_almacen = htmlspecialchars($en_almacen,ENT_COMPAT);

$fech_caducidad = str_replace("'",'"',$fech_caducidad);
$fech_caducidad = htmlspecialchars($fech_caducidad,ENT_COMPAT);


$cant_almacen = $_POST['cantidad_almacen'];
$cant_cocina = $_POST['cantidad_cocina'];


$cant_almacen = str_replace("'",'"',$cant_almacen);
$cant_almacen = htmlspecialchars($cant_almacen,ENT_COMPAT);

$cant_cocina = str_replace("'",'"',$cant_cocina);
$cant_cocina = htmlspecialchars($cant_cocina,ENT_COMPAT);

$nombre = $_POST['nuevo_nombre'];
$nombre = str_replace("'",'"',$nombre);
$nombre = htmlspecialchars($nombre,ENT_COMPAT);


$query = "UPDATE ingrediente SET nombre='$nombre', cantidad_almacen='$cant_almacen',cantidad_cocina='$cant_cocina',fecha_caducidad ='$fech_caducidad',en_almacen='$en_almacen', cantidad='$cantidad', unidad ='$unidad', costo ='$costo' WHERE id_ingrediente=$id_ingrediente";
ejecutarSQLCommand($query);

ejecutarSQLCommand("DELETE FROM proveedor_ingrediente WHERE id_ingrediente='$id_ingrediente'");


$query = '';
foreach($proveedores as $proveedor)
{
	if(trim($proveedor)!='')
	{
		
		$res = execResultSet("SELECT id_proveedor FROM proveedor WHERE nombre='$proveedor'");
		$id_prov = $res[0]['id_proveedor'];
		$query = $query. "INSERT INTO `dbluxrestaurant`.`proveedor_ingrediente` (`id_proveedor`, `id_ingrediente`) VALUES ('$id_prov', '$id_ingrediente');";
		
		
		
		
		
		
	}
	
	
}
ejecutarSQLCommand($query);





?>