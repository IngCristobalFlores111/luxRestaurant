<?php

if(isset($_GET['accion'])){
    include_once("../../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "init":
            $query1 ="SELECT idUnidad AS id,nombre FROM `tbfactura_unidades`";
            $query2="SELECT tbfactura_productos.codigo,tbfactura_productos.idProducto AS id,tbfactura_productos.nombre,tbfactura_productos.descripcion,tbfactura_productos.precio,tbfactura_productos.idUnidad, tbfactura_unidades.nombre AS unidad FROM `tbfactura_productos` INNER JOIN tbfactura_unidades ON tbfactura_unidades.idUnidad = tbfactura_productos.idUnidad";
            echo $sql->multi_query(array($query1,$query2));

            break;
    }

}