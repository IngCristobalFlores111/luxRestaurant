<?php
include ("functions.php");
$nombre = $_POST['nombre'];
$RFC = $_POST['RFC'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$id_proveedor = $_POST['id_provedor'];

$nombre = str_replace("'",'"',$nombre);
$nombre = htmlspecialchars($nombre,ENT_COMPAT);
$RFC = str_replace("'",'"',$RFC);
$RFC = htmlspecialchars($RFC,ENT_COMPAT);
$telefono = str_replace("'",'"',$telefono);
$telefono = htmlspecialchars($telefono,ENT_COMPAT);
$email = str_replace("'",'"',$email);
$email = htmlspecialchars($email,ENT_COMPAT);
$query = '';
$query = "UPDATE proveedor SET nombre='$nombre', RFC='$RFC',telefono='$telefono',email ='$email' WHERE id_proveedor='$id_proveedor'";

if(isset( $_POST['agregar'] ))
{
	$query = "INSERT INTO `dbluxrestaurant`.`proveedor` (`id_proveedor`, `nombre`, `RFC`, `telefono`, `email`) VALUES (NULL, '$nombre', '$RFC', '$telefono', '$email');";
}

ejecutarSQLCommand($query);




?>