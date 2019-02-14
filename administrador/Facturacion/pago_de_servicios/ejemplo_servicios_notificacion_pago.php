<?php
error_reporting(0);
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas.php";

$datos['PAC']['usuario']='DEMO700101XXX';
$datos['PAC']['pass']='DEMO700101XXX';
$datos['PAC']['produccion']='NO';
$datos['distribuidor'] = 'DEMO700101XXX';
$datos['modulo'] = "servicios";
$datos['clientId']='436431';
$datos['storeId']='1';
$datos['posId']='64282';
$datos['clerkCode']='261015';
$datos['servicios']='NOTIFICACION_DE_PAGO';
$datos['monto']='1000';
$datos['banco']='Banamex';
$datos['documento']='A0001';
$datos['fecha']='20160310';
$datos['formaPago']='Efectivo';
$datos['nombreProveedor']='PagoServicios';
$datos['RESPUESTA_UTF8']='SI';
$datos['distribuidor']='DEMO700101XXX';

$res = cargar_modulo_multifacturas($datos);
echo "<pre>";
print_r($res);
echo "</pre>";
