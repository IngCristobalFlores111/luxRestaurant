<?php
include ("functions.php");

$idplatillo = $_POST['idplatillo'];

echo getJSONResultSQL("SELECT nombre,categoria,precio,costo FROM tbplatillos WHERE idplatillo='$idplatillo'");




?>