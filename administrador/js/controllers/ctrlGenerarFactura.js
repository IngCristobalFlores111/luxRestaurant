function ctrlGenerarFactura($scope, $http) {

    $scope.busqueda = { cliente: "",producto:"" };
    $scope.factura = { config:null, idLugarExpedicion: "1", descuento: 0, tipoComprobante: "1", serie: "1", folio: "", idMetodoPago: "1", idFormaPago: "1", cliente: null, conceptos: [], numeroCtaPago: "" };
    $scope.formasPago = [];
    $scope.metodosPago = [];
    $scope.clientes = []; // busqueda de clientes---
    $scope.productos = []; // busqueda de productos---
    //$scope.conceptos = []; // conceptos de la factura..
    $scope.conceptos_params = { cantidad: 0 };
    $scope.unidades = [];
    $scope.series = [];
    $scope.totales = { subtotal: 0, iva: 0, total: 0 };
    $scope.concepto = {nombre:"",codigo:"",descripcion:"",unidad:"0",cantidad:0,precio:0};
    var folios = []; // folios disponibles
    $scope.lugaresExpedicion = [];
    $scope.configs = [];
    $http.get("PHP/new/facturacion/generarFactura/fetchData.php", { params: { accion: "init" } }).then(function (resp) {
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
    var config_anterior = {};
    $scope.configSelected = function (index) {
        var config = $scope.configs[index];
        $scope.factura.config = config.id;
        config_anterior.selected = false;
        config.selected = true;
        config_anterior = config;
    }
    $scope.buscarCliente = function () {
        $http.get("PHP/new/facturacion/generarFactura/fetchData.php", { params: { accion: "buscarCliente", q: $scope.busqueda.cliente } }).then(function (resp) {
            $scope.clientes = resp.data;
        });
    }
    $scope.selectCliente = function (index) {
        var cliente = $scope.clientes[index];
        $scope.factura.cliente = cliente;
        $scope.clientes = [];
    }
    $scope.buscarProducto = function () {
        $http.get("PHP/new/facturacion/generarFactura/fetchData.php", { params: { accion: "buscarProducto", q: $scope.busqueda.producto } }).then(function (resp) {
            $scope.productos = resp.data;
        });

    }

    $scope.selectProducto = function (index) {
        var producto = $scope.productos[index];
        var cantidad = $scope.conceptos_params.cantidad;
        if (cantidad <= 0) {
            toastr.info("Cantidad tiene que ser mayor a 0", "Cantiad invalida");
        } else {
            var index_conceptos = $scope.factura.conceptos.indexOf(producto);
            if (index_conceptos != -1) {
                var concepto = $scope.factura.conceptos[index_conceptos];
                var importe_anterior = concepto.importe;
                var cantidad_anterior = concepto.cantidad;
                cantidad += cantidad_anterior;
                concepto.precio = parseFloat(concepto.precio);
                concepto.cantidad = cantidad;
                concepto.importe = (concepto.precio) * concepto.cantidad;
                $scope.totales.subtotal += (concepto.importe - importe_anterior);
                $scope.totales.iva = $scope.totales.subtotal * 0.16;
                $scope.totales.total = $scope.totales.iva + $scope.totales.subtotal;

            } else {
                var importe = cantidad * producto.precio;
                producto.importe = importe;
                producto.cantidad = parseFloat(cantidad);
                $scope.totales.subtotal += importe;
                $scope.totales.iva = $scope.totales.subtotal * 0.16;
                $scope.totales.total = $scope.totales.iva + $scope.totales.subtotal;

                $scope.factura.conceptos.push(producto);
            }
        }
    }
    
    function validarConcepto(concepto) {
        var errores = [];
        for (var i in concepto) {
            if (concepto[i] == "") {
                errores.push(i + " No puede estar vacio");
            }
        }
        if (concepto.precio <= 0) {
            errores.push("precio tiene que ser mayor a 0");
        }
        if (concepto.cantidad <= 0) {
            errores.push("cantidad tiene que ser mayor a 0");
        }
        return errores;
    }
    $scope.agregarConcepto = function () {
        var concepto = $scope.concepto;
        var errores = validarConcepto(concepto);
        if (errores.length > 0) {
            toastr.info(errores.join("<br>"), "Error en formulario");
        } else {
            var importe = concepto.cantidad * concepto.precio;
            concepto.importe = importe;
            $scope.factura.conceptos.push(concepto);
            $scope.concepto = { nombre: "", codigo: "", descripcion: "", unidad: "0", cantidad: 0, precio: 0 };
            $scope.totales.subtotal += importe;
            $scope.totales.iva = $scope.totales.subtotal * 0.16;
            $scope.totales.total = $scope.totales.iva + $scope.totales.subtotal;
        }

    }

    $scope.eliminarConcepto = function (index) {
        var concepto = $scope.factura.conceptos[index];
        var importe = concepto.importe;
        $scope.totales.subtotal -= importe;
        $scope.totales.iva = $scope.totales.subtotal * 0.16;
        $scope.totales.total = $scope.totales.iva + $scope.totales.subtotal;

        $scope.factura.conceptos.splice(index, 1);
       
    }

    $scope.calularImporte = function (concepto) {

        if (isNaN(concepto.cantidad) || isNaN(concepto.precio)) {
            toastr.info("Solo valores numericos positivos", "Introduce valores validos");
        } else {
            var importe_anterior = concepto.importe;
            concepto.importe = concepto.cantidad * concepto.precio;
            $scope.totales.subtotal -= importe_anterior;
            $scope.totales.subtotal += concepto.importe;
            $scope.totales.iva = $scope.totales.subtotal * 0.16;
            $scope.totales.total = $scope.totales.subtotal + $scope.totales.iva;
        }
    }

    function validarFactura(factura) {
        var errores = [];
        if (factura.conceptos == null || factura.conceptos.length == 0) {
            errores.push("Debes de especificar los conceptos de la factura");
        }
        if (factura.cliente == null) {
            errores.push("Debes de seleccionar un cliente a facturar");
        }
        var subtotal = $scope.totales.subtotal;
        if (subtotal <= 0) {
            errores.push("Totales de la factura invalidos");
        }
        if (factura.config == null) {
            errores.push("Debes de seleccionar una configuracion");
        }

        return errores;

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
    function buscarMetodosPago(id) {
        var formas = $scope.metodosPago; var len = formas.length; var i = 0;
        for (; i < len; i++) {
            var f = formas[i];
            if (f.id == id) {
                return f;
            }
        }

    }

    function obtenerIdSerie(serie){
        var series = $scope.series; var len = series.length; var i = 0;
        for(;i<len;i++){
            if(serie==series[i].serie){
                return series[i].id;
            }
        }
    }

    $scope.facturar = function () {
        var factura = $scope.factura;
        var errores = validarFactura(factura);
        if (errores.length > 0) {
            toastr.info(errores.join("<br>"), "Verificar los datos");
        } else {
            var postParams = {
                cliente: factura.cliente,
                conceptos: factura.conceptos,
                idEmisor: 1,
                idConfig: factura.config,
                idLugarExpedicion: factura.idLugarExpedicion,
                serie: factura.serie,
                folio: factura.folio,
                idMetodoPago: {nombre:buscarMetodosPago(factura.idMetodoPago),id:factura.idMetodoPago},
                idFormaPago: { nombre: buscarFormasPago(factura.idFormaPago), id: factura.idFormaPago },
                tipo_comprobante: factura.tipoComprobante,
                numeroCtaPago: factura.numeroCtaPago,
                descuento: factura.descuento,
                idSerie: obtenerIdSerie(factura.serie)

            };
            toastr.info("Timbrando Factura...", "Se esta timbrando tu factura");

            $http.post("Facturacion/facturarCliente.php", postParams).then(function (resp) {
                if (resp.data.exito) {
                    toastr.options.onHidden = function () { location.href = "index-ng.html#!/factura?id=" + resp.data.idFactura };

                    toastr.success("Se ha generado la factura existosamente", "Factrua timbrada");

                } else {
                    toastr.error(resp.data.errores, "Upss... ha ocurrido un error");
                }
                $scope.factura = { config:null, idLugarExpedicion: "1", descuento: 0, tipoComprobante: "1", serie: "1", folio: "", idMetodoPago: "1", idFormaPago: "1", cliente: $scope.factura.cliente, conceptos: [], numeroCtaPago: "" };
                $scope.totales = { subtotal: 0, iva: 0, total: 0 };
                config_anterior.selected = false;

            });




            }



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

    var descuento_anterior = 0;

    $scope.aplicarDescuento = function () {
        var descuento = $scope.factura.descuento;
        if (descuento <= 0) {
            toastr.info("Ingresa un descuento valido", "Descuento invalido");
        } else {

            var subtotal = $scope.totales.subtotal;
            subtotal += descuento_anterior;
            subtotal -= descuento;
            $scope.totales = { subtotal: subtotal, iva: subtotal * 0.16, total: subtotal + (subtotal * 0.16) };
            descuento_anterior = descuento;
        }
    }
    $scope.quitarDescuento = function () {
        var descuento = $scope.factura.descuento;
        if (descuento > 0) {
            var subtotal = $scope.totales.subtotal;
            subtotal += descuento;
            $scope.totales = { subtotal: subtotal, iva: subtotal * 0.16, total: subtotal + (subtotal * 0.16) };
            $scope.factura.descuento = 0;
        }
    }

}