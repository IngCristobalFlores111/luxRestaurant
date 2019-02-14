<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "platillos":
            $query ="SELECT tbplatillos.preparado,tbplatillos.idplatillo AS id,tbplatillos.imagepath AS img,tbplatillos.nombre,tbplatillos.descripcion,tbplatillos.costo,tbplatillos.precio,tbplatillos.activado,tbplatillos_categorias.nombre AS categoria,tbplatillos_categorias.idCategoria FROM tbplatillos INNER JOIN tbplatillos_categorias ON tbplatillos_categorias.idCategoria = tbplatillos.idCategoria ORDER BY nombre";
          $results =   $sql->executeQuery($query);
          print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "categorias":
            $query ="SELECT `idCategoria` AS id, nombre FROM `tbplatillos_categorias`";
            $results =   $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "init":
            $query1 ="SELECT tbplatillos.preparado,tbplatillos.idplatillo AS id,tbplatillos.imagepath AS img,tbplatillos.nombre,tbplatillos.descripcion,tbplatillos.costo,tbplatillos.precio,tbplatillos.activado,tbplatillos_categorias.nombre AS categoria,tbplatillos_categorias.idCategoria FROM tbplatillos INNER JOIN tbplatillos_categorias ON tbplatillos_categorias.idCategoria = tbplatillos.idCategoria ORDER BY nombre";
            $query2 ="SELECT `idCategoria` AS id, nombre FROM `tbplatillos_categorias`";
            $results = $sql->multi_query(array($query1,$query2));
            echo $results;
            break;
        case "costoPlatillo":
            $query ="SELECT ROUND(SUM(tbinsumos_platillo.cantidad * tbinsumos.costo),3) AS costo FROM `tbinsumos_platillo` INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbinsumos_platillo.idInsumo WHERE tbinsumos_platillo.idplatillo = ?";
            $results = $sql->get_bind_results($query,array("i",$_GET['idPlatillo']));
            $results = $results[0];
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

            break;



    }
}

?>