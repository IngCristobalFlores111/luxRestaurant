<?php
include ('functions.php');

$idCliente = $_POST['idCliente'];
$idCuenta = $_POST['idCuenta'];

$query ="INSERT INTO tbfacturas (`idcliente`,`idcuenta`) VALUES ($idCliente,$idCuenta) ON DUPLICATE KEY UPDATE idcuenta=idcuenta";
ejecutarSQLCommand($query);


?>