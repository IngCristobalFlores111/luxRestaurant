<?php
include ("functions.php");
$id= $_POST['id'];
$query = "SELECT nombre FROM tbplatillos WHERE idplatillo=$id";
$res = execResultSet($query);
echo $res[0]['nombre'];

?>