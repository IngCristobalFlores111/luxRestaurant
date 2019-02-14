function printService($http){
    this.imprimirCuentaCompleta = function(idCuenta,parcial){
        return $http.get("php/print/accionPrint.php",{params:{accion:"imprimirCuenta",idCuenta:idCuenta,pacial:parcial}});

    }
}