<?php
/**
 * @author MultiFacturas.com
 * @copyright 2014
 *
 * EL array $datos contiene la información de la factura a generar
 *
 * GENERA EL XML Y LO TIMBRA EN BASE A LA INFORMACION DEL ARREGLO $datos
 *
 * VALIDADOR DE ESTRUCTURA DEL XML
 * https://www.consulta.sat.gob.mx/sicofi_web/moduloECFD_plus/ValidadorCFDI/Validador%20cfdi.html
 *
 * PARA NOTA DE CREDITO SOLO CAMBIA EL PARAMETRO $datos['factura']['tipocomprobante'] a egreso
 *
 * EN ALGUNOS EJEMPLOS SON ILUSTRATIVOS DE LOS PARAMETROS, ASI QUE LOS MONTOS NO CONCORDARAN
 *
 */


error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas_PHP7.php";
include_once "../PHP/new/functions.php";
$sql = createMysqliConnection();

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$cliente = $request->cliente;
$conceptos = $request->conceptos;



$query ="SELECT tbfactura_emisor.telefono,tbfactura_emisor.pais,tbfactura_emisor.nombre,tbfactura_emisor.RFC,tbfactura_emisor.regimen,tbfactura_emisor.email,tbfactura_emisor.direccion,tbfactura_emisor.noInt,tbfactura_emisor.noExt,tbfactura_emisor.colonia,muni.nombre AS municipio,estado.nombre AS estado,tbfactura_emisor.codigoPostal FROM `tbfactura_emisor` INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbfactura_emisor.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas = tbfactura_emisor.estado WHERE tbfactura_emisor.idEmisor = ?";
$emisor = $sql->get_bind_results($query,array("i",$request->idEmisor));
$emisor =$emisor[0];

$query ="SELECT * FROM `tbfactura_config` WHERE `idConfig` = ?";
$config = $sql->get_bind_results($query,array("i",$request->idConfig));
$config = $config[0];

$query ="SELECT tbfactura_lugares_expedicion.pais,tbfactura_lugares_expedicion.direccion,tbfactura_lugares_expedicion.noInt,tbfactura_lugares_expedicion.noExt,tbfactura_lugares_expedicion.noInt,tbfactura_lugares_expedicion.colonia,muni.nombre AS municipio,estado.nombre AS estado,tbfactura_lugares_expedicion.codigoPostal FROM `tbfactura_lugares_expedicion` INNER JOIN tbentidadesfederativas muni ON muni.identidadesFederativas = tbfactura_lugares_expedicion.municipio INNER JOIN tbentidadesfederativas estado ON estado.identidadesFederativas =tbfactura_lugares_expedicion.estado
WHERE tbfactura_lugares_expedicion.idLugarExpedicion = ?";
$lugarExpedicion = $sql->get_bind_results($query,array("i",$request->idLugarExpedicion));
$lugarExpedicion = $lugarExpedicion[0];






$datos['PAC']['usuario'] = $config['pac_usuario'];
$datos['PAC']['pass'] = $config['pac_password'];
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/'.$config['archivo_cer'];
$datos['conf']['key'] = 'pruebas/'.$config['archivo_key'];
$datos['conf']['pass'] = $config['password'];


$folio = $request->serie.$request->folio;
//RUTA DONDE ALMACENARA EL CFDI
$datos['cfdi']='timbrados/cfdi_'.$folio.".xml";

$datos['php_openssl']='SI';




$datos['factura']['serie'] = $request->serie; //opcional
$datos['factura']['folio'] = $request->folio; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] = $request->idMetodoPago->id; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = $request->idFormaPago->nombre;  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = ($request->tipo_comprobante=='1')?'ingreso':'egreso'; //ingreso, egreso
$datos['factura']['moneda'] = $config['moneda']; // MXN USD EUR
$datos['factura']['tipocambio'] = $config['tipoCambio']; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = $lugarExpedicion['municipio'].",".$lugarExpedicion['estado'];
$datos['factura']['NumCtaPago'] = $request->numeroCtaPago;

$datos['factura']['RegimenFiscal'] = $emisor['regimen'];

$datos['emisor']['rfc'] = $emisor['RFC']; //RFC DE PRUEBA
$datos['emisor']['nombre'] = $emisor['nombre'];  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = $emisor['direccion'];
$datos['emisor']['DomicilioFiscal']['noExterior'] = $emisor['noExt'];
$datos['emisor']['DomicilioFiscal']['noInterior'] = $emisor['noInt'];
$datos['emisor']['DomicilioFiscal']['colonia'] = $emisor['colonia'];
$datos['emisor']['DomicilioFiscal']['localidad'] = $emisor['municipio'];
$datos['emisor']['DomicilioFiscal']['municipio'] = $emisor['municipio'];
$datos['emisor']['DomicilioFiscal']['estado'] = $emisor['estado'];
$datos['emisor']['DomicilioFiscal']['pais'] = $emisor['pais'];
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] =$emisor['codigoPostal']; //5 digitos

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = $lugarExpedicion['direccion'];
$datos['emisor']['ExpedidoEn']['noExterior'] = $lugarExpedicion['noExt'];
$datos['emisor']['ExpedidoEn']['noInterior'] = $lugarExpedicion['noInt'];
$datos['emisor']['ExpedidoEn']['colonia'] = $lugarExpedicion['colonia'];
$datos['emisor']['ExpedidoEn']['localidad'] = $lugarExpedicion['municipio'];
$datos['emisor']['ExpedidoEn']['municipio'] = $lugarExpedicion['municipio'];
$datos['emisor']['ExpedidoEn']['estado'] = $lugarExpedicion['estado'];
$datos['emisor']['ExpedidoEn']['pais'] = $lugarExpedicion['pais'];
$datos['emisor']['ExpedidoEn']['CodigoPostal'] =  $lugarExpedicion['codigoPostal'];

// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = $cliente->RFC;
$datos['receptor']['nombre'] = $cliente->nombre;
//opcional
$datos['receptor']['Domicilio']['calle'] = $cliente->direccion;
$datos['receptor']['Domicilio']['noExterior'] = $cliente->noExt;
$datos['receptor']['Domicilio']['noInterior'] = $cliente->noInt;
$datos['receptor']['Domicilio']['colonia'] = $cliente->colonia;
$datos['receptor']['Domicilio']['localidad'] = $cliente->municipio;
$datos['receptor']['Domicilio']['municipio'] = $cliente->municipio;
$datos['receptor']['Domicilio']['estado'] = $cliente->estado;
$datos['receptor']['Domicilio']['pais'] = 'MEXICO';
$datos['receptor']['Domicilio']['CodigoPostal'] = $cliente->codigoPostal;

//AGREGAR 10 CONCEPTOS DE PRUEBA
$subTotal = 0;
foreach ($conceptos as $concepto) {
    $c = array();
    $importe = $concepto->importe;
    $c['unidad'] = $concepto->unidad;
    $c['cantidad'] = $concepto->cantidad;
    $c['ID'] = $concepto->codigo;
    $c['descripcion'] = $concepto->descripcion;
    $c['valorunitario'] = $concepto->precio;
    $c['importe'] = $importe;
    $datos['conceptos'][] = $c;
    $subTotal+=(float)$importe;
}
$iva = $subTotal*0.16;
$total = ($subTotal-$request->descuento) + $iva;
$datos['factura']['subtotal'] = $subTotal; // sin impuestos
$datos['factura']['descuento'] = $request->descuento; // descuento sin impuestos
$datos['factura']['total'] = $total; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = $iva; // iva de los productos facturados
$datos['impuestos']['translados'][0] = $translado1;



$res= cfdi_generar_xml($datos);
if($res['codigo_mf_numero']==0)
{ // factura timbrada
    // generar pdf
    $xml_path ='timbrados/cfdi_'.$folio.".xml";
    $png_path ='timbrados/cfdi_'.$folio.".png";
    $pdf_path = 'timbrados/cfdi_'.$folio.'.pdf';
    include_once("../pdfWriter/FacturaPDFGen.php");
    GeneratePDF_Pedimentos("configFiles/logo.jpg",$xml_path,$png_path,$pdf_path,$emisor['email'],$emisor['telefono'],$cliente->email,$cliente->telefono,null);



    $xml = file_get_contents($xml_path);


    $query ="INSERT INTO `tbfactura`(`idEmisor`, `idConfig`, `idLugarExpedicion`,
             `fecha`, `xml`, `total`, `idCliente`, `idMetodoPago`, `idFormaPago`,
             `numeroCtaPago`, `tipoComprobante`,estatus)
             VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
    $hoy =  date('Y-m-d H:i:s',time()-120);
    $sql->execQueryBinders($query,array("iiissdiiisii",$request->idEmisor,
        $request->idConfig,$request->idLugarExpedicion,
        $hoy,$xml,$total,$cliente->id,$request->idMetodoPago->id,$request->idFormaPago->id,$request->numeroCtaPago,$request->tipo_comprobante,1));
    $query ="INSERT INTO `tbfolios_factura`(`idFolio`, `idSerie`, `idFactura`) VALUES (?,?,?);";
    $idFactura = $sql->getLastId();
    $sql->execQueryBinders($query,array("iii",$request->folio,$request->idSerie,$idFactura));
    $errores = $sql->getErrorLog();
    $respuesta = array();
    if(count($errores)>0){
        $respuesta['exito'] = false;
        $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
    }else{
        $respuesta['exito'] = true;
        $respuesta['idFactura'] =$idFactura;
    }
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

}else{
    $respuesta = array();
    $respuesta['exito'] = false;
    $respuesta['errores'] = $res['codigo_mf_texto'];
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


}

?>