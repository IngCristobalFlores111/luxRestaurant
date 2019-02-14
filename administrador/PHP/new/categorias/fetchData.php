<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "categorias":
            $query ="SELECT tbplatillos_categorias.nombre, tbplatillos_categorias.idCategoria AS id,COUNT(tbplatillos.idCategoria) AS cantidad FROM `tbplatillos_categorias` LEFT JOIN tbplatillos ON tbplatillos.idCategoria = tbplatillos_categorias.idCategoria GROUP BY tbplatillos_categorias.idCategoria";
            $results =$sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "unidades":
            $query="SELECT tbunidades.idUnidad AS id, tbunidades.nombre,COUNT(tbinsumos.idInsumo) AS cantidad FROM `tbunidades` LEFT JOIN tbinsumos ON tbinsumos.idUnidad = tbunidades.idUnidad GROUP BY tbinsumos.idUnidad";
            $results1 =$sql->executeQuery($query);
            $query ="SELECT tbfactura_unidades.idUnidad AS id,tbfactura_unidades.nombre,COUNT(tbfactura_productos.idProducto) AS cantidad FROM `tbfactura_unidades` LEFT JOIN tbfactura_productos ON tbfactura_productos.idUnidad = tbfactura_unidades.idUnidad GROUP BY tbfactura_unidades.idUnidad";
            $results2 = $sql->executeQuery($query);
            $output = array("u1"=>$results1,"u2"=>$results2);
            print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
            case "gastosCategorias":
           $query="SELECT idGastoCategoria AS id,nombre FROM `tbgastos_categorias`";
            $results =$sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

            break;


    }
}


?>