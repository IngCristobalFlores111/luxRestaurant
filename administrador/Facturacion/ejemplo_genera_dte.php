<?php
//error_reporting(0);

// NO OLVIDAR ESTE INCLUDE
include_once "lib/cfdi32_multifacturas.php";

// PAC
$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]

// Modulo para DTE
$datos['modulo'] = 'dte';

//
$datos['conf']['file'] = 'pruebas/roco.pfx';
$datos['conf']['pass'] = 'rr03r9';
$datos['conf']['CAF'] = __DIR__ . '/lib/modulos/dte/CAF.xml';

// Ruta donde se almacenara el DTE
$datos['ruta_dte'] = 'timbrados/';

// Caratula
//$datos['caratula']['RutEnvia'] = '11222333-4' (se obtiene de la firma)
$datos['caratula']['RutReceptor'] = '60803000-K';
$datos['caratula']['FchResol'] = '2016-08-03';
$datos['caratula']['NroResol'] = '0';

// Datos del Encabezado
$datos['dte']['Encabezado']['IdDoc']['TipoDTE'] = '33';
$datos['dte']['Encabezado']['IdDoc']['Folio'] = '1';

// Datos del Emisor
$datos['dte']['Encabezado']['Emisor']['RUTEmisor'] = '16271370-1';
$datos['dte']['Encabezado']['Emisor']['RznSoc'] = 'RODRIGO ANTONIO ROCO ALDANA';
$datos['dte']['Encabezado']['Emisor']['GiroEmis'] = 'MANTENIMIENTO Y REPARACION DE MAQUINARIA DE OFICINA, CONTABILIDAD E IN';
$datos['dte']['Encabezado']['Emisor']['Acteco'] = '31341';
$datos['dte']['Encabezado']['Emisor']['DirOrigen'] = '33 OTE 7 1/2 SUR 3653';
$datos['dte']['Encabezado']['Emisor']['CmnaOrigen'] = 'TALCA';

// Datos del Receptor
$datos['dte']['Encabezado']['Receptor']['RUTRecep'] = '60803000-K';
$datos['dte']['Encabezado']['Receptor']['RznSocRecep'] = 'EMPRESA  LTDA';
$datos['dte']['Encabezado']['Receptor']['GiroRecep'] = 'COMPUTACION';
$datos['dte']['Encabezado']['Receptor']['DirRecep'] = 'SAN DIEGO 2222';
$datos['dte']['Encabezado']['Receptor']['CmnaRecep'] = 'LA FLORIDA';

// Datos del Detalle
$datos['dte']['Detalle'][0]['NmbItem'] = 'Cajón AFECTO';
$datos['dte']['Detalle'][0]['QtyItem'] = '194';
$datos['dte']['Detalle'][0]['PrcItem'] = '3254';

$datos['dte']['Detalle'][1]['NmbItem'] = 'Relleno AFECTO';
$datos['dte']['Detalle'][1]['QtyItem'] = '69';
$datos['dte']['Detalle'][1]['PrcItem'] = '5413';

/*
 * Se obtiene la respuesta del modulo
 */
$res = cargar_modulo_multifacturas($datos);

// Se muestran los resultados
print_r($res);