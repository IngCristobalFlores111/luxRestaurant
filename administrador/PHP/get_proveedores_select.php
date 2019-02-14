<?php
include ("functions.php");

$iding = $_POST['id_ing'];

$query = "SELECT nombre,id_proveedor FROM proveedor WHERE id_proveedor NOT IN (SELECT id_proveedor FROM proveedor_ingrediente WHERE id_ingrediente='$iding')  ORDER BY nombre LIMIT 25";

if(isset($_POST['search']))
{
	
	$search = $_POST['search'];
	$search = str_replace("'",'"',$search);
	$search = htmlspecialchars($search,ENT_COMPAT);
	$search = strtoupper($search);
	$query = "SELECT nombre,id_proveedor FROM proveedor WHERE id_proveedor NOT IN (SELECT id_proveedor FROM proveedor_ingrediente WHERE id_ingrediente='$iding') AND UCASE(nombre) LIKE '%$search%' ORDER BY nombre LIMIT 25";

	
}


$result = execResultSet($query);


$select = $_POST['select'];







if(is_null($result))
{
echo "No hay proveedores que mostrar";
	exit();	
}
$out ='';
foreach($result as $proveedor)
{$nombre = $proveedor['nombre'];
	$id = $proveedor['id_proveedor'];
	
	if($select=='1')
	$out = $out."<option id='prov_modal_$id'>$nombre</option>";
	else{
		
		$out = $out."<td id='table_tmp_modal_$id'>$nombre</td>";
		}
	
	
}
echo $out;



?>