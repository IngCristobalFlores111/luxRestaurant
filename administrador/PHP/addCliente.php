<?php
header('Content-Type: text/html; charset=UTF-8'); 

include ("functions.php");




$nombre = $_POST['nombre'];
$rfc = $_POST['rfc'];
$calle = $_POST['calle'];
$NoExterior = $_POST['NoExterior'];
$NoInterior = $_POST['NoInterior'];
$colonia = $_POST['colonia'];
$codigoPostal = $_POST['codigoPostal'];
$localidad = $_POST['localidad'];
$estado = $_POST['estado'];
$municipio = $_POST['municipio'];
// quitar ascentos

/*
$nombre = strtoupper($nombre);
$rfc = strtoupper($rfc);
$calle = strtoupper($calle);
$NoExterior = strtoupper($NoExterior);
$NoInterior = strtoupper($NoInterior);
$colonia = strtoupper($colonia);
$estado = strtoupper($estado);
$municipio = strtoupper($municipio);
*/




$query ="INSERT INTO `dbrestaurante`.`tbclientes` (`rfc`, `nombre`, `calle`, `noExterior`, `noInterior`, `colonia`, `localidad`, `municipio`, `estado`, `pais`, `codigoPostal`) VALUES ('$rfc', '$nombre', '$calle', '$NoExterior', '$NoInterior', '$colonia', '$localidad', '$municipio', '$estado', 'MEXICO', '$codigoPostal');";

ejecutarSQLCommand($query);
?>