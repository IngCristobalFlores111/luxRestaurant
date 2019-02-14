<?php
include ("functions.php");
$Idplatillo = $_POST['idplatillo'];

$query ="SELECT nombre,idplatillo FROM tbplatillos WHERE idplatillo!=$Idplatillo LIMIT 50";

if( isset($_POST['query']))
{
	$search = $_POST['query'];
	$search = str_replace("'",'"',$search);
	$search = htmlspecialchars($search,ENT_COMPAT);
	$search = strtoupper($search);
	$query ="SELECT nombre,idplatillo FROM tbplatillos WHERE idplatillo!=$Idplatillo AND UCASE(nombre) LIKE '%$search%' LIMIT 50";
	
}



$result = execResultSet($query);

if(is_null($result))
{
	echo "<option>No hay platillos que mostrar</option>";
	exit();
}


$out ='';
foreach($result as $platillo)
{
	$Id=$platillo['idplatillo'];
	$nombre = $platillo['nombre'];
	$out = $out."<option value='$Id' >$nombre</option>";
	
}
echo $out;





?>