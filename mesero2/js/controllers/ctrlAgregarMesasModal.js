function ctrlAgregarMesasModal($scope,$rootScope, $http) {
    $scope.mesa = {nombre:""};

    $scope.agregarMesa = function () {
        var mesa = $scope.mesa.nombre;
        if (mesa == "") {
            toastr.info("Nombre de la mesa no puede ser vacio");
        } else {
            $http.post("php/accionMesero.php?accion=agregarMesa", { nombre: mesa }).then(function (resp) {
                if (resp.data.exito) {
                    var idMesa = resp.data.idMesa;
                    var mesaObj = { nombre: mesa, id: idMesa };
                    $rootScope.$broadcast("mesaAgregada", mesaObj);
                    toastr.success("Mesa Agregada correctamente");
                } else {
                    toastr.error("Upsss... por el momento no fue posible agregar la mesa, contacta a soporte tecnico")
                }

            });
           

        }
    }

}