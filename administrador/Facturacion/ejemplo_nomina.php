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

error_reporting(0); // OPCIONAL DESACTIVA NOTIFICACIONES DE DEBUG

date_default_timezone_set('America/Mexico_City');

include_once "lib/cfdi32_multifacturas.php";

/////////////////////////////////////////////////////////////////////////////////
////////////     CREAR ARCHIVOS .PEM
/////////////////////////////////////////////////////////////////////////////////
$datos['modonomina']='SI';


$datos['PAC']['usuario'] = 'DEMO700101XXX';
$datos['PAC']['pass'] = 'DEMO700101XXX';
$datos['PAC']['produccion'] = 'NO'; //   [SI|NO]
$datos['conf']['cer'] = 'pruebas/aaa010101aaa.cer.pem';
$datos['conf']['key'] = 'pruebas/aaa010101aaa.key.pem';
$datos['conf']['pass'] = '12345678a';



//RUTA DONDE ALMACENARA EL CFDI
$datos['cfdi']='timbrados/cfdi_ejemplo_nomina.xml';
// OPCIONAL GUARDAR EL XML GENERADO ANTES DE TIMBRARLO
$datos['xml_debug']='timbrados/sin_timbrar_ejemplo_nomina.xml';

//OPCIONAL, ACTIVAR SOLO EN CASO DE CONFLICTOS
//$datos['remueve_acentos']='SI';

//OPCIONAL, UTILIZAR LA LIBRERIA PHP DE OPENSSL, DEFAULT SI
$datos['php_openssl']='SI';

$datos['factura']['serie'] = 'A'; //opcional
$datos['factura']['folio'] = '100'; //opcional
$datos['factura']['fecha_expedicion'] = date('Y-m-d H:i:s',time()-120);// Opcional  "time()-120" para retrasar la hora 2 minutos para evitar falla de error en rango de fecha



$datos['factura']['metodo_pago'] = '01'; // VER DOCUMENTACION :: EFECTIV0, CHEQUE, TARJETA DE CREDITO, TRANSFERENCIA BANCARIA, NO IDENTIFICADO
$datos['factura']['forma_pago'] = 'PAGO EN UNA SOLA EXHIBICION';  //PAGO EN UNA SOLA EXHIBICION, CREDITO 7 DIAS, CREDITO 15 DIAS, CREDITO 30 DIAS, ETC
$datos['factura']['tipocomprobante'] = 'egreso'; 
$datos['factura']['moneda'] = 'MXN'; // MXN USD EUR
$datos['factura']['tipocambio'] = '1.0000'; // OPCIONAL (MXN = 1.00, OTRAS EJ: USD = 13.45; EUR = 16.86)
$datos['factura']['LugarExpedicion'] = 'MONTERREY, NUEVO LEON';
//$datos['factura']['NumCtaPago'] = '0234'; //opcional; 4 DIGITOS pero obligatorio en transferencias y cheques

$datos['factura']['RegimenFiscal'] = 'MI REGIMEN';

$datos['emisor']['rfc'] = 'AAA010101AAA'; //RFC DE PRUEBA 
$datos['emisor']['nombre'] = 'ACCEM SERVICIOS EMPRESARIALES SC';  // EMPRESA DE PRUEBA
$datos['emisor']['DomicilioFiscal']['calle'] = 'JUAREZ';
$datos['emisor']['DomicilioFiscal']['noExterior'] = '100';
$datos['emisor']['DomicilioFiscal']['noInterior'] = ''; //(opcional)
$datos['emisor']['DomicilioFiscal']['colonia'] = 'CENTRO';
$datos['emisor']['DomicilioFiscal']['localidad'] = 'MONTERREY';
$datos['emisor']['DomicilioFiscal']['municipio'] = 'MONTERREY'; // o delegacion
$datos['emisor']['DomicilioFiscal']['estado'] = 'NUEVO LEON';
$datos['emisor']['DomicilioFiscal']['pais'] = 'MEXICO';
$datos['emisor']['DomicilioFiscal']['CodigoPostal'] = '01234'; // 5 digitos

//SI EX EXPEDIDO EN SUCURSAL CAMBIA EL DOMICILIO
//SI ES EN EL MISMO DOMICILIO REPETIR INFORMACION
$datos['emisor']['ExpedidoEn']['calle'] = 'HIDALGO';
$datos['emisor']['ExpedidoEn']['noExterior'] = '240';
$datos['emisor']['ExpedidoEn']['noInterior'] = ''; //(opcional)
$datos['emisor']['ExpedidoEn']['colonia'] = 'LAS CUMBRES 3 SECTOR';
$datos['emisor']['ExpedidoEn']['localidad'] = 'MONTERREY';
$datos['emisor']['ExpedidoEn']['municipio'] = 'MONTERREY'; // O DELEGACION
$datos['emisor']['ExpedidoEn']['estado'] = 'NUEVO LEON';
$datos['emisor']['ExpedidoEn']['pais'] = 'MEXICO';
$datos['emisor']['ExpedidoEn']['CodigoPostal'] = '64610'; // 5 digitos

// IMPORTANTE PROBAR CON NOMBRE Y RFC REAL O GENERARA ERROR DE XML MAL FORMADO
$datos['receptor']['rfc'] = 'SOHM7509289MA';
$datos['receptor']['nombre'] = 'MIGUEL ANGEL SOSA HERNANDEZ';
//opcional
$datos['receptor']['Domicilio']['calle'] = 'PERIFERICO';
$datos['receptor']['Domicilio']['noExterior'] = '1024';
$datos['receptor']['Domicilio']['noInterior'] = 'B';
$datos['receptor']['Domicilio']['colonia'] = 'SAN ANGEL';
$datos['receptor']['Domicilio']['localidad'] = 'CIUDAD DE MÉXICO';
$datos['receptor']['Domicilio']['municipio'] = 'ALVARO OBREGON';
$datos['receptor']['Domicilio']['estado'] = 'DISTRITO FEDERAL';
$datos['receptor']['Domicilio']['pais'] = 'MEXICO';
$datos['receptor']['Domicilio']['CodigoPostal'] = '23010'; // 5 digitos

//AGREGAR 10 CONCEPTOS DE PRUEBA
    $concepto['cantidad'] = 1;
    $concepto['unidad'] = 'SERVICIO';
    $concepto['ID'] = "NOM"; //ID, REF, CODIGO O SKU DEL PRODUCTO
//    $concepto['descripcion'] = "PRODUCTO PRUEBA > '$i'";
    $concepto['descripcion'] = "NOMINA";
    $concepto['valorunitario'] = '100.00'; // SIN IVA
    $concepto['importe'] = '100.00';

    $datos['conceptos'][] = $concepto;

$datos['factura']['subtotal'] = 1160.00; // sin impuestos
$datos['factura']['total'] = 1160.00; // total incluyendo impuestos
$datos['factura']['descuento'] = 100.00; // descuento sin impuestos

$retenido['impuesto'] = 'ISR';
$retenido['importe'] = 100.00; // iva de los productos facturados
$datos['impuestos']['retenidos'][] = $retenido;



//////////////////////////////////////////////////////////////
//DATOS GENERALES DE LA NOMINA
//////////////////////////////////////////////////////////////

//OBLIGATORIOS
$datosnomina['NumEmpleado']='1040';
$datosnomina['CURP']='DESO801116HGTLRS08';
$datosnomina['TipoRegimen']='001';
$datosnomina['FechaPago']='2013-12-13';
$datosnomina['FechaInicialPago']='2013-12-06';
$datosnomina['FechaFinalPago']='2013-12-13';
$datosnomina['NumDiasPagados']='5';
$datosnomina['PeriodicidadPago']='semanal';
//
$datosnomina['FechaInicioRelLaboral']='2012-12-13';


//OPCIONALES
/*
$datosnomina['SalarioBaseCotApor']='89.58';
$datosnomina['SalarioDiarioIntegrado']='60.50';
$datosnomina['RiesgoPuesto']='003';
$datosnomina['Departamento']='ALMACEN';
$datosnomina['Puesto']='JEFE DE ALMACEN';
$datosnomina['TipoContrato']='Base';
$datosnomina['TipoJornada']='Diurna';

$datosnomina['RegistroPatronal']='B471578365';
$datosnomina['NumSeguridadSocial']='12988020199';

// AL NO INGRESARSE LA CALCULA CON DIFERENCIA DE FECHAS FechaFinalPago-FechaInicioRelLaboral
$datosnomina['Antiguedad']='365';  //semanas
//UTILIZADOS CUANDO SE HACE TRANSFERENCIA ELECTRONICA
$datosnomina['CLABE']='012345678901234567';
$datosnomina['Banco']='123';
*/




//////////////////////////////////////////////////////////////
//AGREGA PERCEPCIONES
//PERSEPCION 1
$percepcion['TipoPercepcion']='001';
$percepcion['Clave']='019';
$percepcion['Concepto']='SUELDOS SEMANAL';
$percepcion['ImporteGravado']='2404.22';
$percepcion['ImporteExento']='0.00';
$percepciones[]=$percepcion;
unset($percepcion);

//PERSEPCION 2
$percepcion['TipoPercepcion']='010';
$percepcion['Clave']='001';
$percepcion['Concepto']='PREMIOS DE PUNTUALIDAD';
$percepcion['ImporteGravado']='240.42';
$percepcion['ImporteExento']='0.00';
$percepciones[]=$percepcion;
unset($percepcion);


//PERSEPCION 3
$percepcion['TipoPercepcion']='016';
$percepcion['Clave']='002';
$percepcion['Concepto']='PREMIOS DE ASISTENCIA';
$percepcion['ImporteGravado']='240.42';
$percepcion['ImporteExento']='0.00';
$percepciones[]=$percepcion;
unset($percepcion);

//////////////////////////////////////////////////////////////
//AGREGAR DEDUCCIONES

//DEDUCCION 1
$deduccion['TipoDeduccion']='001';
$deduccion['Clave']='008';
$deduccion['Concepto']='IMSS';
$deduccion['ImporteGravado']='64.39';
$deduccion['ImporteExento']='0.00';
$deducciones[]=$deduccion;
unset($deduccion);

//DEDUCCION 2
$deduccion['TipoDeduccion']='005';
$deduccion['Clave']='012';
$deduccion['Concepto']='INFONAVIT';
$deduccion['ImporteGravado']='64.39';
$deduccion['ImporteExento']='0.00';
$deducciones[]=$deduccion;
unset($deduccion);


//DEDUCCION 3
$deduccion['TipoDeduccion']='002';
$deduccion['Clave']='008';
$deduccion['Concepto']='ISR';
$deduccion['ImporteGravado']='360.86';
$deduccion['ImporteExento']='0.00';
$deducciones[]=$deduccion;
unset($deduccion);




///////////////////////*BETA*///////////////////////
// AGREGA INCAPACIDAD 
// CONCEPTO 1 INCAPACIDAD

$incapacidad['DiasIncapacidad']='1';
$incapacidad['TipoIncapacidad']='1'; //Razón de la incapacidad: Catálogo publicado en el portal del SAT en internet
$incapacidad['Descuento']='1';
$incapacidades[]=$incapacidad;
unset($incapacidad);
$datos['nomina']['incapacidades']=$incapacidades;


///HORAS EXTRAS
//Dias  -- Número de días en que el trabajador realizó horas extra en el periodo
//TipoHoras -- Tipo de pago de las horas extra: Dobles o Triples
//HorasExtra -- Número de horas extra trabajadas en el periodo
//ImportePagado -- Importe pagado por las horas extra        

$horasextra['Dias']='2';
$horasextra['TipoHoras']='Dobles';
$horasextra['HorasExtra']='33';
$horasextra['ImportePagado']='1';
$horasextras[]=$horasextra;
unset($horasextra);
$datos['nomina']['horasextras']=$horasextras;



$datos['nomina']['datos']=$datosnomina;
$datos['nomina']['percepciones']=$percepciones;
$datos['nomina']['deducciones']=$deducciones;



$res= cfdi_generar_xml($datos);


///////////    MOSTRAR RESULTADOS DEL ARRAY $res   ///////////
 
echo "<h1>Respuesta Generar XML y Timbrado</h1>";
foreach($res AS $variable=>$valor)
{
    $valor=htmlentities($valor);
    $valor=str_replace('&lt;br/&gt;','<br/>',$valor);
    echo "<b>[$variable]=</b>$valor<hr>";
}



?>