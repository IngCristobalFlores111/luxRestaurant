<?php
include ("functions.php");

$query = "SELECT `id_proveedor`,`nombre`,`RFC`,`telefono`,`email` FROM `proveedor` ORDER BY nombre ASC LIMIT 25";


if(isset($_POST['query']))
{
	$search = $_POST['query'];
	$search = str_replace("'",'"',$search);
	$search = htmlspecialchars($search,ENT_COMPAT);
	$search = strtoupper($search);
	
$query  = "SELECT `id_proveedor`,`nombre`,`RFC`,`telefono`,`email` FROM `proveedor` WHERE UCASE(CONCAT(nombre,' ',RFC,' ',telefono, ' ' ,email)) LIKE '%$search%'";

}

$result = execResultSet($query);
if( is_null($result))
{
	echo "No hubo resultados";
	exit();
}


$out = '';
foreach($result as $proveedor)
{
	$id = $proveedor['id_proveedor'];
	$nombre = $proveedor['nombre'];
	$RFC = $proveedor['RFC'];
	$telefono = $proveedor['telefono'];
	$email = $proveedor['email'];

	
	$out = $out."<tr id='prov_id_$id'>";
	
	$out = $out."<td>$nombre</td>";
	$out = $out."<td>$RFC</td>";
	$out = $out."<td>$telefono</td>";
	$out = $out."<td>$email</td>";
	$out  = $out."<td> <p><button onclick='actualizar_proveedor_info($id)' class='btn btn-default'>Actualizar</button></td>";
	$out = $out ."</tr>";
	

	
	}
	echo $out;


?>