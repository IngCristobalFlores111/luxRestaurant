<?php

if(isset($_GET['accion'])){
    include_once("../../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "init":
            $query1 ="SELECT idFormaPago AS id,nombre FROM `tbfactura_formas_pago`";
            $query2="SELECT idMetodoPago AS id,nombre  FROM `tbfactura_metodos_apago`";
            $query3 ="SELECT idUnidad AS id,nombre FROM `tbfactura_unidades`";
            $query4 ="SELECT idSerie AS id,serie FROM `tbseries_factura`";
            $query5 = "SELECT idFolio + 1 AS folio,idSerie FROM `tbfolios_factura` ORDER BY idFolio DESC LIMIT 1";
            $query6 = "SELECT `idLugarExpedicion` AS id,muni.nombre AS municipio,estado.nombre AS estado FROM `tbfactura_lugares_expedicion` INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbfactura_lugares_expedicion.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas = tbfactura_lugares_expedicion.estado";
            $query7 ="SELECT CONCAT(pac_usuario,' ',archivo_key) AS nombre,idConfig AS id,moneda,tipoCambio FROM `tbfactura_config`";
            echo $sql->multi_query(array($query1,$query2,$query3,$query4,$query5,$query6,$query7));
            break;
        case "buscarCliente":
            $q = $_GET['q'];
            $q = $sql->filter_input($q);
            $query ="SELECT tbclientes.telefono,tbclientes.idCliente AS id,tbclientes.nombre,tbclientes.RFC,tbclientes.email,tbclientes.direccion,tbclientes.noInt,tbclientes.noExt,tbclientes.colonia,muni.nombre AS municipio,estado.nombre AS estado,tbclientes.codigoPostal FROM `tbclientes` INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbclientes.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas = tbclientes.estado WHERE MATCH(tbclientes.nombre,tbclientes.RFC) AGAINST('*".$q."*' IN BOOLEAN MODE)";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "buscarProducto":
            $q = $_GET['q'];
            $q = $sql->filter_input($q);
            $query ="SELECT tbfactura_productos.codigo,tbfactura_productos.idProducto AS id,tbfactura_productos.nombre,tbfactura_productos.descripcion,tbfactura_productos.precio,tbfactura_unidades.nombre AS unidad FROM `tbfactura_productos` INNER JOIN tbfactura_unidades ON tbfactura_unidades.idUnidad = tbfactura_productos.idUnidad WHERE MATCH(tbfactura_productos.nombre,tbfactura_productos.descripcion) AGAINST('*".$q."*' IN BOOLEAN MODE)";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
            break;

    }

}