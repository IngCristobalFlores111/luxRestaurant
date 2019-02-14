<?php



date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas_PHP7.php";

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
include_once("../PHP/new/functions.php");
$query ="SELECT * FROM `tbfactura_config` ORDER BY idConfig ASC LIMIT 1";
$sql = createMysqliConnection();
$config =$sql->executeQuery($query);
$config = $config[0];
$datos['cancelar']='SI';
$datos['cfdi']='timbrados/'.$request->xmlCancelar;
$datos['PAC']['usuario'] = $config['pac_usuario'];
$datos['PAC']['pass'] = $config['pac_password'];
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/'.$config['archivo_cer'];
$datos['conf']['key'] = 'pruebas/'.$config['archivo_key'];
$datos['conf']['pass'] = $config['password'];

$res= cfdi_cancelar($datos);

print_r(json_encode($res,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));

if($res['codigo_mf_numero']=='0'){
    $query ="UPDATE `tbfactura` SET estatus = ? WHERE idFactura = ?";
    $sql->execQueryBinders("ii",0,$request->idFactura);
}


?>