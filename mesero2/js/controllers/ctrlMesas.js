function ctrlMesas($scope, $rootScope, $http, $interval) {
    $scope.mesas = [];
    function getMesas() {
        $http.get("php/fetchData.php", { params: { accion: "mesas" } }).then(function (resp) {
            $scope.mesas = resp.data;
            $rootScope.$broadcast("mesas",angular.copy(resp.data));
           
        });

    }
    getMesas();

    $interval(getMesas, 3000);
    $scope.abrirMesa = function (index) {
        location.href = "index.html#!/mesa?id=" + $scope.mesas[index].id;

    }

}