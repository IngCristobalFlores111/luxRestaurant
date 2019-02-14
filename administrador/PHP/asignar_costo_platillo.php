<?php
include ("functions.php");

$idplatillo = $_POST['idplatillo'];
$costo = $_POST['costo'];

$cantidades = $_POST['cantidades'];
$ids_ingredientes = $_POST['ids_ingredientes'];

$query = "UPDATE tbplatillos SET costo='$costo' WHERE idplatillo =$idplatillo";
ejecutarSQLCommand($query);

// actualizar receta con nuevas cantidades
$aCantidades = explode(":",$cantidades);
$aIdsIngredientes = explode(":",$ids_ingredientes);


$query ='';
for($i=0;$i<count($aCantidades);$i++)
{
	$cantidad = $aCantidades[$i];
	$ingrediente = $aIdsIngredientes[$i];
	if(trim($cantidad)!=''  && trim($ingrediente)!='')
	{
	
	$query =$query."UPDATE recetas SET cantidad =$cantidad WHERE idplatillo=$idplatillo AND id_ingrediente=$ingrediente ;";
	}
	
	
	
}
ejecutarSQLCommand($query);





?>