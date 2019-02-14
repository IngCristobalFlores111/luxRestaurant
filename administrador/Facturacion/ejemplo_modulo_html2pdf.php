<?php
error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG
include "lib/cfdi32_multifacturas.php";
date_default_timezone_set('America/Mexico_City');
include_once "lib/cfdi32_multifacturas.php";

$datosHTML['RESPUESTA_UTF8'] = "SI";
$datosHTML['PAC']['usuario'] = "DEMO700101XXX";
$datosHTML['PAC']['pass'] = "DEMO700101XXX";
$datosHTML['PAC']['produccion'] = "NO";
//MODULO MULTIFACTURAS : CONVIERTE UN XML CFDI A HTML
$datosHTML['modulo']="cfdi2html";                                                //NOMBRE MODULO
$datosHTML['rutaxml']="timbrados/cfdi_ejemplo_factura_comercio_exterior.xml";    //RUTA DEL XML CFDI
$datosHTML['titulo']="factura ejemplo";                                          //TITULO DE FACTURA
$datosHTML['tipo']="FACTURA";                                                    //TIPO DE FACTURA VENTA,NOMINA,ARRENDAMIENTO, ETC
$datosHTML['path_logo']="timbrados/LOGOAEDESADECV.jpg";                          //RUTA DE LOGOTIPO DE FACTURA
$datosHTML['notas']="una nota mas y masa";                                       //NOTA IMPRESA EN FACTURA
$datosHTML['color_marco']="#013ADF";                                             //COLOR DEL MARCO DE LA FACTURA
$datosHTML['color_marco_texto']="#FFFFFF";                                       //COLOR DEL TEXTO DEL MARCO DE LA FACTURA
$datosHTML['color_texto']="#0174DF";                                             //COLOR DEL TEXTO EN GENERAL
$datosHTML['fuente_texto']="monospace";                                          //FUENTE DEL TEXTO EN GENERAL
   
$res = cargar_modulo_multifacturas($datosHTML);                                  //FUNCION QUE CARGA EL MODULO cfdi2html
$HTML=$res['html'];                                     //HTML DEL XML           //RESPUESTA DE LA FUNCION CARGAR MODULO

//////////////////////////////////////////////////////////////////////////////
//CONVERTIR EL HTML DEL XML CFDI A PDF
$datosPDF['PAC']['usuario'] = "DEMO700101XXX";
$datosPDF['PAC']['pass'] = "DEMO700101XXX";
$datosPDF['PAC']['produccion'] = "NO";
$datosPDF['modulo']="html2pdf";                                                   //NOMBRE MODULO
$datosPDF['html']="$HTML";                                                        // HTML DE XML CFDI A CONVERTIR A PDF
$datosPDF['archivo_html']="";                                                     // OPCION SI SE TIENE UN ARCHIVO .HTML       
$datosPDF['archivo_pdf']="/var/www/avances/multifacturas_docs/multifacturas_sdk_desarrollo/timbrados/cfdi_ejemplo_factura_comercio_exterior.pdf";
//$datosPDF['archivo_pdf']="RUTA DONDE SE CREARA EL PDF/nombrearhivo.pdf";          //RUTA DONDE SE GUARDARA EL PDF
$res = cargar_modulo_multifacturas($datosPDF);                                    //RESPUESTA DE LA FUNCION CARGAR MODULO  

echo "<pre>";
print_r($res);
echo "</pre>";
