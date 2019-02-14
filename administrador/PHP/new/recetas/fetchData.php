<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();

    switch($_GET['accion']){
        case "obtenerInsumosPlatillo":
            $idPlatillo = $_GET['idPlatillo'];
            $query ="SELECT tbinsumos.costo AS costo_unitario,tbinsumos.idInsumo AS id,tbinsumos.nombre,tbunidades.nombre AS unidad,ROUND(tbinsumos.costo*tbinsumos_platillo.cantidad,3) AS costo,tbinsumos_platillo.cantidad FROM `tbinsumos_platillo` INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbinsumos_platillo.idInsumo INNER JOIN tbunidades ON tbunidades.idUnidad = tbinsumos.idUnidad WHERE tbinsumos_platillo.idplatillo = ? ;";
            $results =  $sql->get_bind_results($query,array("i",$idPlatillo));
           print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "buscarInsumos":
            $q = $_GET['q'];
            $query ="SELECT tbinsumos.idInsumo AS id,tbinsumos.nombre,tbinsumos.descripcion,tbinsumos.costo AS costo_unitario,tbunidades.nombre AS unidad FROM `tbinsumos` INNER JOIN tbunidades ON tbunidades.idUnidad = tbinsumos.idUnidad WHERE MATCH(tbinsumos.nombre) AGAINST ('*".$q."*' IN BOOLEAN MODE)";
            $results= $sql->executeQuery($query);
           $query="SELECT `idInsumoCompuesto` AS id,nombre,descripcion FROM `tbinsumos_compuestos` WHERE MATCH(nombre,descripcion) AGAINST('*".$q."*' IN BOOLEAN MODE)";
            $results2= $sql->executeQuery($query);
           $output = array_merge($results,$results2);
           
        print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

            break;
        case "almacenesInsumo":
                        $id = $_GET['id'];

            $query ="SELECT tbalmacen.nombre,tbalmacen.idAlmacen AS id FROM `tbalmacen_insumos_actual` INNER JOIN tbalmacen ON tbalmacen.idAlmacen = tbalmacen_insumos_actual.idAlmacen
WHERE tbalmacen_insumos_actual.idInsumo = ?
GROUP BY tbalmacen_insumos_actual.idAlmacen";
           $results= $sql->get_bind_results($query,array("i",$id));
           print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

            break;
        case "insumoProveedores":
            $query ="SELECT tbproveedores.nombre,tbinsumo_proveedores.idProveedor AS id FROM `tbinsumo_proveedores` INNER JOIN tbproveedores ON tbproveedores.idProveedor = tbinsumo_proveedores.idProveedor WHERE tbinsumo_proveedores.idInsumo = ?";
            $id = $_GET['id'];
            $results = $sql->get_bind_results($query,array("i",$id));
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


            break;
        case "insumosProveedorPlatillo":
            $query ="SELECT idProveedor,idInsumo,idAlmacen FROM `tb_platillo_insumos_prov_actual` WHERE idPlatillo = ?";
            $id = $_GET['id'];
            $results = $sql->get_bind_results($query,array("i",$id));
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


            break;

    }

}


?>