<?php

include ("functions.php");
$ingredientes_a = $_POST['ingredientes'];
$idplatillo = $_POST['idplatillo'];
$ingredientes = explode(":",$ingredientes_a);

$query = '';
foreach($ingredientes as $ingrediente)
{
if(trim($ingrediente)!='')
	{
		$query = $query."INSERT INTO `dbluxrestaurant`.`recetas` (`idplatillo`, `id_ingrediente`) VALUES ('$idplatillo', '$ingrediente');";
		
		
	}	
}
ejecutarSQLCommand($query);



?>