<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "categorias":
            $query ="SELECT idCategoria AS id, nombre FROM `tbplatillos_categorias`";
           $results =  $sql->executeQuery($query);
           print_r(json_encode($results,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "topProductosInit":
            $query ="SELECT tbplatillos_categorias.idCategoria,tbplatillos_categorias.nombre AS categoria,tbplatillos.nombre,SUM(tbventaplatillos.cantidad) AS cantidad,SUM(tbventaplatillos.total) AS total FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo INNER JOIN tbplatillos_categorias ON tbplatillos_categorias.idCategoria = tbplatillos.idCategoria GROUP BY tbventaplatillos.idplatillo ORDER BY cantidad DESC";
            $results =  $sql->executeQuery($query);
            $query="SELECT tbplatillos.nombre AS platillo,SUM(tbventaplatillos.total) AS total,SUM(tbventaplatillos.cantidad) AS cantidad_vendido,SUM(tbventaplatillos.costo) AS gastos,SUM(ganancia) AS ganancia FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo GROUP BY tbventaplatillos.idplatillo ORDER BY cantidad_vendido DESC";
            $topMas = $sql->executeQuery($query);
            $query="SELECT tbplatillos.nombre AS platillo,SUM(tbventaplatillos.total) AS total,SUM(tbventaplatillos.cantidad) AS cantidad_vendido,SUM(tbventaplatillos.costo) AS gastos,SUM(ganancia) AS ganancia FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo GROUP BY tbventaplatillos.idplatillo ORDER BY cantidad_vendido ASC";
            $topMenos= $sql->executeQuery($query);
            
            $output=array("topCategorias"=>$results,"topMas"=>$topMas,"topMenos"=>$topMenos);
            print_r(json_encode($output,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

            break;
            case "buscarProducto":
            $q = $sql->filter_input($_GET['q']);
            $query="SELECT tbplatillos.nombre,tbplatillos.precio,SUM(tbventaplatillos.cantidad) AS cantidad,SUM(tbventaplatillos.total) AS total,SUM(tbventaplatillos.costo) AS gastos, SUM(tbventaplatillos.ganancia) AS ganancias FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo WHERE MATCH(tbplatillos.nombre,tbplatillos.descripcion) AGAINST ('*".$q."*' IN BOOLEAN MODE) GROUP BY tbventaplatillos.idplatillo";
            $platillo= $sql->executeQuery($query);
            print_r(json_encode($platillo,JSON_NUMERIC_CHECK|JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            
            break;


    }
}

?>