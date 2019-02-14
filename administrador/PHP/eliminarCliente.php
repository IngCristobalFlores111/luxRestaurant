<?php
include ("functions.php");
$idCliente = $_POST['idCliente'];
$query ="DELETE FROM tbclientes WHERE idcliente=$idCliente";
ejecutarSQLCommand($query);
$query = "DELETE FROM tbfacturas WHERE idcliente=$idCliente";


?>