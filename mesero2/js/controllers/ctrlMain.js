function ctrlMain($scope,$http) {
$scope.abrirCaja = function(){
    modalCaja.modal("show");
}
$scope.abrirTickets = function(){
    $("#modalTickets").modal("show");
}
    $scope.show = { mesas: true, pedidos: true };
    var alternate = 0;
    $scope.showHide = function () {
        $scope.show.mesas = !$scope.show.mesas;
        if ($scope.show.mesas) {
            $scope.show.pedidos = !$scope.show.pedidos;
        } else {
            $scope.show.pedidos = true;
        }
        if (alternate % 3 == 0 && alternate!=0) {
            $scope.show = { mesas: true, pedidos: true };

        }

        alternate++;
    }
  
    $scope.abrirConfig = function () {
        $("#modalUsrSetting").modal("show");

    }
    $scope.logOut = function () {
        $http.post("php/logOut.php").then(function () {
            location.href = "../login/Session.html";
        });
    }
   // usr.timestamp = usr.timestamp.replace(/-/g, '/');
  

}