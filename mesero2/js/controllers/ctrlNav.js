function ctrlNav($scope,$timeout) {
    
    $scope.agregarMesa = function () {
        $("#ModalAgregarMesa").modal("show")
    }
    $scope.quitarMesa = function () {

        $("#ModalQuitarMesa").modal("show");
    }

}