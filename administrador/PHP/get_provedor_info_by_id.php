<?php
include("functions.php");
$id = $_POST['id_prov'];

echo getJSONResultSQL("SELECT * FROM `proveedor` WHERE `id_proveedor`=$id");




?>