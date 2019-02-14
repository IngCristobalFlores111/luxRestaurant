<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "obtenerAlmacenesProveedores":
        $query = "SELECT `idAlmacen` AS id,nombre FROM tbalmacen";
        $almacenes = $sql->executeQuery($query);
        $query="SELECT `idProveedor` AS id, nombre,RFC FROM `tbproveedores`";
        $proveedores = $sql->executeQuery($query);
        $respuesta = array("almacenes"=>$almacenes,"proveedores"=>$proveedores);
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
        
        

        break;
        case "obtenerAlmacenes":
            $query="SELECT tbalmacen.idAlmacen AS id, tbalmacen.nombre,COUNT(tbalmacen_insumos_actual.idInsumo) AS cantidad FROM `tbalmacen` LEFT JOIN tbalmacen_insumos_actual ON tbalmacen_insumos_actual.idAlmacen = tbalmacen.idAlmacen GROUP BY tbalmacen.idAlmacen";
            $result = $sql->executeQuery($query);
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

            break;
        case "obtenerProveedores":
            $query ="SELECT tbproveedores.idProveedor AS id,tbproveedores.nombre,tbproveedores.RFC,tbproveedores.domicilio,tbproveedores.email,COUNT(tbinsumo_proveedores.idInsumo) AS cantidad FROM `tbproveedores` LEFT JOIN tbinsumo_proveedores ON tbinsumo_proveedores.idProveedor = tbproveedores.idProveedor GROUP BY tbproveedores.idProveedor";
            $result = $sql->executeQuery($query);
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "buscarInsumos":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $q = $request->q;
            $query ="SELECT tbinsumos.costo,tbinsumos.idInsumo AS id,tbinsumos.nombre,tbunidades.nombre AS unidad,tbinsumos.costo FROM `tbinsumos` INNER JOIN tbunidades on tbunidades.idUnidad = tbinsumos.idUnidad WHERE MATCH(tbinsumos.nombre) AGAINST ('*".$q."*' IN BOOLEAN MODE)";
            $result = $sql->executeQuery($query);
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "proveedoresInsumo":
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $query ="SELECT tbproveedores.idProveedor AS id,tbproveedores.nombre FROM `tbinsumo_proveedores`
INNER JOIN tbproveedores ON tbproveedores.idProveedor = tbinsumo_proveedores.idProveedor
WHERE tbinsumo_proveedores.idInsumo = ?";
           $result =  $sql->get_bind_results($query,array("i",$request->idInsumo));
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "almacenesBasic":
            $query ="SELECT idAlmacen AS id, nombre FROM `tbalmacen`";
            $result = $sql->executeQuery($query);
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "almacenInsumos":
            $query="SELECT tbinsumos.idInsumo,tbinsumos.costo AS costo_individual,tbalmacen_insumos_actual.idAlmacen,tbalmacen_insumos_actual.idInsumo,tbalmacen_insumos_actual.idProveedor,tbalmacen_insumos_actual.idAlmacenInsumosActual AS id,tbinsumos.nombre,tbproveedores.nombre AS proveedor,tbalmacen.nombre AS almacen,tbalmacen_insumos_actual.cantidad,(tbinsumos.costo*tbalmacen_insumos_actual.cantidad) AS costo FROM `tbalmacen_insumos_actual` INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbalmacen_insumos_actual.idInsumo INNER JOIN tbalmacen ON tbalmacen.idAlmacen = tbalmacen_insumos_actual.idAlmacen INNER JOIN tbproveedores ON tbproveedores.idProveedor = tbalmacen_insumos_actual.idProveedor";
            $result = $sql->executeQuery($query);
            print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "historialInsumo":
             $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
$query="SELECT tbusuarios.nombre AS usuario,tbalmacen_insumos.razon,tbinsumos.nombre,tbinsumos.costo*tbalmacen_insumos.cantidad AS costo,tbalmacen_insumos.cantidad,tbalmacen_insumos.fecha,tbalmacen.nombre AS almacen FROM `tbalmacen_insumos` INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbalmacen_insumos.idInsumo INNER JOIN tbalmacen ON tbalmacen.idAlmacen = tbalmacen_insumos.idAlmacen
LEFT JOIN tbusuarios
ON tbusuarios.iduser = tbalmacen_insumos.idUsrMod
WHERE tbalmacen_insumos.fecha BETWEEN ? AND ?  AND tbalmacen_insumos.idInsumo = ?";  
            $results = $sql->get_bind_results($query,array("ssi",$request->fechaInicio,$request->fechaFin,$request->idInsumo));
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));


            break;
            case "buscarPlatillo":
             $q = $_GET['q'];
            $sql->filter_input($q); 
             $query = "SELECT `idplatillo` AS id,nombre,`descripcion`,`precio` FROM tbplatillos WHERE MATCH(nombre) AGAINST('*$q*' IN BOOLEAN MODE)             ";
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            
            break;
            case "obtenerInventarioUnidad":

            $query="SELECT actual,idInventarioUnidad AS id,tbalmacen.idAlmacen,tbalmacen.nombre AS almacen,tbproveedores.idProveedor, tbproveedores.nombre AS proveedor, tbinventario_unidad.cantidad,tbplatillos.idplatillo AS idPlatillo,tbplatillos.nombre AS nombre FROM `tbinventario_unidad` INNER JOIN tbalmacen ON tbalmacen.idAlmacen = tbinventario_unidad.`idAlmacen` INNER JOIN tbproveedores ON tbproveedores.idProveedor = tbinventario_unidad.`idProveedor` INNER JOIN tbplatillos ON tbplatillos.`idPlatillo`=tbinventario_unidad.`idPlatillo`";
            
            $results = $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            

            break;
             case "insumoDetalleHistorial":
            $query="SELECT tbproveedores.nombre AS proveedor,tbalmacen_insumos.fecha,tbusuarios.nombre AS usuario,tbalmacen_insumos.razon,tbinsumos.nombre,tbinsumos.costo,tbalmacen_insumos.cantidad,tbalmacen_insumos.fecha,tbalmacen.nombre AS almacen FROM `tbalmacen_insumos` INNER JOIN tbinsumos ON tbinsumos.idInsumo = tbalmacen_insumos.idInsumo INNER JOIN tbalmacen ON tbalmacen.idAlmacen = tbalmacen_insumos.idAlmacen
            LEFT JOIN tbusuarios
            ON tbusuarios.iduser = tbalmacen_insumos.idUsrMod
            
            INNER JOIN tbproveedores ON tbproveedores.idProveedor=tbalmacen_insumos.idProveedor
            WHERE  tbalmacen_insumos.idInsumo = ?";
           $results =  $sql->get_bind_results($query,array("i",$_GET['id']));
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            

            break;
            


    }

}



?>