<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
        switch($_GET['accion']){
            case "estados":
                $query ="SELECT identidadesFederativas AS id, nombre FROM `tbentidadesfederativas` WHERE idmapaJerarquias=2";
                $result = $sql->executeQuery($query);
                print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
                break;
            case "municipios":
                $idEstado = $_GET['idEstado'];
                $query="SELECT tbestados_tiene_hijos.idmunicipioDistrito AS id,tbentidadesfederativas.nombre FROM `tbestados_tiene_hijos` INNER JOIN tbentidadesfederativas ON tbentidadesfederativas.identidadesFederativas = tbestados_tiene_hijos.idmunicipioDistrito WHERE tbestados_tiene_hijos.idestado = ? AND tbentidadesfederativas.idmapaJerarquias=3";
                $result = $sql->get_bind_results($query,array("i",$idEstado));
                print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
                break;
            case "clientes":
                $query="SELECT tbclientes.idCliente AS id,tbclientes.nombre,tbclientes.RFC,tbclientes.email, CONCAT(tbclientes.direccion,' ',tbclientes.noExt ,' ',tbclientes.noInt,' cp.',tbclientes.codigoPostal,' ',muni.nombre,' ',estado.nombre) AS domicilio,direccion,COUNT(tbfactura.idFactura) AS cantidad,direccion,noInt,noExt,colonia,municipio,estado,codigoPostal FROM `tbclientes` INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbclientes.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas = tbclientes.estado LEFT JOIN tbfactura ON tbfactura.idCliente = tbclientes.idCliente GROUP BY tbclientes.idCliente";
                $result = $sql->executeQuery($query);
                print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
               break;
            case "facturas":
                $query ="SELECT tbfactura.idFactura AS id,CONCAT(tbseries_factura.serie,'-',tbfolios_factura.idFolio) AS folio,tbclientes.nombre AS cliente,tbclientes.RFC,tbfactura.fecha,tbfactura.total FROM `tbfactura` INNER JOIN tbfolios_factura ON tbfolios_factura.idFactura = tbfactura.idFactura INNER JOIN tbseries_factura ON tbseries_factura.idSerie = tbfolios_factura.idSerie INNER JOIN tbclientes ON tbclientes.idCliente = tbfactura.idCliente ORDER BY tbfactura.fecha DESC";
                $result = $sql->executeQuery($query);
                print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
                break;
            case "configs":
                $query ="SELECT * FROM `tbfactura_config`";
                $result = $sql->executeQuery($query);
                print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
                break;
            case "facturaDetalle":
                $query ="SELECT tbfactura.estatus,tbfactura.idCliente,CONCAT(tbseries_factura.serie,tbfolios_factura.idFolio) AS folio,tbfactura.idFactura AS id,tbfactura.fecha,tbfactura.total,tbfactura.tipoComprobante,tbfactura_metodos_apago.nombre AS metodoPago,tbfactura_formas_pago.nombre AS formaPago,tbfactura.numeroCtaPago,CONCAT(muni.nombre,',',estado.nombre) AS lugar FROM `tbfactura` INNER JOIN tbfactura_formas_pago ON tbfactura_formas_pago.idFormaPago = tbfactura.idFormaPago INNER JOIN tbfactura_metodos_apago ON tbfactura_metodos_apago.idMetodoPago =tbfactura.idMetodoPago INNER JOIN tbfactura_lugares_expedicion ON tbfactura_lugares_expedicion.idLugarExpedicion = tbfactura.idLugarExpedicion INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbfactura_lugares_expedicion.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas = tbfactura_lugares_expedicion.estado
INNER JOIN tbfolios_factura  ON tbfolios_factura.idFactura = tbfactura.idFactura
INNER JOIN tbseries_factura  ON tbseries_factura.idSerie = tbfolios_factura.idSerie
WHERE tbfactura.idFactura = ?";
                $result = $sql->get_bind_results($query,array("i",$_GET['idFactura']));
                if(count($result)==0){
                    echo"0";
                    exit();
                }
                $result = $result[0];
                $query ="SELECT idCliente,tbclientes.nombre,RFC,email,telefono,direccion,noInt,noExt,colonia,muni.nombre AS municipio,estado.nombre AS estado,codigoPostal,pais FROM `tbclientes` INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbclientes.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas = tbclientes.estado WHERE tbclientes.idCliente = ?";
                $result2 = $sql->get_bind_results($query,array("i",$result['idCliente']));
                $result2 = $result2[0];

                include_once("../helper.php");
               $xml_factura =  OpenCompleteXMLFile("../../../Facturacion/timbrados/cfdi_".$result['folio'].".xml",false);
               $output = array("factura"=>$result,"cliente"=>$result2,"xml"=>$xml_factura);
               print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
                break;
            case "emisor":
                $query ="SELECT * FROM `tbfactura_emisor`";
                $result1 = $sql->executeQuery($query);
                $result1 = $result1[0];

                $query ="SELECT identidadesFederativas AS id,nombre FROM `tbentidadesfederativas` WHERE idmapaJerarquias = 2";
                $result2 = $sql->executeQuery($query);

                $output= array("emisor"=>$result1,"estados"=>$result2);
                print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

                break;
            case "lugaresExpedicion":
                $query ="SELECT * FROM `tbfactura_lugares_expedicion`";
                $result = $sql->executeQuery($query);
                print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

                break;
            case "contactos":
                $idCliente = $_GET['idCliente'];
                $query ="SELECT * FROM `tbfactura_clientes_contactos` WHERE idCliente = ?";
               $result =  $sql->get_bind_results($query,array("i",$idCliente));
               print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

                break;



        }

}
?>