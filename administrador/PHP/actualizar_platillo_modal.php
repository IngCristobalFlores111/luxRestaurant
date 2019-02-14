<?php
include("functions.php");

$idplatillo = $_POST['id'];
$nombre = $_POST['nombre'];
$precio = $_POST['precio'];
$costo = $_POST['costo'];
$categoria = $_POST['categoria'];

$nombre = str_replace("'",'"',$nombre);
$nombre  = htmlspecialchars($nombre,ENT_COMPAT);

$precio = str_replace("'",'"',$precio);
$precio  = htmlspecialchars($precio,ENT_COMPAT);

$costo = str_replace("'",'"',$costo);
$costo  = htmlspecialchars($costo,ENT_COMPAT);

$categoria= str_replace("'",'"',$categoria);
$categoria = htmlspecialchars($categoria,ENT_COMPAT);



$query = "UPDATE tbplatillos SET nombre='$nombre',precio='$precio',costo='$costo' , categoria ='$categoria' WHERE idplatillo=$idplatillo";
ejecutarSQLCommand($query);





?>