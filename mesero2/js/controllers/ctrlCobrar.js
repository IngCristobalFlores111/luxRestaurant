function ctrlCobrar($scope,$rootScope, $http,$timeout,promosService,printService) {

$scope.metodosPago = [];
$http.get("php/fetchData.php",{params:{accion:"metodosPago"}}).then(function(resp){

    $scope.metodosPago = resp.data;    

});

    $scope.goFacturar = function () {
        $("#modalCobrar").modal("hide");
        $("#modalFacturar").modal("show");
        $rootScope.$broadcast("facturar", obtenerTotal());
    }
    $scope.show = { pagarTodo: false, secondStepMontos: false, firstStep: true, cuentas: false, secondStep: false, montos: false };
    $scope.abrirDividirCuentas = function () {
        $scope.show.cuentas = true;
        $scope.show.firstStep = false;
    }
    var count_cuentas = 0;
    $scope.cuentas = [];
    $scope.platillos = [];
    $scope.indexCuenta = 0;
    $scope.mesa = {};
    var anterior = {};
    
    $scope.agregarCuenta = function () {
        count_cuentas++;
        $scope.cuentas.push({id:count_cuentas});

    }
    function inactiveCuentas() {
      var len = $scope.cuentas.length; var i = 0;
        for (; i < len; i++) {
            $scope.cuentas[i].active = false;
        }
    }
    $scope.activarCuenta = function (index) {
       // anterior.active = false;
        inactiveCuentas();
        $scope.cuentas[index].active = true;
        //anterior = $scope.cuentas[index];
        $scope.indexCuenta = index;
        if (!$scope.cuentas[index].platillos) {
            $scope.cuentas[index].platillos = [];
        }

    }
    $scope.$on("cobrar", function (event, data) {
        $scope.platillos = angular.copy(data.platillos);
        $scope.mesa = angular.copy(data.mesa);
    });
    function buscarIdPedidoPlatillo(id){
        var platillos = $scope.cuentas[$scope.indexCuenta].platillos; var len = platillos.length; var i = 0;
        for (; i < len; i++) {
            var p = platillos[i];
            if (p.idPedidoPlatillo == id) {
                p.index = i;
                return p;
            }
        }
        return null;
    }
    $scope.agregarAcuenta = function (index) {
        var platillo = angular.copy($scope.platillos[index]);
        if (!$scope.cuentas[$scope.indexCuenta]) {
            toastr.info("Debes de selccionar una cuenta antes");
        } else {
            var index_cuenta = $scope.indexCuenta;
            var platillo_existente = buscarIdPedidoPlatillo(platillo.idPedidoPlatillo);
            if (platillo_existente!=null) {
                    $scope.cuentas[index_cuenta].platillos[platillo_existente.index].cantidad++;
                    $scope.platillos[index].cantidad--;
                
                if ($scope.platillos[index].cantidad == 0) {
                    $scope.platillos.splice(index, 1);
                }
                    var item = $scope.cuentas[index_cuenta].platillos[platillo_existente.index];
                    promosService.obtenerDescuentoItem(item.id,item.cantidad,item.precio).then(function(resp){
                        var descuento = resp.data.descuento;
                        if(descuento>0){
                        $scope.cuentas[index_cuenta].descuento = resp.data.descuento;
                        $scope.cuentas[index_cuenta].total -=descuento;
                    }else{
                        $scope.cuentas[index_cuenta].descuento = 0;
                    }
                    });

            } else {
                $scope.platillos[index].cantidad--;
                if ($scope.platillos[index].cantidad == 0) {
                    $scope.platillos.splice(index, 1);

                }
                platillo.cantidad = 1;
                
                promosService.obtenerDescuentoItem(platillo.id,platillo.cantidad,platillo.precio).then(function(resp){
                    var descuento = resp.data.descuento;
                    if(descuento>0){
                    $scope.cuentas[index_cuenta].descuento = resp.data.descuento;
                    $scope.cuentas[index_cuenta].total -=descuento;
                }else{
                    $scope.cuentas[index_cuenta].descuento = 0;
                    
                }
                });
                
                $scope.cuentas[index_cuenta].platillos.push(platillo);

            }
            actualizarTotales();

        }
    }

    function buscarPlatillo(id) {
        var platillos = angular.copy($scope.platillos); var i = 0; var len = platillos.length;
        for (; i < len; i++) {
            var p = platillos[i];
            if (p.idPedidoPlatillo == id) {
                p.index = i;
                return p;
            }

        }
        return null;
    }
    $scope.regresarPlatillo = function (index) {
        var platillo = angular.copy($scope.cuentas[$scope.indexCuenta].platillos[index]);
        var platillo_existente = buscarPlatillo(platillo.idPedidoPlatillo);
        if (platillo_existente != null) {
            $scope.platillos[platillo_existente.index].cantidad++;
        
        } else {
            platillo.cantidad = 1;
            $scope.platillos.push(platillo);

        }
        $scope.cuentas[$scope.indexCuenta].platillos[index].cantidad--;
        if ($scope.cuentas[$scope.indexCuenta].platillos[index].cantidad == 0) {
            $scope.cuentas[$scope.indexCuenta].platillos.splice(index, 1);

        }else{
        var item = $scope.cuentas[$scope.indexCuenta].platillos[index];
        promosService.obtenerDescuentoItem(item.id,item.cantidad,item.precio).then(function(resp){
            var descuento = resp.data.descuento;
            if(descuento>0){
            $scope.cuentas[$scope.indexCuenta].descuento = resp.data.descuento;
            $scope.cuentas[$scope.indexCuenta].total -=descuento;
        }
        });
    }
        actualizarTotales();
    }
   
    function actualizarTotales() {
        var index_cuenta = $scope.indexCuenta;
        var platillos = $scope.cuentas[index_cuenta].platillos; var len = platillos.length; var i = 0;
        var total = 0;
        for (; i < len; i++) {
            var p = platillos[i];
            total += p.cantidad * p.precio;

        }
        var subtotal = total / 1.16;
        var iva = total - subtotal;
        $scope.cuentas[index_cuenta].subtotal = subtotal;
        $scope.cuentas[index_cuenta].iva = iva;
        $scope.cuentas[index_cuenta].total = total;


    }


    $scope.regresarPaso1 = function () {
        $scope.show.firstStep = true;
        $scope.show.secondStep = false;
        $scope.show.cuentas = false;
        $scope.show.montos = false;
        $scope.show.pagarTodo = false;
    }
    $scope.regresarAcuentas = function () {
        $scope.show.firstStep = false;
        $scope.show.secondStep = false;
        $scope.show.cuentas = true;
    }

    function limpiarCuentas() {
        var cuentas = $scope.cuentas; var len = cuentas.length; var i = 0;
        for (; i < len; i++) {
            if (!cuentas[i].subtotal) {
                $scope.cuentas.splice(i, 1);
            }
        }
    }
    $scope.abrirPago = function () {
        $scope.show.firstStep = false;
        $scope.show.secondStep = true;
        $scope.show.cuentas = false;
        limpiarCuentas();
        
        $scope.indexCuenta = 0;

    }

    var cuentaAnterior = {};
    $scope.selectCuentaPagar = function (index) {
        inactiveCuentas();
        $scope.cuentas[index].active = true;
        $scope.indexCuenta = index;
    }

    $scope.pago = { recibido: 0, cambio:0,metodo_pago:"0" }

    $scope.obtenerCambio = function () {
        var total = $scope.cuentas[$scope.indexCuenta].total;
        var recibido = $scope.pago.recibido;
        var cambio = total - recibido;
      //  if (cambio < 0) { cambio = 0;}
        $scope.pago.cambio = Math.abs(cambio);

    }
    var total = 0;
    $scope.cuentasPagadas = [];
    $scope.abrirTicket = function(c){
        window.open("../administrador/tickets/ticket_"+c+".pdf");
        
    }
    function pagarCuenta() {
        var cuenta = $scope.cuentas[$scope.indexCuenta];

        $http.post("php/accionMesero.php?accion=pagarCuenta", { mesa:$scope.mesa.idmesa,descuento:cuenta.descuento,platillos:cuenta.platillos,metodoPago:$scope.pago.metodo_pago,total: cuenta.total, idPedido: $scope.mesa.idpedido }).then(function (resp) {
            if (resp.data.exito) {
                printService.imprimirCuentaCompleta(resp.data.idCuenta,"0").then(function(resp){
                    if(!resp.data.exito){
                     console.log(resp.data);
                    }
                });
                $scope.cuentasPagadas.push(resp.data.idCuenta);
                total += cuenta.total;
                toastr.success("Se ha pagado la cuenta con exito");
                //cuenta.pagado = true;
                $scope.cuentas.splice($scope.indexCuenta, 1);
                var nuevo = $scope.indexCuenta +1;
                if ($scope.cuentas[nuevo]) {
                    $scope.indexCuenta = nuevo;
                    $scope.cuentas[nuevo].active = true;

                } else {
                     nuevo = $scope.indexCuenta - 1;
                     if ($scope.cuentas[nuevo]) {
                         $scope.indexCuenta = nuevo;
                         $scope.cuentas[nuevo].active = true;
                     }

                }
            } else {
                toastr.error("Upss... no se ha podido agregar pago, intentalo mas tarde");
            }

        });
    }
    $scope.pagarCuenta = function () {
        if ($scope.cuentas[$scope.indexCuenta].total<=$scope.pago.recibido){
            pagarCuenta();

        } else {
            toastr.info("Cantidad recibida no cubre el monto de la cuenta");
        }
    }

    $scope.terminar = function () {
        $http.post("php/accionMesero.php?accion=terminarCuenta", { idPedido: $scope.mesa.idpedido, idMesa: $scope.mesa.idmesa }).then(function (resp) {
            if (resp.data.exito) {
                toastr.success("Se ha terminado la cuenta exitosamente");
               location.href = "index.html";
            } else {
                toastr.error("Upsss... algo salio mal, intentalo mas tarde");
            }

        });
    }
    $scope.totalPagar = 0;
   

    function obtenerTotal() {
        var platillos = $scope.platillos; var len = platillos.length; var i = 0;
        var total = 0;
        for (; i < len; i++) {
            var p = platillos[i];
            total += p.precio * p.cantidad;
        }
        return total;

    }
    $scope.abrirPorMonto = function () {
        promosService.obtenerTotalCuenta($scope.mesa.idpedido).then(function(resp){
            var data = resp.data;
            $scope.totalPagar = data.total;    
            $scope.descuento = data.descuento;        

        });
        $scope.show.montos = true;
        $scope.show.secondStep = false;
        $scope.show.cuentas = false;
        $scope.show.firstStep = false;

    }
    var count_montos = 1;

    $scope.montos = [{ id: count_montos, recibido: 0, monto: 0, cambio: 0 }];

   
    $scope.agregarMonto = function () {
        count_montos++;
        $scope.montos.push({ id: count_montos, recibido: 0, monto: 0, cambio: 0 });
    }
    $scope.pagoCubierto = false;
    function verificarMontos() {
        var montos = $scope.montos; var len = montos.length; var i = 0;
        var montosTotal = 0;
        for (; i < len; i++) {
            var m = montos[i];
            if(m!=null)
            montosTotal += m.monto;

        }

        if (montosTotal >= $scope.totalPagar) {
          
            $scope.pagoCubierto = true;
        } else {
            $scope.pagoCubierto = false;

        }
        return ($scope.totalPagar-montosTotal);
    }

    $scope.montoRecibido = function (index) {
       
        var monto = $scope.montos[index];
        var cambio = monto.recibido - monto.monto;

        if (cambio > 0) {
            monto.cambio = cambio;
        }
        verificarMontos();
    }
    var faltante = 0;
    $scope.montoPago = function (index) {
        var monto = $scope.montos[index];

        var cambio = monto.recibido - monto.monto;
        if (cambio > 0) {
            monto.cambio = cambio;
        }
        faltante = verificarMontos();
        if (monto.monto >= faltante && (faltante != 0)) {
            if (faltante < 0) {
                monto.monto = monto.monto - Math.abs(faltante);
            } 
        }
      

    }
    $scope.cuentasIguales = function () {
        var len = $scope.montos.length;
        var monto = $scope.totalPagar / len;
        var i = 0;
        for (; i < len; i++) {
            $scope.montos[i].monto = monto;

        }
        $scope.pagoCubierto = true;
    }

    function limpiarMontos() {
        var montos = $scope.montos; var len = montos.length; var i = 0;
        for (; i < len; i++) {
            var m = montos[i].monto;
            if (m <= 0 || m == "" || m == undefined) {
                $scope.montos.splice(i, 1);
            }
        }
    }
    $scope.abrirPaso2Montos = function () {
        $scope.show.secondStepMontos = true;
        $scope.show.montos = false;
        $scope.pagoCubierto = false;
        limpiarMontos();
        $scope.montos[0].active = true;
        $scope.indexMonto = 0;
        montoAnterior = $scope.montos[0];
    }
    var montoAnterior = {};
    $scope.indexMonto = 0;
    $scope.regresarAmontos = function () {
        $scope.show.secondStepMontos = false;
        $scope.show.montos = true;

    }
    function inactiveMontos() {
        var len = $scope.montos.length; var i = 0;
        for (; i < len; i++) {
            $scope.montos[i].active = false;
        }
    }
    $scope.activarMonto = function (i) {
       // montoAnterior.active = false;
        inactiveMontos();
        $scope.montos[i].active = true;
        //montoAnterior = $scope.montos[i];
        $scope.indexMonto = i;
    }

    function pagarCuentaMonto() {
        var monto = $scope.montos[$scope.indexMonto];
        return $http.post("php/accionMesero.php?accion=pagarCuenta", { mesa:$scope.mesa.idmesa,descuento:$scope.descuento,metodoPago:$scope.pago.metodo_pago,total: monto.monto, idPedido: $scope.mesa.idpedido });
    }
    $scope.cuentas_monto = [];
    $scope.pagarCuentaMonto = function () {
        pagarCuentaMonto().then(function (resp) {
            if (resp.data.exito) {
                printService.imprimirCuentaCompleta(resp.data.idCuenta,"1").then(function(resp2){
                    if(!resp2.data){
                        console.log(resp2.data);
                    }
                });
                $scope.cuentas_monto.push(resp.data.idCuenta);
                toastr.success("Cuenta Pagada exitosamente");
                var i = $scope.indexMonto;
                $scope.montos.splice($scope.indexMonto, 1);
                var nuevo = i + 1;
                if ($scope.montos.length != 0) {
                    if ($scope.montos[nuevo]) {
                        montoAnterior.active = false;
                        $scope.montos[nuevo].active = true;

                    } else {
                        nuevo = i - 1;
                        if ($scope.montos[nuevo]) {
                            montoAnterior.active = false;
                            $scope.montos[nuevo].active = true;
                        }
                    }
                } 




            } else {
                toastr.error("Upsss...Ha ocurrido un error, intentalo mas tarde");
            }
        });

    }
    $scope.descuento = 0;
    $scope.abrirPagarTodo = function () {
        $scope.show.firstStep = false;
        $scope.show.pagarTodo = true;
        promosService.obtenerTotalCuenta($scope.mesa.idpedido).then(function(resp){
            $scope.totalPagar = resp.data.total;
            $scope.descuento = resp.data.descuento;
        });
        //$scope.totalPagar = obtenerTotal();
    }

    $scope.pagarTodo = { recibido: 0, cambio :0};
    $scope.pagarTodoRecibido = function () {
        var monto = $scope.pagarTodo.recibido;
        if (monto >= $scope.totalPagar) {
            $scope.pagarTodo.cambio = Math.abs(monto - $scope.totalPagar);
        }



    }
    function pagarTodoDB() {
        return $http.post("php/accionMesero.php?accion=pagarCuenta", { mesa:$scope.mesa.idmesa,descuento:$scope.descuento,metodoPago:$scope.pago.metodo_pago,total: $scope.totalPagar, idPedido: $scope.mesa.idpedido });


    }
 
    $scope.todoPagado = false;
    $scope.cuenta_pagada_todo = 0;
    $scope.pagarTodo = function () {
        pagarTodoDB().then(function (resp) {
            if (resp.data.exito) {
                toastr.success("Se ha pagado la cuenta exitsomente");
                //$scope.terminar();
                $scope.todoPagado = true;
                total = $scope.totalPagar;
                $scope.cuenta_pagada_todo = resp.data.idCuenta;

            }
        });
    }
    // funcionalidad de impresion de tickets
    $scope.imprimirTicketTodo = function(){
        printService.imprimirCuentaCompleta($scope.cuenta_pagada_todo,"0").then(function(resp){
          var data = resp.data;
          if(data.exito){
              window.open("../administrador/tickets/ticket_"+$scope.cuenta_pagada_todo+".pdf");
          }else{
              console.log(data);
          }
        });
    }

}
