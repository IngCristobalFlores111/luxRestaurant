function ctrlCobrar($scope,$rootScope,$http){


$scope.cliente ={nombrCliente:"",domicilio:""};
$scope.platillos = [];
$scope.total = 0;
$scope.descuento = 0;

$scope.pago = {recibido:0,cambio:0};
$scope.idPedido = 0;
$scope.metodosPago = [];
$http.get("php/fetchData.php",{params:{accion:"metodosPago"}}).then(function(resp){
    $scope.metodosPago = resp.data;
});
$scope.calcularCambio = function(){
var pago = $scope.pago;
var total = $scope.total;

if(pago.recibido<0){
    pago.recibido = 0;
}else{
pago.cambio =Math.abs(pago.recibido-total);

}



}
$scope.$on("cobrar",function(event,data){
    $scope.show = {btnTotal:true,btnTerminar:false};
$scope.platillos = data.platillos;
$scope.idPedido = data.pedido.idPedido;
$scope.cliente = {nombrCliente:data.pedido.nombrCliente,domicilio:data.pedido.domicilio};
var len = data.platillos.length; var i = 0;
var total =0;
for(;i<len;i++){
    total += parseFloat(data.platillos[i].precio)*parseFloat(data.platillos[i].cantidad);
}
$scope.total = total;

$http.post("../mesero2/php/promos/fetchData.php?accion=obtenerTotal",{idPedido:data.pedido.idPedido}).then(function(resp){

    $scope.descuento = resp.data.descuento;
    if($scope.descuento>0){
        $scope.total = total-resp.data.descuento;
        
    }
});

});
    $scope.pagar = function(){
   var metodoPago = $scope.pago.metodoPago;
   if(metodoPago=="" || metodoPago==undefined){
       toastr.info("Debes de seleccionar un metodo de pago");
   }else{
        $http.post("php/accionOrden.php?accion=pagar",{metodoPago:$scope.pago.metodoPago,descuento:$scope.descuento,total:$scope.total,idPedido:$scope.idPedido}).then(function(resp){
        if(resp.data.exito){
            toastr.success("Se ha cobrado la cuenta exitosamente");
          $scope.show.btnTotal = false;
          $scope.show.btnTerminar = true;
          $rootScope.$broadcast("cobrado",$scope.idPedido);
        }else{
            toastr.error("Upsss... ha ocurrido un problema, contacta a soporte");
        }


        });
    }


}
$scope.terminar = function(){
$http.post("php/accionOrden.php?accion=terminar",{idPedido:$scope.idPedido}).then(function(resp){
if(resp.data.exito){
    toastr.success("Se ha cerrado la cuenta exitosamente");
    modalCobrar.modal("hide"); 
}else{
    toastr.error("Upsss... algo paso mal, contacta a soporte");
}

})
}
}