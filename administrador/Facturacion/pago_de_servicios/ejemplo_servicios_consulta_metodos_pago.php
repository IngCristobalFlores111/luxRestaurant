<?php
error_reporting(0);
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas.php";

$datos['PAC']['usuario'] = "DEMO700101XXX";
$datos['PAC']['pass'] = "DEMO700101XXX";
$datos['PAC']['produccion'] = "NO";
$datos['distribuidor'] = 'DEMO700101XXX';
$datos['modulo'] = "servicios";
$datos['RESPUESTA_UTF8'] = "SI";
$datos['servicios'] = "METODOS_PAGO";
$datos['clientId'] = "436431";
$datos['clerkCode'] = "261015";
$datos['posId'] = "64282";
$datos['storeId'] = "1";

$res = cargar_modulo_multifacturas($datos);
echo "<pre>";
print_r($res);
echo "</pre>";
