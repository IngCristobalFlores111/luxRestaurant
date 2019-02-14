<?php
// <!-- phpDesigner :: Timestamp [16/02/2015 06:59:25 p.m.] -->
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
    $tipo='TICKETS_TODOS';  // TICKETS_TODOS|TICKETS_FACTURADOS|TICKETS_NOFACTURADOS|TICKETS_CADUCADOS
    $fecha_inicial='2015-01-01';    //yyyy-mm-dd
    $fecha_final='2015-02-20';      //yyyy-mm-dd

    $res=ticket_reporte($rfc,$pass,$tipo,$fecha_inicial,$fecha_final);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Reporte Ticket</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>