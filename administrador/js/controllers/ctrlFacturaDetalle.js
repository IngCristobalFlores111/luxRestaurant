function ctrlFacturaDetalle($scope, $http) {
    var idFactura = getParameterByName('id');
    if (idFactura == null) {
        location.href = "index-ng.html#!/facturacion";
    }
    $scope.tab = 1;
    $scope.setTab = function (tab) {
        $scope.tab = tab;
        if (tab == 2) {
            $scope.$broadcast("cargarContactos", { idCliente: $scope.factura.cliente.idCliente, folio: $scope.factura.factura.folio });
        }

    }
    $scope.factura = { factura: {}, cliente: {} };
    $scope.conceptos = [];
    $scope.totales = { subtotal: 0, iva: 0, total: 0,descuento:0 };
    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "facturaDetalle", idFactura: idFactura } }).then(function (resp) {
        if (resp.data == '0') {
            location.href = "index-ng.html#!/facturacion";
        } else {
            console.log(resp.data.xml);
            $scope.factura.factura = resp.data.factura;
            $scope.factura.moneda = resp.data.xml.Moneda;
            $scope.factura.tc = resp.data.xml.TipoCambio;
            $scope.factura.tipoComprobante = resp.data.xml.TipoDeComprobante;

            $scope.factura.cliente = resp.data.cliente;
            $scope.conceptos = resp.data.xml.Conceptos;
            var subtotal = resp.data.xml.Subtotal;
            var descuento = resp.data.xml.Descuento;
            var iva =subtotal*0.16;
            var total = subtotal+iva;
            $scope.totales = { subtotal: subtotal,descuento:descuento, iva: iva, total: total };
        }
    });

    $scope.abrirPdf = function () {
        var file = "cfdi_" + $scope.factura.factura.folio + ".pdf";
        window.open("Facturacion/timbrados/" + file);
    }
    $scope.abrirXML = function () {
        var file = "cfdi_" + $scope.factura.factura.folio + ".xml";
        window.open("Facturacion/timbrados/" + file);
    }
    $scope.abrirPNG = function () {
        var file = "cfdi_" + $scope.factura.factura.folio + ".png";
        window.open("Facturacion/timbrados/" + file);
    }

    $scope.cancelarFactura = function () {
        bootbox.confirm({
            title: "Seguro que deseas cancelar la factura de Folio "+$scope.factura.factura.folio+"?",
            message: "Realmente deseas cancelar esta factura? el proceso no es reversible",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirmar'
                }
            },
            callback: function (result) {
                if (result) {
                    toastr.info("Cancelando Factura...", "Trabajando...");
                    var xmlCancelar = "cfdi_" + $scope.factura.factura.folio + ".xml";
                    $http.post("Facturacion/cancelarFactura.php", { idFactura:$scope.factura.factura.id, xmlCancelar: xmlCancelar }).then(function (resp) {
                        if (resp.data.codigo_mf_numero != '0') {
                            toastr.warning("Hubo un error al cancelar la factura,intentalo mas tarde", "Error");
                        } else {
                            toastr.success("Factura Cacelada exitosamente", "Factura Cancelada");
                            $scope.factura.factura.estatus = 0;
                        }
                         
                    });

                }
            }
        });
    }

}