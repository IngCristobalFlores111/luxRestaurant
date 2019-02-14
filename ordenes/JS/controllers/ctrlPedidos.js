function ctrlPedidos($scope,$http,$rootScope){
    $scope.pedidos = [];
    $scope.index = 0;
    var anteriorPedido = {};
    $scope.selectPedido = function(index){
        anteriorPedido.selected = false;
     $scope.index = index;
     $scope.pedidos[index].selected = true;
anteriorPedido = $scope.pedidos[index];
    }

$scope.$on("cobrado",function(event,data){
   var len = $scope.pedidos.length; var i =0;
   for(;i<len;i++){
if($scope.pedidos[i].pedido.idPedido==data){
    $scope.pedidos.splice(i,1);
}

   }

});

$scope.cobrar = function(){

   $rootScope.$broadcast("cobrar",$scope.pedidos[$scope.index]);

    modalCobrar.modal("show");

}

$http.get("php/fetchData.php",{params:{accion:"pedidos"}}).then(function(resp){
$scope.pedidos = resp.data;
$scope.pedidos[0].selected = true;


});
    
}