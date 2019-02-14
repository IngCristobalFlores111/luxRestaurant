<?php
//error_reporting(E_ALL);

function _inim_imprime()
{

  
}


///////////////////////////////////////////////////////////////////////////////
function imprime_factura($xml_archivo,$titulo,$tipo_factura,$logo,$nota_impresa,$color_marco,$color_marco_texto,$color_texto,$fuente_texto)
{
    
    //FUNCION QUE REGRESA CSS -> CARLOS
    if($color_marco==''){
        $color_marco='black';
    }
    if($color_texto==''){
        $color_texto='black';
    }
    if($color_marco_texto==''){
        $color_marco_texto='white';
    }

    $css=html_css($color_marco,$color_marco_texto,$color_texto,$fuente_texto);
    $valor.="<head>$css</head>";
    
    if(file_exists($xml_archivo)==false)
    {
        return 'ERROR 1, NO EXISTE XML, MUY  POSIBLEMENTE ES UNA PRUEBA FALLIDA';
    }
    if(filesize($xml_archivo)<100)
    {
        return 'ERROR 2, XML INVALIDO';        
    }

    $xml = simplexml_load_file($xml_archivo);

    $ns = $xml->getNamespaces(true);

    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);
    
//    $xml->registerXPathNamespace('i', $ns['implocal']);

/*
    $xml->registerXPathNamespace('c', $ns['cfdi']);
    $xml->registerXPathNamespace('t', $ns['tfd']);
*/


     
     
    //EMPIEZO A LEER LA INFORMACION DEL CFDI E IMPRIMIRLA
    foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante)
    {

          $cfdiComprobante['version'];
          
          $fecha_expedicion= $cfdiComprobante['fecha'];
//TODO: aki etiketa de cuenta en cheke
          $metodo_pago= autoformato_impresion($cfdiComprobante['metodoDePago']);

          
          $sello= $cfdiComprobante['sello'];
          
          $total=$cfdiComprobante['total'];
          $total_=number_format((string)$total,2);

          $Moneda=$cfdiComprobante['Moneda'];
          
          
          
          $subtotal=$cfdiComprobante['subTotal'];
          $subtotal_=number_format((string)$subtotal,2);
          
          $descuento=$cfdiComprobante['descuento'];
          $descuento_=number_format((string)$descuento,2);
          
          $serie=$cfdiComprobante['serie'];
          $folio=$cfdiComprobante['folio'];

          $NumCtaPago=$cfdiComprobante['NumCtaPago'];
          

          
          
          $certificado_key=$cfdiComprobante['certificado'];
          
          $forma_pago=autoformato_impresion( $cfdiComprobante['formaDePago']);
          
          $certificado_no=$cfdiComprobante['noCertificado'];
          
          $cfdiComprobante['tipoDeComprobante'];
          
          $LugarExpedicion=autoformato_impresion($cfdiComprobante['LugarExpedicion']);
    }

    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor)
    {
       $emisor_rfc=$Emisor['rfc'];
       
       $emisor_nombre= autoformato_impresion($Emisor['nombre']);
       
    }
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:DomicilioFiscal') as $DomicilioFiscal)
    {
       $emisor_pais= autoformato_impresion($DomicilioFiscal['pais']);
       
       $emisor_calle= autoformato_impresion($DomicilioFiscal['calle']);
       
       $emisor_estado= autoformato_impresion($DomicilioFiscal['estado']);
       
       $emisor_colonia= autoformato_impresion($DomicilioFiscal['colonia']);
       
       $emisor_municipio= autoformato_impresion($DomicilioFiscal['municipio']);
       
$emisor_localidad= autoformato_impresion($DomicilioFiscal['localidad']);
       
       
       
       $emisor_noExterior= autoformato_impresion($DomicilioFiscal['noExterior']);

       $emisor_noInterior= autoformato_impresion($DomicilioFiscal['noInterior']);
       
       
       $emisor_CP= autoformato_impresion($DomicilioFiscal['codigoPostal']);
       $emisor_CP=sprintf('%05d',$emisor_CP);
       
    }
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:ExpedidoEn') as $ExpedidoEn)
    {
       $expedido_pais= autoformato_impresion($ExpedidoEn['pais']);
       
       $expedido_calle=autoformato_impresion($ExpedidoEn['calle']);
       
       $expedido_estado=autoformato_impresion($ExpedidoEn['estado']);
       
       $expedido_colonia=autoformato_impresion($ExpedidoEn['colonia']);
       
       $expedido_noExterior=autoformato_impresion($ExpedidoEn['noExterior']);

       $expedido_noInterior=autoformato_impresion($ExpedidoEn['noInterior']);


       
       $expedido_CP=autoformato_impresion($ExpedidoEn['codigoPostal']);
       $expedido_CP=sprintf('%05d',$expedido_CP);
       
       $expedido_municipio=autoformato_impresion($ExpedidoEn['municipio']);
$expedido_localidad=autoformato_impresion($ExpedidoEn['localidad']);
       
    }

    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor//cfdi:RegimenFiscal') as $RegimenFiscal)
    {
       $regimen_fiscal=autoformato_impresion($RegimenFiscal['Regimen']);
    }


    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor)
    {
       $receptor_rfc=$Receptor['rfc'];
       
       $receptor_nombre=autoformato_impresion($Receptor['nombre']);
       
    }
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor//cfdi:Domicilio') as $ReceptorDomicilio)
    {
        
       $receptor_pais=autoformato_impresion($ReceptorDomicilio['pais']);
       
       $receptor_calle=autoformato_impresion($ReceptorDomicilio['calle']);
       
       $receptor_estado=autoformato_impresion($ReceptorDomicilio['estado']);
       
       $receptor_colonia=autoformato_impresion($ReceptorDomicilio['colonia']);
       
       $receptor_municipio=autoformato_impresion($ReceptorDomicilio['municipio']);
$receptor_localidad=autoformato_impresion($ReceptorDomicilio['localidad']);
       
       $receptor_noExterior=autoformato_impresion($ReceptorDomicilio['noExterior']);

       $receptor_noInterior=autoformato_impresion($ReceptorDomicilio['noInterior']);
       
       $receptor_CP=autoformato_impresion($ReceptorDomicilio['codigoPostal']);
       $receptor_CP=sprintf('%05d',$receptor_CP);
       
    }


//productos
    $desgloce='<table width="100%">
       <tr class="factura_detalles_cabecera">
        <td width="44px">CNT</td>
        <td  width="75px">UNIDAD</td>
        <td width="75px">CODIGO</td>
        <td>DESCRIPCION</td>
        <td   width="100px" align="right">PRECIO UNITARIO</td>
        <td   width="100px"  align="right">IMPORTE</td>
       </tr>
    
    ';
    $tmp=1;
    
    
    

    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:CuentaPredial') as $PredialData)
    {
        $predial=(string) $PredialData['numero'];
        if($predial!='')
        {
            $predial="<br/>PREDIAL : $predial";
        }
    }

    $subtotal_productos=0.00;
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto)
    {
        
       $unidad=$Concepto['unidad'];
       $importe=$Concepto['importe'];
       $cantidad=$Concepto['cantidad'];
       $descripcion=$Concepto['descripcion'].$predial;
       $precio_unitario=$Concepto['valorUnitario'];
       
       $codigo=$Concepto['noIdentificacion'];
       $numero=$Concepto['numero'];
       if($tmp==0)
       {
           $class='factura_detalles_renglon1';
           $tmp=1;
       }
       else
       {
           $class='factura_detalles_renglon2';
           $tmp=0;
       }
       $descripcion=autoformato_impresion($descripcion);
        $precio_unitario_=number_format((string)$precio_unitario,2);     
        $importe_=number_format((string)$importe,2);  
        $subtotal_productos+=(float)$importe;

       $desgloce.="
       <tr class='$class'>
        <td>$cantidad</td>
        <td>$unidad </td>
        <td>$codigo </td>
        <td>$descripcion</td>
        <td align='right'>$$precio_unitario_</td>
        <td  align='right'>$$importe_</td>
       </tr>
       ";
    }



    $desgloce.='</table>';
    


$isr_retenido=0.00;
$iva_retenido=0.00;


    foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd)
    {
        $timbre_selloCFD= $tfd['selloCFD'];
        $timbre_fecha= $tfd['FechaTimbrado'];
        $timbre_uuid= $uuid=$tfd['UUID'];
        $timbre_noCertificadoSAT= $tfd['noCertificadoSAT'];
        $timbre_version= $tfd['version'];
        $timbre_selloSAT = $sellosat=$tfd['selloSAT'];

    }
    
    
//TRANSLADOS (impuestos)
$total_translados=$total_translados_locales=0;
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Traslados//cfdi:Traslado') as $Traslado)
    {
       $tasa=$Traslado['tasa'];
       $importe=$Traslado['importe'];
       $importe_=number_format((string)$importe,2);
       $impuesto= $Traslado['impuesto'];
       $total_translados=$total_translados+(float)$importe;
       
        $iva_txt.="
                <tr>

                    <td class='factura_totales'>
                    $impuesto ($tasa%) 
                    </td>
                    <td class='factura_totales'>
                     $importe_
                    </td>
                </tr>
        ";

    }

//LOCALES 



    $cadena=file_get_contents($xml_archivo);

    if(strpos($cadena,'ImpuestosLocales')>0)
    {

                    list($tmp,$cadena,$tmp)=explode('ImpuestosLocales',$cadena);
                
                    list($tmp,$cadena2)=explode('>',(string)$cadena,2);//
                    $cadena="<implocal:ImpuestosLocales  >
                    $cadena2"."ImpuestosLocales>
                    ";
                    $xml2 = simplexml_load_string($cadena);
                    $arr = object2array($xml2);
                    $TrasladosLocales=$arr['TrasladosLocales'];
                
                //TRANSLADO LOCAL
                
                    foreach($TrasladosLocales AS $llave_=>$TrasladosLocal)
                    {
                        $ImpLocTrasladado=$TrasladosLocal['@attributes']['ImpLocTrasladado'];
                    if($ImpLocTrasladado=='')
                        $ImpLocTrasladado=$TrasladosLocal['ImpLocTrasladado'];
            
            
                    $Importe=$TrasladosLocal['@attributes']['Importe'];
                    if($Importe=='')
                        $Importe=$TrasladosLocal['Importe']; 
            
            
                    $TasadeTraslado=$TrasladosLocal['@attributes']['TasadeTraslado'];
                    if($TasadeTraslado=='')
                        $TasadeTraslado=$TrasladosLocal['TasadeTraslado'];
            
                   $total_translados=$total_translados+(float)$Importe;
            
            //echo "TL $ImpLocTrasladado $TasadeTraslado% $Importe<br>";
                    $importe_=number_format((string)$Importe,2);
                    $iva_txt.="
                            <tr>
            
                                <td class='factura_totales'>
                                (LOCAL) $ImpLocTrasladado ($TasadeTraslado%) 
                                </td>
                                <td class='factura_totales'>
                                 $importe_
                                </td>
                            </tr>
                    ";        
                }
            
            
            //RETENCIONES LOCAL
              $RetencionesLocales=$arr['RetencionesLocales'];
                foreach($RetencionesLocales AS $llave_=>$RetencionesLocal)
                {
                    $ImpLocRetenido=$RetencionesLocal['@attributes']['ImpLocRetenido'];
                    if($ImpLocRetenido=='')
                        $ImpLocRetenido=$RetencionesLocal['ImpLocRetenido'];
            
            
                    $Importe=$RetencionesLocal['@attributes']['Importe'];
                    if($Importe=='')
                        $Importe=$RetencionesLocal['Importe']; 
            
            
                    $TasadeRetencion=$RetencionesLocal['@attributes']['TasadeRetencion'];
                    if($TasadeRetencion=='')
                        $TasadeRetencion=$RetencionesLocal['TasadeRetencion'];
            
                    $importe_=number_format((string)$Importe,2);
                   $importe_retenciones=$importe_retenciones+$Importe;

                   $retenciones_txt.="
                                   <tr>
                                        <td class='factura_totales'>
                                        RET LOCAL $ImpLocRetenido ($TasadeRetencion%) $
                                        </td>
                                        <td class='factura_totales'>
                                         $importe_
                                        </td>
                                    </tr>
            
                   ";                    
                }
            


        
    }
        


//RETENCIONES
//    $retenciones_txt='';
//    $importe_retenciones=0.00;
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Impuestos//cfdi:Retenciones//cfdi:Retencion') as $Retencion)
    {
       $importe=$Retencion['importe'];
       $impuesto=$Retencion['impuesto'];

       $importe_retenciones=$importe_retenciones+(float)$importe;
       
       $importe_=number_format((string)$importe,2);
       $retenciones_txt.="
                       <tr>
                            <td class='factura_totales'>
                            RET $impuesto $
                            </td>
                            <td class='factura_totales'>
                             $importe_
                            </td>
                        </tr>

       ";
    }
    if($importe_retenciones==0)
    {
       $retenciones_txt=''; 
    }


// SI HAY RETENCIONES MUESTA EL SUBTOTAL ANTES DE RETENCIONES CON IMPUESTOS AGREGADOS
    if($retenciones_txt!='')
    {
        $subtotal_con_retenciones=(float)$subtotal+(float)$total_translados+(float)$total_translados_locales;

        $subtotal_con_retenciones=number_format($subtotal_con_retenciones-$descuento_,2);
        //$subtotal_ //aki
       $retenciones_txt="
                       <tr>
                            <td class='factura_totales'>
                            SUB TOTAL $
                            </td>
                            <td class='factura_totales'>
                            
                             $subtotal_con_retenciones
                            </td>
                        </tr>
                        $retenciones_txt
       ";
        
    }

//NOMINAS
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina:Nomina') as $Nomina)
    {
        $RegistroPatronal= autoformato_impresion($Nomina['RegistroPatronal']);
        $NumEmpleado= autoformato_impresion($Nomina['NumEmpleado']);
        $CURP= autoformato_impresion($Nomina['CURP']);
        
        $TipoRegimen= autoformato_impresion($Nomina['TipoRegimen']);
        
        $NumSeguridadSocial= autoformato_impresion($Nomina['NumSeguridadSocial']);
        $FechaPago= autoformato_impresion($Nomina['FechaPago']);
        $FechaInicialPago= autoformato_impresion($Nomina['FechaInicialPago']);
        $FechaFinalPago= autoformato_impresion($Nomina['FechaFinalPago']);
        $NumDiasPagados= autoformato_impresion($Nomina['NumDiasPagados']);
        $Departamento= autoformato_impresion($Nomina['Departamento']);
        $Banco= autoformato_impresion($Nomina['Banco']);
        $CLABE= autoformato_impresion($Nomina['CLABE']);
        $FechaInicioRelLaboral= autoformato_impresion($Nomina['FechaInicioRelLaboral']);
        $Antiguedad= autoformato_impresion($Nomina['Antiguedad']);
        $Puesto= autoformato_impresion($Nomina['Puesto']);
        $TipoContrato= autoformato_impresion($Nomina['TipoContrato']);
        $TipoJornada= autoformato_impresion($Nomina['TipoJornada']);
        $PeriodicidadPago= autoformato_impresion($Nomina['PeriodicidadPago']);
        $SalarioBaseCotApor= autoformato_impresion($Nomina['SalarioBaseCotApor']);
        $RiesgoPuesto= autoformato_impresion($Nomina['RiesgoPuesto']);
        $SalarioDiarioIntegrado= autoformato_impresion($Nomina['SalarioDiarioIntegrado']);
        $RegistroPatronal= autoformato_impresion($Nomina['RegistroPatronal']);
       
    }
    if($CURP!='')
    {
        $letra=10;
        $nomina_general="
        <div>
        <table width='100%' border=0 class='factura_titulo_ch' >
            <tr>
            <td width='50%' style='font-size:$letra px !important'> 
            Registro Patronal : $RegistroPatronal</td><td style='font-size:$letra px !important'> Fecha Inicio Laboral : $FechaInicioRelLaboral
            </td></tr><tr><td style='font-size:$letra px !important'> Tipo de Regimen : $TipoRegimen</td><td style='font-size:$letra px !important'> Fecha Inicial : $FechaInicialPago
            </td></tr><tr><td style='font-size:$letra px !important'> Numero de Empleado : $NumEmpleado</td><td style='font-size:$letra px !important'> Fecha Final : $FechaFinalPago
            </td></tr><tr><td style='font-size:$letra px !important'> CURP : $CURP</td><td style='font-size:$letra px !important'> Fecha de Pago : $FechaPago
            </td></tr><tr><td style='font-size:$letra px !important'> No. Seguro Social : $NumSeguridadSocial</td><td style='font-size:$letra px !important'> Dias pagados : $NumDiasPagados
            </td></tr><tr><td style='font-size:$letra px !important'> DEPARTAMENTO : $Departamento</td><td style='font-size:$letra px !important'> Antiguedad : $Antiguedad semanas
            </td></tr><tr><td style='font-size:$letra px !important'> PUESTO : $Puesto</td><td style='font-size:$letra px !important'> PERIODICIDAD : $PeriodicidadPago
            </td></tr><tr><td style='font-size:$letra px !important'> BANCO :$Banco</td><td style='font-size:$letra px !important'> SALARIO BASE : $SalarioBaseCotApor
            </td></tr><tr><td style='font-size:$letra px !important'> CLAVE : $CLABE</td><td style='font-size:$letra px !important'> RIESGO : $RiesgoPuesto
            </td></tr><tr><td style='font-size:$letra px !important'> TIPO CONTRATO : $TipoContrato</td><td style='font-size:$letra px !important'> SALARIO DIARIO INTEGRADO: $SalarioDiarioIntegrado
            </td></tr><tr><td style='font-size:$letra px !important'> TIPO JORNADA : $TipoJornada</td><td>
    
            </td></tr>
        </table>
        </div>    
        ";
    }

//NOMINAS PERCEPCIONES
    $NominaPercepciones='';
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina:Percepciones//nomina:Percepcion') as $Percepciones)
    {
        
        $TipoPercepcion= autoformato_impresion($Percepciones['TipoPercepcion']);
        $Clave= autoformato_impresion($Percepciones['Clave']);
        $Concepto= autoformato_impresion($Percepciones['Concepto']);
        $ImporteGravado= autoformato_impresion($Percepciones['ImporteGravado']);
        $ImporteExento= autoformato_impresion($Percepciones['ImporteExento']);
        
        
        $NominaPercepciones.="<tr><td style='font-size:$letra px !important'>$TipoPercepcion</td><td style='font-size:$letra px !important'>$Clave</td><td style='font-size:$letra px !important'>$Concepto</td><td style='font-size:$letra px !important'>$ImporteGravado</td><td style='font-size:$letra px !important'>$ImporteExento</td></tr>";
        

    }
    if(strlen($NominaPercepciones)>10)
    {
        $NominaPercepciones="
        <b>PERCEPCIONES:</b>
            <table width='100%'>
       <tr class='factura_detalles_cabecera'  >
        <td style='font-size:$letra px !important'>TP</td>
        <td style='font-size:$letra px !important'>CLAVE</td>
        <td style='font-size:$letra px !important' >CONCEPTO</td>
        <td style='font-size:$letra px !important'>IMP GRAVADO</td>
        <td style='font-size:$letra px !important'>IMP EXENTO</td>
       </tr>

            $NominaPercepciones
            </table>
        ";
    }


//NOMINAS DEDUCCIONES
    $NominaDeducciones='';
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina:Deducciones//nomina:Deduccion') AS $Deducciones)
    {
        
        $TipoDeduccion= autoformato_impresion($Deducciones['TipoDeduccion']);
        $Clave= autoformato_impresion($Deducciones['Clave']);
        $Concepto= autoformato_impresion($Deducciones['Concepto']);
        $ImporteGravado= autoformato_impresion($Deducciones['ImporteGravado']);
        $ImporteExento= autoformato_impresion($Deducciones['ImporteExento']);
        
        $NominaDeducciones.="<tr><td style='font-size:$letra px !important'>$TipoDeduccion</td><td style='font-size:$letra px !important'>$Clave</td><td style='font-size:$letra px !important'>$Concepto</td><td style='font-size:$letra px !important'>$ImporteGravado</td><td style='font-size:$letra px !important'>$ImporteExento</td></tr>";
        

    }
    if(strlen($NominaDeducciones)>10)
    {
        $NominaDeducciones="
        <b>DEDUCCIONES:</b>
            <table width='100%'>
       <tr class='factura_detalles_cabecera'  >
        <td style='font-size:$letra px !important'>TP</td>
        <td style='font-size:$letra px !important'>CLAVE</td>
        <td style='font-size:$letra px !important'>CONCEPTO</td>
        <td style='font-size:$letra px !important'>IMP GRAVADO</td>
        <td style='font-size:$letra px !important'>IMP EXENTO</td>
       </tr>

            $NominaDeducciones
            </table>
        ";
    }



//NOMINAS HORAS EXTRA
    $NominaHorasExtras='';
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina:HorasExtras//nomina:HorasExtra') AS $HoraExtra)
    {
        
        $Dias= autoformato_impresion($HoraExtra['Dias']);
        $TipoHoras= autoformato_impresion($HoraExtra['TipoHoras']);
        $HorasExtra= autoformato_impresion($HoraExtra['HorasExtra']);
        
        $NominaHorasExtras.="<tr><td style='font-size:$letra px !important'>$Dias</td><td style='font-size:$letra px !important'>$TipoHoras</td><td style='font-size:$letra px !important'>$HorasExtra</td></tr>";

        

    }
    if(strlen($NominaHorasExtras)>10)
    {
        $NominaHorasExtras="
        <b>HORAS EXTRA:</b>
            <table width='100%'>
       <tr class='factura_detalles_cabecera'  >
        <td style='font-size:$letra px !important'>DIAS</td>
        <td style='font-size:$letra px !important'>TIPO</td>
        <td style='font-size:$letra px !important'>HORAS EXTRA</td>
       </tr>

            $NominaHorasExtras
            </table>
        ";
    }



//NOMINAS INCAPACIDADES
    $NominaIncapacidades='';
    foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//nomina:Incapacidades//nomina:Incapacidad') AS $Incapacidad)
    {
        
        $DiasIncapacidad= autoformato_impresion($Incapacidad['DiasIncapacidad']);
        $TipoIncapacidad= autoformato_impresion($Incapacidad['TipoIncapacidad']);
        $Descuento= autoformato_impresion($Incapacidad['Descuento']);
        
        $NominaIncapacidades.="<tr><td style='font-size:$letra px !important'>$DiasIncapacidad</td><td style='font-size:$letra px !important'>$TipoIncapacidad</td><td style='font-size:$letra px !important'>$Descuento</td></tr>";

        

    }
    if(strlen($NominaIncapacidades)>10)
    {
        $NominaIncapacidades="
        <b>INCAPACIDADES:</b>
            <table width='100%'>
       <tr class='factura_detalles_cabecera'  >
        <td style='font-size:$letra px !important'>DIAS</td>
        <td style='font-size:$letra px !important'>TIPO</td>
        <td style='font-size:$letra px !important'>DESCUENTO</td>
       </tr>

            $NominaIncapacidades
            </table>
        ";
    }




$nominas_txt="
<table>
<tr valign='top'>
    <td width='50%' valign='top'>
        $NominaPercepciones
        $NominaHorasExtras
    </td>
    <td width='50%'>
        $NominaDeducciones
        $NominaIncapacidades
    </td>

</tr>
</table>
";
$emisor_municipio2=trim(strtolower($emisor_municipio2));
$emisor_municipio2=str_replace(' ','',$emisor_municipio2);
$emisor_municipio2=str_replace('.','',$emisor_municipio2);
$emisor_municipio2=str_replace(',','',$emisor_municipio2);

$emisor_localidad2=trim(strtolower($emisor_localidad2));
$emisor_localidad2=str_replace(' ','',$emisor_localidad2);
$emisor_localidad2=str_replace('.','',$emisor_localidad2);
$emisor_localidad2=str_replace(',','',$emisor_localidad2);


if($emisor_municipio2==$emisor_localidad2)
{
    $emisor_localidad='';
}
$Emisor="
<div class='factura_emisor factura_cuadro '>
    <div class='factura_titulo_ch'>EMISOR:</div>
    <div class='factura_titulo_empresa'>$emisor_nombre </div>
    <div> RFC: <b>$emisor_rfc</b></div>
    
    $emisor_calle $emisor_noExterior $emisor_noInterior, $emisor_colonia CP:$emisor_CP
    <br/>
    $emisor_municipio $emisor_localidad,
    $emisor_estado,
    $emisor_pais
    
</div>
";
$ciudad_estado="$emisor_municipio $emisor_localidad $emisor_estado,";
if($expedido_municipio==$expedido_localidad)
{
    $expedido_localidad='';
}
$ExpedidoEn='';


$ExpedidoEn="
<div class='factura_expedidoen factura_cuadro_linea'>
    <span class='factura_titulo_ch'>EXPEDIDO EN:</span>
    $expedido_calle $expedido_noExterior $expedido_noInterior, $expedido_colonia 
    $expedido_municipio $expedido_localidad, $expedido_estado, $expedido_pais CP:$expedido_CP
</div>

";


        $idreceptor=$datosfacturas['idreceptor'];
//        $datosreceptor=lee_sql_mash(sql_agenda($idreceptor));
        $Fiscal_Orientacion=$datosreceptor['Fiscal_Orientacion'];

if($receptor_municipio==$receptor_localidad)
{
    $receptor_localidad='';
}

$Receptor="
<div class='factura_receptor factura_cuadro '>
    <div class='factura_titulo_ch'>RECEPTOR:</div>
    <div class='factura_titulo_empresa'>$receptor_nombre  </div>
    RFC: <b> $receptor_rfc </b><br/>
    
    $receptor_calle $receptor_noExterior $receptor_noInterior $Fiscal_Orientacion $receptor_colonia CP:$receptor_CP
    <br/>
    $receptor_municipio  $receptor_localidad,
     $receptor_estado,
     $receptor_pais

</div>
";

$DatosGenerales="
<div class='factura_titulo_serie_folio'>$titulo</div>
<div class='factura_datosgenerales'>

<div class='factura_titulo_serie_folio'>$tipo_factura : $serie$folio</div>

<div> Folio Fiscal : $timbre_uuid  </div>
<div> Numero Certificado CSD : $certificado_no  </div>
<div> Lugar y Fecha: $LugarExpedicion $fecha_expedicion  </div>

</div>
";
//$logo="<div class='logo'><img src='http://192.168.1.111/multifacturas_docs/multifacturas_sdk_desarrollo/$logo'></div>";

if(!file_exists($logo))
{
    $logo="c:/cfdipdf/transparente.gif";
}
else
{
    $conflogo['max']=220;
    if(function_exists('ver_imagen_mash'))
    {
        $logo=ver_imagen_mash($logo,250,0,$conflogo);
    }
    else
    {
        $logo=$logo;
    }    
}


$cabecera="
    <table width='100%'>
        <tr valign='top'>
            <td width='260'><img  width='250px' src='{URL}/$logo'></td>

            <td >$DatosGenerales</td>
        </tr>
    </table>
    $ExpedidoEn
    <table width='100%'>
        <tr valign='top'>
            <td width='50%'>$Emisor</td>
            <td width='50%'>$Receptor</td>
        </tr>
    </table>

";
//$nomina_general
$certificado_key= $datosjson['datoscertificado']['SAT_Llave_PEM'];

//cfd_lee_cadena($sello,$certificado_key,$timbre_noCertificadoSAT);
//echo base64_decode($timbre_selloSAT);

    $cadena_sat="||$timbre_version|$timbre_uuid|$timbre_fecha|$timbre_selloCFD|$timbre_noCertificadoSAT||";

$longitud=95;
$sello = wordwrap($sello,$longitud,'<br>',true);
$timbre_selloSAT = wordwrap($timbre_selloSAT,$longitud,'<br>',true);
$cadena_sat = wordwrap($cadena_sat,$longitud,'<br>',true);
//$sello = wordwrap($sello,$longitud,'<br>',true);


$idsession=$datosfacturas['idtpv_session'];
$idempresa_atendio=$datosfacturas['idempresa_atendio'];
$idcliente=$datosfacturas['idcliente'];
$idempresa=$datosfacturas['idempresa'];

//        $timbre_selloSAT = $sellosat=$tfd['selloSAT'];

$archivo_png=str_replace('.xml','.png',$xml_archivo);
$archivo_png=str_replace('.XML','.PNG',$archivo_png);


if(function_exists('libreria_mash'))
{
    libreria_mash('num2letras');
    
}
else
{

    include_once 'num2letras.php';
}

    switch($Moneda)
    {
        case 'MXN' :  $moneda_txt='PESOS'; break;
        case 'USD' :  $moneda_txt='DOLAR'; break;
        case 'EUR' :  $moneda_txt='EUROS'; break;
        default :  $moneda_txt='PESOS'; break;
    }
    
    $numeroletras=num2letras($total,'  ');    


if(intval($NumCtaPago)>0)
    $NumCtaPago_txt="CUENTA : $NumCtaPago";
$sellos_pie="

<div class='factura_sellos factura_cuadro '>
<table width='100%' border=0>
    <tr valign='top'>
        <td width=200px;>
            <img src='{URL}/$archivo_png'><br/>
            
        </td>
        <td>
            <div class='factura_sellos_txt'>
            CANTIDAD CON LETRA: $numeroletras $moneda_txt ($Moneda)<br>
            <b>METODO PAGO: $metodo_pago | FORMA PAGO: $forma_pago | $NumCtaPago_txt  </b><br/>
                <b>REGIMEN FISCAL : </b>$regimen_fiscal  <b>Fecha Timbrado : </b>$timbre_fecha <br/> 
                <b>SELLO : </b><br/>$sello <br/>
                <b>SELLO SAT : </b><br/>$timbre_selloSAT <br/>

                <b>Numero Certificado SAT : </b> $timbre_noCertificadoSAT <br/>
                <b>Cadena Original</b><br/><br/>
                $cadena_sat 
                <br/>

<b>Este documento es una representación impresa de un CFDI</b> EFECTOS FISCALES AL PAGO 
            </div>
            <br/>
$referencia $barcode_factura 
        </td>
    
    </tr>
</table>

</div>
";
 
$importeneto=(float)$subtotal;
//echo "$importeneto=$subtotal-$descuento";
//mash $importeneto=sprintf('%1.2f',$importeneto);


if(strlen($nota_impresa)>5)
{
    $notas_impresas="
        <div class='factura_sellos factura_cuadro'>
        $nota_impresa
        </div>
    ";    
}


$desc1= $datosfacturas['descuento_adicional_porcentaje'];
$desc2= $datosfacturas['descuento_formapago_porcentaje'];


$importeneto_=number_format((string)$importeneto,2);

if($descuento>0)
{
        $descuento_txt_="
                        <tr>
        
                            <td class='factura_totales'>
                            DESCUENTO $<br>
                            
                            </td>
                            <td class='factura_totales'>
                             $descuento_
                            </td>
                        </tr>
        ";
}

/*


                        <tr>
        
                            <td class='factura_totales'>
                            IMPORTE NETO $
                            </td>
                            <td class='factura_totales'>
                             $importeneto_
                            </td>
                        </tr>

*/




if($CURP!='')
{
//    $retenciones_txt='';
}



$pie="

<table width='100%'>
    <tr>
        <td valign='top' >
            $notas_impresas
        </td>
        <td width='300px' >
            
                    <table width='300px' >
                        <tr >
                            <td class='factura_totales'>
                            IMPORTE $
                            </td>
                            <td class='factura_totales'>
                             $subtotal_
                            </td>
                        </tr>
$descuento_txt_
$iva_txt
                        $retenciones_txt
                        <tr>
        
                            <td class='factura_totales'>
                            TOTAL $
                            </td>
                            <td class='factura_totales'>
                             $total_<br/>$Moneda
                             
                            </td>
                            
                        </tr>
                        
                    </table> 
          
        </td>
    </tr>
</table>

";
$idfactura2=sprintf('%06d',$idfactura);

if($datosfacturas['factura_cancelada']==1)
{
    $cancelado_fecha=$datosfacturas['factura_cancelada_fecha'];
    $cancelado_motivo=$datosfacturas['factura_cancelada_motivo'];
    $cancelado_msg_pac=$datosfacturas['factura_cancelada_pac_msg'];

    $cancelado="
    <div class='factura_cancelada'>
    <h4>FACTURA CANCELADA</h4> <br/>
    FECHA CANCELACION : $cancelado_fecha  <br/>
    MOTIVO : $cancelado_motivo  <br/>
    MENSAJE DEL TIMBRADO : $cancelado_msg_pac  <br/>
    
    </div>
    ";
}

if($datosfacturas['factura_cancelada']==2)
{
    $cancelado_fecha=$datosfacturas['factura_cancelada_fecha'];
    $cancelado_motivo=$datosfacturas['factura_cancelada_motivo'];
    $cancelado_msg_pac=$datosfacturas['factura_cancelada_pac_msg'];

    $cancelado="
    <div class='factura_cancelada'>
    <h4>FACTURA PENDIENTE DE CANCELAR</h4> <br/>
    <h4>NO ESTA INCLUIDO EN EL REPORTE DE VENTAS</h4> <br/>
    FECHA CANCELACION : $cancelado_fecha  <br/>
    MOTIVO : $cancelado_motivo  <br/>
    MENSAJE DEL TIMBRADO : $cancelado_msg_pac  <br/>
    
    </div>
    ";
}


if($certificado_no==20001000000100005867)
{
    $cancelado="
    <div class='factura_cancelada'>
<br/><br/>    
PRUEBA DEL SISTEMA
<br/>
FACTURA NO VALIDA ANTE EL SAT
<br/><br/><br/>    
    </div>
    ";
}

$leyenda="
<div class='factura_leyenda' style='font-size:10px !important;'>
Por este pagare me(nos) obligo(amos) a pagar incondicionalmente el dia ___________________ en esta ciudad de $ciudad_estado.
o en cualquier otra plaza que se me(nos) requiera a la orden de $emisor_nombre
la cantidad de $________________ valor recibido a mi(nuestra) entrega satisfaccion, queda convenida que en caso de mora
el presente pagare causara un interes ____% mensual hasta la liquidacion.
<br><br>
FIRMA CLIENTE : _____________________________ <br/>

</div>
";
if($CURP!='')
{
    $leyenda='';
}

$valor.="
$cabecera
<div class=factura_detalles>
$desgloce
$nominas_txt
$nomina_general
$cancelado
</div>
$pie
$sellos_pie
$barcode_factura
$leyenda 
";

global $masheditor;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' OR count($masheditor)==0) 
    {
        $valor=str_replace('{URL}/','',$valor);
    }

    return $valor;
}
///////////////////////////////////////////////////////////////////////////////
function autoformato_impresion($txt)
{
    //$txt=utf8_decode(utf8_decode($txt));
    $txt=utf8_decode($txt);
    return $txt;
}
///////////////////////////////////////////////////////////////////////////////

function object2array($object)
{
    $return = NULL;
      
    if(is_array($object))
    {
        foreach($object as $key => $value)
            $return[$key] = object2array($value);
    }
    else
    {
        $var = get_object_vars($object);
          
        if($var)
        {
            foreach($var as $key => $value)
                $return[$key] = ($key && !$value) ? NULL : object2array($value);
        }
        else return $object;
    }

    return $return;
} 


function XML2Array ( $xml )
{
    $array = simplexml_load_string ( $xml );
    $newArray = array ( ) ;
    $array = ( array ) $array ;
    foreach ( $array as $key => $value )
    {
        $value = ( array ) $value ;
        $newArray [ $key] = $value [ 0 ] ;
    }
    $newArray = array_map("trim", $newArray);
  return $newArray ;
} 

class simple_xml_extended extends SimpleXMLElement
{
    public    function    Attribute($name)
    {
        foreach($this->Attributes() as $key=>$val)
        {
            if($key == $name)
                return (string)$val;
        }
    }

}

///////////////////////////////////////////////////////////////////////////////
function genera_pdf($idfactura,$ruta_pdf=NULL,$ruta_url=NULL)
{

    $idfactura=intval($idfactura);
    if($idfactura==0 )
    {
        
    }
    else
    {
        if($idfactura>0)
        {
            $sql="
            SELECT 
              multi_facturas.XML
            FROM
              multi_facturas
            WHERE
              (multi_facturas.idfactura = $idfactura)    
            ";
            if(function_exists('lee_sql_mash'))
                list($xml)=lee_sql_mash($sql);
        }
        
    }

    $pdf=str_replace('.xml','.pdf',$xml);
    if($ruta_pdf!=NULL)
        $pdf=$ruta_pdf;
    global $masheditor;
    $urlbase=$masheditor['url'];

//echo debug_mash($masheditor);
    $rfc=$_COOKIE["RFC"];
    $hora=time();
    $md5=md5("mash,$rfc,$hora,");
    $authpdf="$rfc,$hora,$md5";
    $carpeta_instalacion=$masheditor['carpeta_instalacion'];
    if($carpeta_instalacion!='')
        $carpeta_instalacion="$carpeta_instalacion/";
    $url="$urlbase/mt,46,1/idfacturahtml,$idfactura/impresion,si/?authpdf=$authpdf";

    if($ruta_url!=NULL)
    {
        

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
        {
            $url=$ruta_url;
        }
        else
        {
            $url=$ruta_url;
            if(function_exists('formato_url_mash'))
                $url=formato_url_mash($url);
            #$url="$url/?authpdf=$authpdf";        
        } 

    }
unlink("$carpeta_instalacion$pdf");

    $ruta='';
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
    {
        $SO=$_SERVER['PROCESSOR_IDENTIFIER'];
        if(strpos("  $SO",'x86')>0)
        {
            //32bits
            //$ruta='c:\\cfdipdf\\32\\';
            $ruta='c:\\cfdipdf\\htdocs\\32\\';
        }
        else
        {
            //64 bits
            //$ruta='c:\\cfdipdf\\64\\';
            $ruta='c:\\cfdipdf\\htdocs\\32\\';
        }
    }
    if(file_exists("$carpeta_instalacion$pdf")==false)
    {
        $comando=$ruta."wkhtmltopdf  -s A4 -B 1 -T 1 -L 1 -R 1  \"$url\"  \"$carpeta_instalacion$pdf\"    "; //   -B 1 -T 1 -L 1 -R 1 -s A4 &        
    }

//echo $comando;

    $resultado=shell_exec($comando);
    $url=$masheditor['url'];
    $valor= ver_pdf("$url/$pdf");


    return $valor;
    
}
///////////////////////////////////////////////////////////////////////////////

function ver_pdf($pdf)
{   

    $hora=time();
    inicializa_jquery();
    $idrand=rand();
    $html="
    <div id='iddbme_pdf_$idrand'></div>
    <script>
        dbme_muestra_pdf($idrand,'$pdf');
    </script>
    
    ";


    return $html;
}

///////////////////////////////////////////////////////////////////////////////
function html_css($color_marco,$color_marco_texto,$color_texto,$fuente_texto)
{
$css="
<style type='text/css'> 
*{
/*    
    font-family: monospace !important; 
fantasy sans-serif
*/
    font-family: $fuente_texto;

    font-size: 12px !important;
    font-weight: bold !important;
    color: $color_texto;
}

.factura_cuadro{
    margin: 3px !important;
    padding: 3px !important;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
}

.factura_cuadro_linea{
    margin: 3px !important;
    padding: 3px !important;
    border-bottom-width: 1px;
    border-bottom-style: solid;
    
    
}

.factura_emisor{
    text-transform: uppercase !important;
    min-height: 80px;
}

.factura_expedidoen{
    text-transform: uppercase !important;
}
.factura_receptor{
    text-transform: uppercase !important;
    min-height: 80px;
}
.factura_datosgenerales{
    text-transform: uppercase !important;
    font-weight: bold;
    font-size: 14px;
    text-align: right;
}
.factura_sellos{
    word-wrap:break-word;
     
}
.factura_titulo_empresa{
    text-transform: uppercase !important;
    font-weight: bold;
    font-size: 14px !important;
}

.factura_titulo_ch{
    text-transform: uppercase !important;
    font-weight: bold;
    font-size: 13px !important;    
    
}

.factura_cbb{
    display: inline-block;
    width: auto;
    display: inline-block;
    background-color: red;
    
}
.factura_sellos_txt{
    display: inline-block;    
   word-wrap:break-word;
   font-size: 8px !important;
}

.factura_sellos_txt b{
    display: inline-block;    
   word-wrap:break-word;
   font-size: 9px !important;
   text-transform: uppercase;
}


.factura_totales{
    text-align: right !important;
    font-size: 12px !important;
    font-weight: bold !important;
}

.factura_detalles{
    min-height: 100px;
}

.factura_detalles_renglon1{
    font-weight: bold;
    font-style: normal;
}
.factura_detalles_renglon2{
    font-weight: bold;
    font-style: italic;
}
.factura_detalles_cabecera{
    background-color: $color_marco;
    color: $color_marco_texto;
    font-weight: bolder;
}
.factura_detalles_cabecera td{
    background-color: $color_marco;
    color: $color_marco_texto;
}

.factura_titulo_serie_folio{
    font-weight: bold !important;
    font-size: 24px !important;
}

.factura_cancelada{
    position: relative;
    top : -30px;
    margin: 10px;
    margin-left: 100px;
    padding: 5px;
    text-align: center;
    width: 350px;
    font-size: 18px;
    border-style: double;
    border-width: 3px;

    -moz-transform:rotate(-3deg);
    -webkit-transform:rotate(-3deg); 
    -ms-transform:rotate(-3deg);
}
</style> 
";
    
    return $css;
}
///////////////////////////////////////////////////////////////////////////////

?>
