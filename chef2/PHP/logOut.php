<?php
include("functions.php");
$sql = createMysqliConnection();

session_start();
$idUsr = $_SESSION['usr']['idusr'];
$query="UPDATE `tbusuarios_sesiones` SET `fecha_fin` = NOW() WHERE `idUsr` = ? AND `fecha_fin` is NULL";
$sql->execQueryBinders($query,array("i",$idUsr));
$query="UPDATE `tbusuarios` SET `authtoken` = '' , `timestamp` =NULL WHERE `iduser` =?";
$sql->execQueryBinders($query,array("i",$idUsr));

unset($_SESSION['usr']);

?>