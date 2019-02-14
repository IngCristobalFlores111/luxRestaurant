function ctrlCaja($scope,$http){
$scope.opcion = 0;
$scope.cajas= [];
$scope.cajaSelected = null;
$http.get("php/caja/fetchData.php",{params:{accion:"init"}}).then(function(resp){
var data = resp.data;

$scope.opcion = data.opcion;
if(data.opcion==1){
    $scope.cajaSelected = data.cajas;
}else{
$scope.cajas = data.cajas;
}
});
$scope.selectCaja = function(caja){
$scope.cajaSelected = caja;
}
$scope.deselectCaja = function(){
$scope.cajaSelected = null;

}
$scope.iniciarCaja = function(){
var caja = $scope.cajaSelected;
if(caja.total_inicio>0){
$http.post("php/caja/accionCaja.php?accion=initCaja",{total:caja.total_inicio,idCaja:caja.idCaja}).then(function(resp){
if(resp.data.exito){
    $scope.opcion =1;
    toastr.success("Se ha iniciado caja exitosamente");
    $scope.cajaSelected.total_fin = caja.total_inicio;
}else{
    toastr.error("Upsss... ha ocurrido un error, cotnacta a soporte");
}


});

}
else{
    toastr.info("Cantidad invalida");
}
}
$scope.actualizarActual = function(){
var caja = $scope.cajaSelected;
    if(caja.total_fin>0){
    $http.post("php/caja/accionCaja.php?accion=actualizarTotalActual",{total:caja.total_fin,idCaja:caja.idCaja}).then(function(resp){
   if(resp.data.exito){
       toastr.success("Cantidad actualizada");
   }else{
       toastr.error("Upsss... algo salio mal, contacta a soporte");
   }


    });

}else{
    toastr.info("Cantidad invalida");
}

}
$scope.corteCaja = function(){
    var caja = $scope.cajaSelected;
    console.log(caja);
$http.post("php/caja/accionCaja.php?accion=corteCaja",caja).then(function(resp){
if(resp.data.exito){
    toastr.success("Corte realizado exitosamente");
    $scope.opcion=0;
    $scope.cajas= resp.data.cajas;
    $scope.cajaSelected = null;    
}else{
    toastr.error("Upsss.. ha ocurrido un error,contacta a soporte");
}

});


}

}