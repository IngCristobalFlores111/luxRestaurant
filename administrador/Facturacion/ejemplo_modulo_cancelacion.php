<?php
error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas.php";

$datos['RESPUESTA_UTF8'] = "SI";

$datos['PAC']['usuario'] = "DEMO700101XXX";
$datos['PAC']['pass'] = "DEMO700101XXX";
$datos['PAC']['produccion'] = "NO";

$datos['SDK']['ruta'] = "C:\\multifacturas_sdk";

//$datos['cfdis'] = array('39218E56-295C-4BA3-A35A-7676573A2DC5', 'F474D744-E705-4DB5-B38D-6111F60AAD26', '38B6DD8A-D3EE-43D2-A4EE-D72A338E8D30');
//$datos['rfc'] = 'SOHM7509289MA';
//$datos['pfx64'] = base64_encode(file_get_contents('nuevo.pfx'));

$datos['modulo'] = 'cancelacion';
//$datos['uuid'] = 'FE8395D5-3F92-406D-B7B7-03EC849E3F37';//'a9c45b8c-ebd6-4179-8cd5-d17b3c81bf35';//'17aeeccc-6ec0-49af-b786-0878d34e0b27';
$datos['cer'] = 'pruebas/sohm7509289ma.cer';
$datos['key'] = 'pruebas/sohm7509289ma.key';
$datos['pass'] = 'sohm2895';
$datos['rfc'] = 'SOHM7509289MA';
$datos['xml'] = 'VENTA-A-PUBLICO-EN-GENERAL-.xml';

$res = cargar_modulo_multifacturas($datos);
var_dump($res);
