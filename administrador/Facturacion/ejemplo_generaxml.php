<?php
error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');
// NO OLVIDAR ESTE INCLUDE
include_once "lib/cfdi32_multifacturas.php";

/*
 * Se indican las credenciales de MultiFacturas
 */
$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]

/*
 * Se especifica que se utilizara el modulo 'descargamasiva'
 */
$datos['modulo'] = 'generaxml';

$tasaIVA = 0.16;
$numConceptos = 10;
$valUnit = 100;
$importe = 100;
$subTotal = $numConceptos * $importe;
$total = $subTotal * (1 + $tasaIVA);
$totalTraslados = $total - $subTotal;

$datos['ruta_xml'] = 'ejemplo_generarxml.xml';

// Datos de la factura
$datos['factura']['LugarExpedicion'] = 'MONTERREY NUEVO LEÓN';
$datos['factura']['Moneda'] = 'MXN';
$datos['factura']['TipoCambio'] = '1.0';
$datos['factura']['descuento'] = '0.0';
$datos['factura']['fecha'] = '2016-07-29T13:18:00';
$datos['factura']['folio'] = '100';
$datos['factura']['serie'] = 'A';
$datos['factura']['formaDePago'] = 'PAGO EN UNA SOLA EXHIBICION';
$datos['factura']['metodoDePago'] = '01';
$datos['factura']['subTotal'] = $subTotal;
$datos['factura']['tipoDeComprobante'] = 'ingreso';
$datos['factura']['total'] = $total;
$datos['factura']['version'] = '3.2';

// Datos del Emisor
$datos['factura']['cfdi:Emisor']['rfc'] = 'AAA010101AAA';
$datos['factura']['cfdi:Emisor']['nombre'] = 'ACCEM SERVICIOS EMPRESARIALES SC';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['calle'] = 'JUAREZ';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['codigoPostal'] = '00000';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['colonia'] = 'CENTRO';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['estado'] = 'NUEVO LEON';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['localidad'] = 'MONTERREY';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['municipio'] = 'MONTERREY';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['noExterior'] = '100';
$datos['factura']['cfdi:Emisor']['cfdi:DomicilioFiscal']['pais'] = 'MEXICO';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['calle'] = 'HIDALGO';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['codigoPostal'] = '00000';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['colonia'] = 'LAS CUMBRES 3 SECTOR';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['estado'] = 'NUEVO LEON';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['localidad'] = 'MONTERREY';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['municipio'] = 'MONTERREY';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['noExterior'] = '240';
$datos['factura']['cfdi:Emisor']['cfdi:ExpedidoEn']['pais'] = 'MEXICO';
$datos['factura']['cfdi:Emisor']['cfdi:RegimenFiscal']['regimen'] = 'MI REGIMEN';

// Datos del receptor
$datos['factura']['cfdi:Receptor']['nombre'] = 'MIGUEL ANGEL SOSA HERNANDEZ';
$datos['factura']['cfdi:Receptor']['rfc'] = 'AAA010101AAA';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['calle'] = 'PERIFERICO';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['codigoPostal'] = '00000';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['colonia'] = 'SAN ANGEL';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['estado'] = 'DISTRITO FEDERAL';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['localidad'] = 'CIUDAD DE MEXICO';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['municipio'] = 'ALVARO OBREGON';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['noExterior'] = '1024';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['noInterior'] = 'B';
$datos['factura']['cfdi:Receptor']['cfdi:Domicilio']['pais'] = 'MEXICO';

// Conceptos
for($i = 0; $i < $numConceptos; $i++) {
	$datos['factura']['cfdi:Conceptos'][$i]['cfdi:Concepto']['cantidad'] = 1;
	$datos['factura']['cfdi:Conceptos'][$i]['cfdi:Concepto']['descripcion'] = 'PRODUCTO DE PRUEBA ' . ($i + 1);
	$datos['factura']['cfdi:Conceptos'][$i]['cfdi:Concepto']['unidad'] = 'PIEZA';
	$datos['factura']['cfdi:Conceptos'][$i]['cfdi:Concepto']['noIdentificacion'] = 'COD' . ($i + 1);
	$datos['factura']['cfdi:Conceptos'][$i]['cfdi:Concepto']['valorUnitario'] = $valUnit;
	$datos['factura']['cfdi:Conceptos'][$i]['cfdi:Concepto']['importe'] = $importe;
}

// Impuestos
$datos['factura']['cfdi:Impuestos']['totalImpuestosTrasladados'] = $totalTraslados;
$datos['factura']['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['impuesto'] = 'IVA';
$datos['factura']['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['tasa'] = $tasaIVA * 100;
$datos['factura']['cfdi:Impuestos']['cfdi:Traslados']['cfdi:Traslado']['importe'] = $totalTraslados;

/*
 * Se obtiene la respuesta del modulo
 */
$res = cargar_modulo_multifacturas($datos);

// Se muestran los resultados
print_r($res);
