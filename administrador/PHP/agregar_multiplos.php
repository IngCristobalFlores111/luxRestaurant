<?php
include ("functions.php");
$nombre_unidad_base = $_POST['nombre_unidad_base'];
$nombre = $_POST['nombre'];
$multiplicador = $_POST['multiplicador'];

$nombre_unidad_base = clean_string($nombre_unidad_base);
$nombre = clean_string($nombre);
$multiplicador = clean_string($multiplicador);

$query = "SELECT COUNT(*) AS cont FROM unidad_multiplos WHERE UCASE(nombre)=UCASE('$nombre')";

$result = execResultSet($query);

if(!(trim($result[0]['cont'])=='0'))
{
	echo "Unidad ya existe";
	exit();
}

$r = execResultSet("SELECT id_unidad FROM unidad WHERE nombre ='$nombre_unidad_base'");
$id_unidad_base = $r[0]['id_unidad']; 




$query ="INSERT INTO `dbluxrestaurant`.`unidad_multiplos` (`id_multiplo`, `id_unidad`, `nombre`, `multiplicador`) VALUES (NULL, '$id_unidad_base', '$nombre', '$multiplicador')";
echo $query;
ejecutarSQLCommand($query);




?>