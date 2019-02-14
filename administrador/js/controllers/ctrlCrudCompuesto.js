function ctrlCrudCompuesto($scope,$http){
$scope.search = {insumo:""};
$scope.insumos = [];
$scope.insumosSimples =[];
$http.get("PHP/new/insumosCompuestos/fetchData.php",{params:{accion:"obtenerInsumosCompuestos"}}).then(function(resp){
$scope.insumos = resp.data;

});
$scope.buscarInsumo = function(){
$http.get("PHP/new/insumosCompuestos/fetchData.php",{params:{"accion":"buscarInsumo","q":$scope.search.insumo}}).then(function(resp){
    $scope.insumosSimples =resp.data;

});

}
$scope.insumoSelected = null;
$scope.setSelected = function(index){    
$scope.insumoSelected = $scope.insumosSimples[index];
//$scope.nuevo.insumos.push($scope.insumoSelected);

}
$scope.unsetSelected = function(){
$scope.insumoSelected = null;

}
$scope.mod = {};
$scope.openInsumo = function(i){
$scope.mod= i;
$scope.insumosActual =[];
$http.get("PHP/new/insumosCompuestos/fetchData.php",{params:{accion:"obtenerInsumosByIdCompuesto",id:i.id}}).then(function(resp){
   console.log(resp.data);
    $scope.insumosActual = resp.data;

    modalInsumo.modal("show");
});
}
$scope.agregarInsumoSimple = function(){
var insumo = angular.copy($scope.insumoSelected);
insumo.cantidad = $scope.mod.cantidad;
    $scope.insumosActual.push(insumo);
    $scope.insumoSelected = null;


}
$scope.eliminarInsumo = function(i){
    bootbox.confirm({
        title: "Seguro que deseas eliminar este insumo?",
        message: "Realmente estas seguro de eliminar "+i.nombre+"?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirmar'
            }
        },
        callback: function (result) {
          if(result){
            
            $http.post("PHP/new/insumosCompuestos/accionCompuesto.php?accion=eliminarCompuesto",{id:i.id}).then(function(resp){
            if(resp.data.exito){
                toastr.success("Se ha eliminado subreceta con exito");
                var index = $scope.insumos.indexOf(i);
                $scope.insumos.splice(index,1)
                $scope.$apply();
                
            }else{
                toastr.error("Upsss... ha ocurrido un error, contacta a soporte");
            }


            });
           


          }
        }

});
}

$scope.eliminarInsumoActual = function(i){

    bootbox.confirm({
        title: "Seguro que deseas eliminar este insumo?",
        message: "Realmente estas seguro de eliminar "+i.nombre+" de esta subreceta?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirmar'
            }
        },
        callback: function (result) {
          if(result){
            var index = $scope.insumosActual.indexOf(i);
            $scope.insumosActual.splice(index,1)
            $scope.$apply();
          }
        }
    });

;

}

$scope.modInsumoCantiadActual = function(i){
    bootbox.prompt({
        title: "Establece la nuva cantidad de "+i.nombre+" en "+i.unidad,
        inputType: 'number',
        callback: function (result) {
            if(result!=null && result>0){
              i.cantidad = result;
              $scope.$apply();

            }
        }
    });


}
$scope.actualizarCompuesto = function(){


    $http.post("PHP/new/insumosCompuestos/accionCompuesto.php?accion=updateInsumoCompuesto",{insumos:$scope.insumosActual,idCompuesto:$scope.mod.id,nombre:$scope.mod.nombre,descripcion:$scope.mod.descripcion}).then(function(resp){
    if(resp.data.exito){
        toastr.success("Se ha actualizado insumo exitosamente");
        modalInsumo.modal("hide");
    }else{
        toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
    }


    });

}




    
}