<?php
include ("functions.php");
$id = $_POST['id'];
$reuslt = execResultSet("SELECT * FROM tbusuarios WHERE iduser=$id");
echo $reuslt[0]['nombre'].":".$reuslt[0]['rol'].":".$reuslt[0]['user'].":".$reuslt[0]['pass'];

?>