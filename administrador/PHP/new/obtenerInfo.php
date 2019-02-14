<?php
include_once("functions.php");
$sql = createMysqliConnection();


if(isset($_GET['accion'])){
    switch($_GET['accion']){
        case "unidades":
            $query ="SELECT * FROM `tbunidades`";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
            break;
        case "insumos":
            $query ="SELECT tbunidades.idUnidad ,tbinsumos.idInsumo AS id,tbinsumos.nombre,tbinsumos.descripcion,tbinsumos.costo, tbunidades.nombre AS unidad FROM `tbinsumos` INNER JOIN tbunidades ON tbunidades.idUnidad = tbinsumos.idUnidad";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

            break;
        case "proveedores":
            $query ="SELECT `idProveedor` AS id,`nombre`,RFC FROM `tbproveedores`";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
            break;
        case "proveedoresInsumo":
            $idInsumo = $_GET['idInsumo'];
            $query = "SELECT tbproveedores.idProveedor AS id,tbproveedores.nombre,tbproveedores.RFC FROM `tbinsumo_proveedores` INNER JOIN tbproveedores ON tbproveedores.idProveedor=tbinsumo_proveedores.idProveedor WHERE tbinsumo_proveedores.idInsumo = ?";
           $results =  $sql->get_bind_results($query,array("i",$idInsumo));
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

            break;
   }


}


?>