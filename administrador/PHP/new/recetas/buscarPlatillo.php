<?php
include_once("../functions.php");
$sql = createMysqliConnection();
$q = $_GET['q'];
$sql->filter_input($q);
if($q==''){
    $query="SELECT idplatillo AS id,nombre,precio,descripcion,imagepath AS img FROM `tbplatillos` LIMIT 10";

}else{
    $query="SELECT idplatillo AS id,nombre,precio,descripcion,imagepath AS img FROM `tbplatillos` WHERE MATCH(nombre) AGAINST ('*".$q."*' IN BOOLEAN MODE)";
}
$results = $sql->executeQuery($query);
print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


?>