function ctrlWelcome($scope, $http) {
    $scope.tipo = "";
    $scope.asunto = "";
    $scope.mensaje = "";
    $scope.soporte = function () {
        $("#modalSoporte").modal("show");
    }
    $scope.enviar = function () {

    }
}