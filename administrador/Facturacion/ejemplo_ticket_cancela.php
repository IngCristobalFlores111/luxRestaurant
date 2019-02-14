<?php
// <!-- phpDesigner :: Timestamp [09/03/2015 11:51:32 a.m.] -->
/**
 * @author MultiFacturas.com
 * @copyright 2015
 * 
 * EN ALGUNOS EJEMPLOS SON ILUSTRATIVOS DE LOS PARAMETROS, ASI QUE LOS MONTOS NO CONCORDARAN
 * 
 */

date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////


    $rfc = 'DEMO700101XXX';
    $pass = 'DEMO700101XXX';
    
    $codigo='AC-6980-fD8'; // CODIGO QUE DA EL SISTEMA
//    $codigo='A100'; // CODIGO DEL SOFTWARE, ADVERTENCIA SI HAY REPETIDOS CANCELARA VARIOS


    $res=ticket_cancela($rfc,$pass,$codigo);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////

echo "<h1>Respuesta Reporte Ticket</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>