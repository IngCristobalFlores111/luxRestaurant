function ctrlQuitarMesas($scope, $http) {

    $scope.mesas = [];
    $http.get("php/fetchData.php", { params: { accion: "mesas" } }).then(function (resp) {
        $scope.mesas = resp.data;
    });
    $scope.$on("mesaAgregada", function (event,data) {
        $scope.mesas.push(data);
    });
    $scope.eliminarMesa = function (index) {
        var mesa = $scope.mesas[index];
        bootbox.confirm({
            title: "Seguro que deseas eliminar esta mesa?",
            message: "Realmente Deseas eliminar la mesa '"+mesa.nombre+"'",
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
                    $http.post("php/accionMesero.php?accion=eliminarMesa", { id: mesa.id }).then( function (resp) {
                       
                        if (resp.data.exito) {

                            toastr.success("Mesa eliminada con exito");
                            $scope.mesas.splice(index, 1);
                        } else {
                            toastr.error("No fue posible eliminar la mesa, contacta a soporte tecnico");
                        }

                    });

                }
            }
        });
    }
}