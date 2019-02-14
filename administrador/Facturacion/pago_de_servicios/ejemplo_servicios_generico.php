<?php
error_reporting(E_ALL);
date_default_timezone_set('America/Mexico_City');
include "lib/cfdi32_multifacturas.php";

$datos['clientId'] = '436431';
$datos['storeId'] = '1';
$datos['posId'] = '64282';
$datos['clerkCode'] = '261015';
$datos['distribuidor'] = 'DEMO700101XXX';
$datos['modulo'] = "servicios";
$datos['idProducto'] = '1';
$datos['monto'] = '20';
$datos['idTransaction'] = '9';
$datos['ref1'] = '7894561230';
$datos['servicios'] = 'TRANSACCION_GENERICA';

$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/aaa010101aaa.cer.pem';
$datos['conf']['key'] = 'pruebas/aaa010101aaa.key.pem';
$datos['conf']['pass'] = '12345678a';

$res = cargar_modulo_multifacturas($datos);
echo "<pre>";
print_r($res);
echo "</pre>";