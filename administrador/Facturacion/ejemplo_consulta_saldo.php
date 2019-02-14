<?php
/**
 * @author MultiFacturas.com
 * @copyright 2013
 * 
 * EL array $datos contiene la información de la factura a generar
 * 
 * GENERACION DEL CERTIFICADO Y LLAVE .PEM
 * ESTOS SE REQUIEREN PARA LA GENERACION DEL SELLO
 * SE RECOMIENDA GENERARLOS UNA SOLA VES Y ALMACENAR SU INFORMACION
 * PARA EVITAR PROCESAMIENTO ADICIONAL
 * 
 * $respuesta=certificado_pem($datos);
 * 
 * GENERA EL XML Y LO TIMBRA EN BASE A LA INFORMACION DEL ARREGLO $datos
 * 
 * VALIDADOR DE ESTRUCTURA DEL XML
 * https://www.consulta.sat.gob.mx/sicofi_web/moduloECFD_plus/ValidadorCFDI/Validador%20cfdi.html
 */


date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";

$usuario='DEMO700101XXX';
$clave='DEMO700101XXX';
$res= saldo_mf($usuario,$clave);



///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Consulta de Saldo</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>