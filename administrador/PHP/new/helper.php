<?php
function OpenCompleteXMLFile($FileName, $Encoding) // [$Encoding := true JSON] , [$Encoding := false PHP ARRAY]
{


    $file = file_get_contents($FileName);
    $file = str_replace('cfdi:', '', $file);
    $file = str_replace('tfd:', '', $file);

    $XmlDoc = simplexml_load_string($file) or die("Error: Cannot create object");

    $Factura = array(
    "Fecha"=>(string)$XmlDoc["fecha"],
    "Folio"=>(int)$XmlDoc["folio"],
    "Moneda"=>(string)$XmlDoc["Moneda"],
    "Serie"=>(string)$XmlDoc["serie"],
    "TipoCambio"=>(float)$XmlDoc["TipoCambio"],
    "FormaDePago"=>(string)$XmlDoc["formaDePago"],
    "MetodoDePago"=>(string)$XmlDoc["metodoDePago"],
    "Subtotal"=>(float)$XmlDoc["subTotal"],
    "Total"=>(float)$XmlDoc["total"],
    "TipoDeComprobante"=>(string)$XmlDoc["tipoDeComprobante"],
    "Descuento"=>(float)$XmlDoc["descuento"],
    "LugarExpedicion"=>(string)$XmlDoc["LugarExpedicion"],
    "FechaCertificacion"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["FechaTimbrado"],
    "Moneda"=>(string)$XmlDoc["Moneda"],
    "TipoCambio"=>(float)$XmlDoc["TipoCambio"],
    "NumeroCertificado"=>(string)$XmlDoc["noCertificado"],
    "SelloDigitalCFDI"=>(string)$XmlDoc["sello"],
    "SelloDigitalSAT"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["selloSAT"],
    "FolioSAT_UUID"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["UUID"],
    "NumeroCertificadoSAT"=>(string)$XmlDoc->Complemento->TimbreFiscalDigital["noCertificadoSAT"],
    "EmisorDomicilioCalle"=>(string)$XmlDoc->Emisor->DomicilioFiscal["calle"],
    "EmisorDomicilioNoExterior"=>(string)$XmlDoc->Emisor->DomicilioFiscal["noExterior"],
    "EmisorDomicilioColonia"=>(string)$XmlDoc->Emisor->DomicilioFiscal["colonia"],
    "EmisorDomicilioMunicipio"=>(string)$XmlDoc->Emisor->DomicilioFiscal["municipio"],
    "EmisorDomicilioEstado"=>(string)$XmlDoc->Emisor->DomicilioFiscal["estado"],
    "EmisorDomicilioPais"=>(string)$XmlDoc->Emisor->DomicilioFiscal["pais"],
    "EmisorDomicilioCP"=>(string)$XmlDoc->Emisor->DomicilioFiscal["codigoPostal"],
    "EmisorDatosRFC"=>(string)$XmlDoc->Emisor["rfc"],
    "EmisorDatosNombre"=>(string)$XmlDoc->Emisor["nombre"],
    "EmisorRegimen"=>(string)$XmlDoc->Emisor->RegimenFiscal["Regimen"],
    "ReceptorDatosCalle"=>(string)$XmlDoc->Receptor->Domicilio["calle"],
    "ReceptorDatosCP"=>(string)$XmlDoc->Receptor->Domicilio["codigoPostal"],
    "ReceptorDatosColonia"=>(string)$XmlDoc->Receptor->Domicilio["colonia"],
    "ReceptorDatosEstado"=>(string)$XmlDoc->Receptor->Domicilio["estado"],
    "ReceptorDatosLocalidad"=>(string)$XmlDoc->Receptor->Domicilio["localidad"],
    "ReceptorDatosCP"=>(string)$XmlDoc->Receptor->Domicilio["codigoPostal"],
    "ReceptorDatosMunicipio"=>(string)$XmlDoc->Receptor->Domicilio["municipio"],
    "ReceptorDatosNoExterior"=>(int)$XmlDoc->Receptor->Domicilio["noExterior"],
    "ReceptorDatosNoInterior"=>(string)$XmlDoc->Receptor->Domicilio["noInterior"],
    "ReceptorDatosPais"=>(string)$XmlDoc->Receptor->Domicilio["pais"],
    "ReceptorRFC"=>(string)$XmlDoc->Receptor["rfc"],
    "ReceptorNombre"=>(string)$XmlDoc->Receptor["nombre"]
     );




    $QtyConceptos = sizeof($XmlDoc->Conceptos[0]->Concepto);

    $Conceptos = array();
    for($i = 0; $i < $QtyConceptos; $i++)
    {
        array_push($Conceptos,
        array(
        "Cantidad" => (int)$XmlDoc->Conceptos->Concepto[$i]["cantidad"],
        "Descripcion" => (string)$XmlDoc->Conceptos->Concepto[$i]["descripcion"],
        "Unidad" => (string)$XmlDoc->Conceptos->Concepto[$i]["unidad"],
        "ValorUnitario" => (float)$XmlDoc->Conceptos->Concepto[$i]["valorUnitario"],
        "Importe" => (float)$XmlDoc->Conceptos->Concepto[$i]["importe"],
        "Codigo" =>(string)$XmlDoc->Conceptos->Concepto[$i]["noIdentificacion"]
        ));
    }
    $Factura["Conceptos"] = $Conceptos;

    if($Encoding)
        return '{"Factura":'.json_encode($Factura).'}';

    else
        return $Factura;
}



?>