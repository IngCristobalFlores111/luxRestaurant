<?php
//C:\multifacturas_sdk\php -c C:\multifacturas_sdk\php.ini -f c:\test.php
error_reporting(0);




date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";


////////////////////////////////////////////////////////////
////// PRUEBA DIVIDENDOS
////////////////////////////////////////////////////////////


//multifacturas_modo_pruebas();

$datos['cfdi']='timbrados/retencion_dividendos.xml';
$datos['remueve_acentos']='SI';
$datos['retencion']='SI';

$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/aaa010101aaa.cer.pem';
$datos['conf']['key'] = 'pruebas/aaa010101aaa.key.pem';
$datos['conf']['pass'] = '12345678a';


//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';






$datos['factura']['MesIni']='6';
$datos['factura']['MesFin']='6';
$datos['factura']['Ejerc']='2014';
$datos['factura']['RFCRecep']='XAXX010101000';
$datos['factura']['RFCEmisor']='AAA010101AAA';

$datos['factura']['NomDenRazSocR']='PÚBLICO EN GENERAL S DE TST';
$datos['factura']['CURPR']='VAHE820926HNLLRD02';
$datos['factura']['NacionalidadE']='Nacional';
$datos['factura']['NomDenRazSocE']='Empresa DEMO para Rentenciones S de TST';
$datos['factura']['CURPE']='AAAA010101HNLMNLD2';
$datos['factura']['FolioInt']='TEST00003';
$datos['factura']['DescRetenc']='Dividendos';
$datos['factura']['CveRetenc']='14';
//$datos['factura']['fecha_expedicion']=date('Y-m-d H:i:sP',time()-120);//// <--- atencion hay un caracter extra
$datos['factura']['fecha_expedicion']=date('Y-m-d H:i:00P',time()-120);//// <--- atencion hay un caracter extra




//dividendos
$datos['dividendos']['DividOUtil']['CveTipDivOUtil']='04';
$datos['dividendos']['DividOUtil']['MontISRAcredRetMexico']='100.00';
$datos['dividendos']['DividOUtil']['MontISRAcredRetExtranjero']='200.00';
$datos['dividendos']['DividOUtil']['MontRetExtDivExt']='300.00';
$datos['dividendos']['DividOUtil']['TipoSocDistrDiv']='Sociedad Nacional';
$datos['dividendos']['DividOUtil']['MontISRAcredNal']='400.00';
$datos['dividendos']['DividOUtil']['MontDivAcumNal']='500.00';
$datos['dividendos']['DividOUtil']['MontDivAcumExt']='600.00';


$datos['dividendos']['Remanente']['ProporcionRem']='1000.00';


//retenciones
$datos['factura']['montoTotOperacion']='14000.00';
$datos['factura']['montoTotGrav']='13000.00';
$datos['factura']['montoTotExent']='1000.00';
$datos['factura']['montoTotRet']='3500.00';

$datos['dividendos']['totales'][0]['BaseRet']='1000.00';
$datos['dividendos']['totales'][0]['Impuesto']='01';
$datos['dividendos']['totales'][0]['montoRet']='500.00';
$datos['dividendos']['totales'][0]['TipoPagoRet']='Pago definitivo';


$res= cfdi_retenicion_generar_xml($datos,$produccion='NO');


echo "<h1>Respuesta </h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}




?>