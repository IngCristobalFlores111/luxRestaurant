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
//include_once "cfdi32_multifacturas_encoded.php";
date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////


$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/aaa010101aaa.cer.pem';
$datos['conf']['key'] = 'pruebas/aaa010101aaa.key.pem';
$datos['conf']['pass'] = '12345678a';



//RUTA DONDE ALMACENARA EL CFDI
$datos['cfdi']='timbrados/cfdi_ejemplo_factura_comercio_exterior.xml';
// OPCIONAL GUARDAR EL XML GENERADO ANTES DE TIMBRARLO
$datos['xml_debug']='timbrados/sin_timbrar_ejemplo_factura_comercio_exterior.xml';

//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = '100'; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha


$datos['factura']['metodo_pago'] = '02'; // EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = 'PAGO EN UNA SOLA EXHIBICION';  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'ingreso'; //ingreso, egreso
$datos['factura']['moneda'] = 'MXN'; // MXN USD EUR
$datos['factura']['tipocambio'] = '1.0000'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = 'MEX';
//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$datos['factura']['RegimenFiscal'] = 'REGIMEN GENERAL DE LEY PERSONAS MORALES';

$datos['emisor']['rfc'] = 'AAA010101AAA'; //RFC DE PRUEBA  
$datos['emisor']['nombre'] = 'ACCEM SERVICIOS EMPRESARIALES SC';  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = 'JUAREZ';
$datos['emisor']['DomicilioFiscal']['noExterior'] = '100';
$datos['emisor']['DomicilioFiscal']['noInterior'] = ''; //(opcional)
$datos['emisor']['DomicilioFiscal']['colonia'] = '0001';//'CENTRO';
$datos['emisor']['DomicilioFiscal']['localidad'] = '02';//MONTERREY';
$datos['emisor']['DomicilioFiscal']['municipio'] = '067';//Torreon'; // o delegacion
//https://es.wikipedia.org/wiki/ISO_3166-2:MX
$datos['emisor']['DomicilioFiscal']['estado'] = 'OAX';
//https://es.wikipedia.org/wiki/ISO_3166-2:MX
$datos['emisor']['DomicilioFiscal']['pais'] = 'MEX';
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = '68000'; // 5 digitos

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = 'HIDALGO';
$datos['emisor']['ExpedidoEn']['noExterior'] = '240';
$datos['emisor']['ExpedidoEn']['noInterior'] = ''; //(opcional)
$datos['emisor']['ExpedidoEn']['colonia'] = '0001';
$datos['emisor']['ExpedidoEn']['localidad'] = '02';
$datos['emisor']['ExpedidoEn']['municipio'] = '067';//'035'; // O DELEGACION
$datos['emisor']['ExpedidoEn']['estado'] = 'OAX';
$datos['emisor']['ExpedidoEn']['pais'] = 'MEX';
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = '68000'; // 5 digitos

// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = 'XEXX010101000';
$datos['receptor']['nombre'] = 'PUBLICO EN GENERAL';
//opcional
$datos['receptor']['Domicilio']['calle'] = 'PERIFERICO';
$datos['receptor']['Domicilio']['noExterior'] = '1024';
$datos['receptor']['Domicilio']['noInterior'] = 'B';
$datos['receptor']['Domicilio']['colonia'] = '3738';
$datos['receptor']['Domicilio']['localidad'] = 'CIUDAD DE MÉXICO';
$datos['receptor']['Domicilio']['municipio'] = 'ALVARO OBREGON';
$datos['receptor']['Domicilio']['estado'] = '';
$datos['receptor']['Domicilio']['pais'] = 'JPN';
$datos['receptor']['Domicilio']['CodigoPostal'] = '23010'; // 5 digitos

//AGREGAR 10 CONCEPTOS DE PRUEBA
for ($i = 1; $i < 2; $i++) {
    unset($concepto);
    $concepto['cantidad'] = 1;
    $concepto['unidad'] = 'PIEZA';
    $concepto['ID'] = "A-1215"; //ID, REF, CODIGO O SKU DEL PRODUCTO
//    $concepto['descripcion'] = "PRODUCTO PRUEBA > '$i'";
    $concepto['descripcion'] = "PRODUCTO PRUEBA $i";
    $concepto['valorunitario'] = '100.00'; // SIN IVA
    $concepto['importe'] = '100.00';

    $datos['conceptos'][] = $concepto;
}

$datos['factura']['subtotal'] = 100.00; // sin impuestos
$datos['factura']['total'] = 116.00; // total incluyendo impuestos



$translado1['impuesto'] = 'IVA';
$translado1['tasa'] = '16';
$translado1['importe'] = 16.00; // iva de los productos facturados
$datos['impuestos']['translados'][0] = $translado1;


//COMPLEMENTO COMERCIO EXTERIOR

$datos['comercio']['TipoOperacion']='2';
$datos['comercio']['ClaveDePedimento']='A1';    //opcional
$datos['comercio']['CertificadoOrigen']='0';    //opcional
//$datos['comercio']['NumCertificadoOrigen']='123456';    //opcional
$datos['comercio']['NumeroExportadorConfiable']='0';    //opcional
$datos['comercio']['Incoterm']='FOB';    //opcional
$datos['comercio']['Subdivision']='0';    //opcional
$datos['comercio']['Observaciones']='detalles';    //opcional
$datos['comercio']['TipoCambioUSD']='10.23';    //opcional
$datos['comercio']['TotalUSD']='116.00';    //opcional

//$datos['comercio']['Emisor']['Curp']='DESO801116HGTLRS08';    //opcional

$datos['comercio']['Receptor']['Curp']='HEUJ880222HOCTRR04';    //opcional
$datos['comercio']['Receptor']['NumRegIdTrib']='123456789';

$datos['comercio']['Destinatario']['NumRegIdTrib']='123456789';    //opcional
$datos['comercio']['Destinatario']['Rfc']='ESI920427886';    //opcional
$datos['comercio']['Destinatario']['Curp']='HIOK800212HOCTRR01';    //opcional
$datos['comercio']['Destinatario']['Nombre']='str1234';    //opcional
$datos['comercio']['Destinatario']['Domicilio']['Calle']='calle de pruebas';
$datos['comercio']['Destinatario']['Domicilio']['NumeroExterior']='1234';    //opcional
//$datos['comercio']['Destinatario']['Domicilio']['NumeroInterior']='str1234';    //opcional
$datos['comercio']['Destinatario']['Domicilio']['Colonia']='0995';    //opcional
$datos['comercio']['Destinatario']['Domicilio']['Localidad']='11';    //opcional
$datos['comercio']['Destinatario']['Domicilio']['Referencia']='str1234';    //opcional
$datos['comercio']['Destinatario']['Domicilio']['Municipio']='035';    //opcional
$datos['comercio']['Destinatario']['Domicilio']['Estado']='COA';
$datos['comercio']['Destinatario']['Domicilio']['Pais']='MEX';
$datos['comercio']['Destinatario']['Domicilio']['CodigoPostal']='27000';


$datos['comercio']['Mercancias'][0]['NoIdentificacion']='A-1215';
$datos['comercio']['Mercancias'][0]['FraccionArancelaria']='01011001';    //opcional
$datos['comercio']['Mercancias'][0]['CantidadAduana']='1.0';    //opcional
$datos['comercio']['Mercancias'][0]['UnidadAduana']='1';    //opcional
$datos['comercio']['Mercancias'][0]['ValorUnitarioAduana']='116.00';    //opcional
$datos['comercio']['Mercancias'][0]['ValorDolares']='116.00';

$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['Marca']='YAMAHA';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['Modelo']='DDA-21';    //opcional
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['SubModelo']='DDD1';    //opcional
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][0]['NumeroSerie']='3292383823';    //opcional
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['Marca']='str12342';
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['Modelo']='str12342';    //opcional
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['SubModelo']='str12342';    //opcional
$datos['comercio']['Mercancias'][0]['DescripcionesEspecificas'][1]['NumeroSerie']='3292383823';    //opcional 


$res= cfdi_generar_xml($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>