function ctlFacturar($scope, $http, $rootScope) {
    $scope.busqueda = { cliente: "", producto: "" };

    $scope.factura = { config: null, idLugarExpedicion: "1", descuento: 0, tipoComprobante: "1", serie: "1", folio: "", idMetodoPago: "1", idFormaPago: "1", cliente: null, conceptos: [], numeroCtaPago: "" };
    $scope.formasPago = [];
    $scope.metodosPago = [];
    $scope.clientes = []; // busqueda de clientes---
    $scope.unidades = [];
    $scope.series = [];
    $scope.lugaresExpedicion = [];
    $scope.total = 0;
    $scope.subtotal = 0;
    $scope.iva = 0;
    var config_anterior = {};

    $http.get("../administrador/PHP/new/facturacion/generarFactura/fetchData.php", { params: { accion: "init" } }).then(function (resp) {
        $scope.formasPago = resp.data.result0;
        $scope.metodosPago = resp.data.result1;
        $scope.unidades = resp.data.result2;
        $scope.series = resp.data.result3;
        folios = resp.data.result4;
        $scope.lugaresExpedicion = resp.data.result5
        $scope.configs = resp.data.result6;

        $scope.factura.folio = folios[0].folio;
        $scope.factura.serie = $scope.series[0].serie;
        $scope.concepto.unidad = $scope.unidades[0].nombre;
    });

    $http.get("php/fetchData.php?accion=unidades").then(function (res) {
        $scope.unidades = res.data;

    });
    $scope.conceptos = [];
    $scope.unidades = [];
    $scope.concepto = { codigo: "", descripcion: "", unidad: "", precio: 0, cantidad: 0, unidad_nombre:"unidad"};
    $scope.$on("facturar", function (event, data) {
        $scope.total = data;
        $scope.conceptos.push({
            identificador: "N/A",
            descripcion: "Insumos de comida",
            unidad: "0",
            unidad_nombre:"N/A",
            precio: data,
            cantidad: 1
            , importe:data
        });
        data = parseFloat(data);
     
        $scope.subtotal = data;
        $scope.total = data + data * 0.16;
        $scope.iva = $scope.total - $scope.subtotal;
    });
    $scope.eliminarConcepto = function (index) {
        var c = $scope.conceptos[index];
        bootbox.confirm({
            title: "Seguro que deseas eliminar este concepto?",
            message: "Realmente estas seguros de eliminar el concepto '" + c.descripcion+"' ?",
            buttons: {
                cancel: {
                    className: "btn-danger",
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    className:"btn-primary",
                    label: '<i class="fa fa-check"></i> Confirmar'
                }
            },
            callback: function (result) {
                if (result) {

                    $scope.conceptos.splice(index, 1);
                    $scope.subtotal = calcularTotales();
                    $scope.total = $scope.subtotal + ($scope.subtotal * 0.16);
                  
                    $scope.iva = $scope.total - $scope.subtotal;
                    $scope.$apply();
                }
            }

            
        });
    }
    var index_concepto = 0;
    $scope.modConcepto = function (index) {
        $scope.opcion = 1;

        $scope.concepto = $scope.conceptos[index];
        index_concepto = index;

      

    }
    function checkConcepto(c) {
        var success = true;
        for (var i in c) {
            if (i == 'precio') {
                if (c[i] <= 0) {
                    success = false;
                    break;
                }
            }
            if (i == 'cantidad') {
                if (c[i] <= 0) {
                    success = false;
                    break;
                }
            }
            if (c[i] == "" || c[i] == undefined) {
                success = false;
                break;
            }
        }
        return success;
    }
    $scope.opcion = 0;
    function getUnidadById(id) {
        var unidades = $scope.unidades; var len = unidades.length; var i = 0;
        id = id.toString();
        for (; i < len; i++) {
            if (unidades[i]) {
                if (unidades[i].id.toString() == id) {
                    return unidades[i];
                }
            }
        }

    }

    function calcularTotales() {
        var conceptos = $scope.conceptos; var len = conceptos.length; var i = 0;
        var total = 0;
        for (; i < len; i++){
            total += conceptos[i].importe;
        }
        return total;
    }

    $scope.submitConcepto = function () {
        var c = $scope.concepto;
        if (checkConcepto(c)) {
            switch ($scope.opcion) {
                case 0:
                    c.importe = c.precio * c.cantidad;
                    c.unidad_nombre = getUnidadById(c.unidad).nombre;
                    $scope.conceptos.push(c);
                    $scope.concepto = { identificador: "", descripcion: "", unidad: "", precio: 0, cantidad: 0 };
                    $scope.subtotal += c.importe;
                    $scope.total = $scope.subtotal + ($scope.subtotal * 0.16);
                    $scope.iva = $scope.total - $scope.subtotal;

                    break;
                case 1:
                    $scope.opcion = 0;
                    var unidad = getUnidadById(c.unidad);
                    $scope.concepto.unidad_nombre = unidad.nombre;
                    $scope.concepto.importe = $scope.concepto.precio * $scope.concepto.cantidad;
                    $scope.conceptos[index_concepto] = $scope.concepto;
                    $scope.concepto = { identificador: "", descripcion: "", unidad: "", precio: 0, cantidad: 0 };
                    $scope.subtotal = calcularTotales();
                    $scope.total = $scope.subtotal + ($scope.subtotal * 0.16);
                    $scope.iva = $scope.total - $scope.subtotal;
                    break;

            }


        } else {
            toastr.info("Datos de concepto invalidos");
        }

    }
    $scope.undoConcepto = function () {
        $scope.concepto = { identificador: "", descripcion: "", unidad: "", precio: 0, cantidad: 0, unidad_nombre: "unidad" };


    }
    $scope.clientes = [];
    $scope.buscarCliente = function () {
        $http.get("../administrador/PHP/new/facturacion/generarFactura/fetchData.php", { params: { accion: "buscarCliente", q: $scope.busqueda.cliente } }).then(function (resp) {
            $scope.clientes = resp.data;
        });
    }
    var cliente_anterior = {};
    $scope.selectCliente = function (index) {
        cliente_anterior.active = false;
        var cliente = $scope.clientes[index];
        $scope.factura.cliente = cliente;
        cliente.active = true;
        cliente_anterior = cliente;
    }
    $scope.asignarFolio = function () {
        var idSerie = $scope.factura.serie;
        var len = folios.length; var i = 0;
        for (; i < len; i++) {
            var f = folios[i];
            if (idSerie == f.idSerie) {
                $scope.factura.folio = f.folio;
                break;
            }
        }

    }
    $scope.configSelected = function (index) {
        var config = $scope.configs[index];
        $scope.factura.config = config.id;
        config_anterior.selected = false;
        config.selected = true;
        config_anterior = config;
    }
    function validarFactura() {
        var errores = [];
        var conceptos = $scope.conceptos;
        var factura = $scope.factura;

        if (conceptos == null || conceptos.length == 0) {
            errores.push("Debes de especificar los conceptos de la factura");
        }
        if (factura.cliente == null) {
            errores.push("Debes de seleccionar un cliente a facturar");
        }
        var subtotal = $scope.subtotal;
        if (subtotal <= 0) {
            errores.push("Totales de la factura invalidos");
        }
        if (factura.config == null) {
            errores.push("Debes de seleccionar una configuracion");
        }

        return errores;

    }
    function buscarMetodosPago(id) {
        var formas = $scope.metodosPago; var len = formas.length; var i = 0;
        for (; i < len; i++) {
            var f = formas[i];
            if (f.id == id) {
                return f;
            }
        }

    }

    function obtenerIdSerie(serie) {
        var series = $scope.series; var len = series.length; var i = 0;
        for (; i < len; i++) {
            if (serie == series[i].serie) {
                return series[i].id;
            }
        }
    }
    function buscarFormasPago(id) {
        var formas = $scope.formasPago; var len = formas.length; var i = 0;
        for (; i < len; i++) {
            var f = formas[i];
            if (f.id == id) {
                return f;
            }
        }

    }
    $scope.facturar = function () {
        var errores = validarFactura();
        var factura = $scope.factura;
        if (errores.length == 0) {
            var postParams = {
                cliente: factura.cliente,
                conceptos: $scope.conceptos,
                idEmisor: 1,
                idConfig: factura.config,
                idLugarExpedicion: factura.idLugarExpedicion,
                serie: factura.serie,
                folio: factura.folio,
                idMetodoPago: { nombre: buscarMetodosPago(factura.idMetodoPago), id: factura.idMetodoPago },
                idFormaPago: { nombre: buscarFormasPago(factura.idFormaPago), id: factura.idFormaPago },
                tipo_comprobante: factura.tipoComprobante,
                numeroCtaPago: factura.numeroCtaPago,
                descuento: factura.descuento,
                idSerie: obtenerIdSerie(factura.serie)

            };

            $http.post("../administrador/Facturacion/facturarCliente.php", postParams).then(function (resp) {
                console.log(resp.data);
                if (resp.data.exito) {

                    toastr.success("Se ha generado factura con exito");
                    $("#modalFacturar").modal("hide");
                } else {
                    toastr.error("Upsss... algo ha salido mal");
                }

            });

        } else
        {
            toastr.info("Errores:<br>" + errores.join("<br>"));


        }
    }
}