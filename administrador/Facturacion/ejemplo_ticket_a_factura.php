<?php
/**
 * @author MultiFacturas.com
 * @copyright 2014
 * 
 * EL array $datos contiene la información de la factura a generar
 * 
 * GENERA EL XML Y LO TIMBRA EN BASE A LA INFORMACION DEL ARREGLO $datos
 * 
 * VALIDADOR DE ESTRUCTURA DEL XML
 * https://www.consulta.sat.gob.mx/sicofi_web/moduloECFD_plus/ValidadorCFDI/Validador%20cfdi.html
 * 
 * PARA NOTA DE CREDITO SOLO CAMBIA EL PARAMETRO $datos['factura']['tipocomprobante'] a egreso
 * 
 * EN ALGUNOS EJEMPLOS SON ILUSTRATIVOS DE LOS PARAMETROS, ASI QUE LOS MONTOS NO CONCORDARAN
 * 
 */


//include_once "cfdi32_multifacturas_encoded.php";
date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////


    $datos['PAC']['usuario'] = 'DEMO700101XXX';
    $datos['PAC']['pass'] = 'DEMO700101XXX';
    $datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
    

    $datos['sucursal']=1;//numero de sucursal

//genera 5 tickets
    $datos['subtotal'] = "2200.00"; // suma de conceptos sin impuestos
    $datos['descuento'] = "200.00"; // descuento
    $datos['iva0'] = "0.00"; // iva tasa 0, despues de descuentos
    $datos['iva16'] = "320.00"; // iva tasa 16, despues de descuentos
    $datos['total'] = "2320.00"; // total incluyendo impuestos
    $datos['referencia_software']="A-10"; //referencia Alfa-Numerica a tu software
    $datos['fecha']='2015-02-01'; //fecha de ticket formato yyyy-mm-dd
    $datos['fecha_caducidad']='2015-02-15'; //Ultimo dia para generar la factura formato yyyy-mm-dd
    $datos['metodo_pago']='CHEQUE'; //metodo de pago, ejemplo EFECTIVO, CHEQUE, TRANSFERENCIA, TARJETA DE CREDITO, ETC...
    $datos['forma_pago']='PAGO EN UNA SOLA EXIBICION'; //forma de pago ejemplo PAGO EN UNA SOLA EXIBICION, CREDITO 7 DIAS
    $datos['moneda']='MXN';  //MONEDA  MXN USD EUR  
    $datos['tipo_cambio']='1.00'; //en peso mexicano MXN el valor es 1.00, en otras monedas su paridad actual, ejemplo USD = 14.55
    $datos['numero_cuenta']='4567'; // (opcional) )4 caracteres cuando es transferencia, cheque, etc...   en blanco si es en efectivo
    
    
    
    //AGREGAR 10 CONCEPTOS DE PRUEBA
    for ($i = 1; $i < 11; $i++)
    {
        unset($concepto);
        $concepto['pcantidad'] = 2;
        $concepto['punidad'] = 'PIEZA';
        $concepto['pcodigo'] = "COD$i"; //ID, REF, CODIGO O SKU DEL PRODUCTO
        $concepto['pdescripcion'] = "PRODUCTO PRUEBA $i";
        $concepto['punitario'] = '110.00'; // SIN IVA
        $concepto['psubtotal'] = '220.00';
    
        $datos['conceptos'][] = $concepto;
    }


$res= ticket_generar($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar Ticket</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}


?>