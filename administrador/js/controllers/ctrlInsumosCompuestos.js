function ctrlCompuesto($scope,$http){
$scope.tabInsumo = 1;
$scope.setTabInsumo = function(tab){
    $scope.tabInsumo = tab;

}

    $scope.getCostoTotal = function(){
var total = 0;
var insumos = $scope.nuevo.insumos;
var len = insumos.length; var i = 0;
for(;i<len;i++){
    var insumo = insumos[i];
total+=insumo.cantidad*insumo.costo;

}
return total;

    }
    $scope.insumos =[];
    $scope.search = {insumo:""};
$scope.nuevo={nombre:"",descripcion:"",insumos:[],tmpCantidad:0};
$scope.insumoSelected = null;
$scope.setSelected = function(index){    
$scope.insumoSelected = $scope.insumos[index];
//$scope.nuevo.insumos.push($scope.insumoSelected);

}
$scope.unsetSelected = function(){
$scope.insumoSelected = null;

}

$scope.editarInsumo = function(i){

    bootbox.prompt({
        title: "Establece la nueva cantidad para el insumo "+i.nombre+" en "+i.unidad,
        inputType: 'number',
        callback: function (result) {
            if(result!=null && result>0){
         i.cantidad = result;
         $scope.$apply();
         
            }
        }
    });
}
$scope.eliminarInsumo = function(index){
    bootbox.confirm({
        title: "Seguro que desas eliminar este insumo?",
        message: "Realmente esas seguro de elimianr este insumo de esta subreceta?",
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

        $scope.nuevo.insumos.splice(index,1);
        $scope.$apply();
      }
        }
    });



}

$scope.agregarInsumo = function(){
var cantidad = $scope.nuevo.tmpCantidad;
    if(cantidad>0 && $scope.insumoSelected!=null)
{
    var n = $scope.insumoSelected;
    n.cantidad =cantidad;
$scope.nuevo.insumos.push(angular.copy(n));
$scope.insumoSelected = null;

}else{
    toastr.info("Datos invalidos");
}
}
$scope.searchInsumo = function(){
$http.get("PHP/new/insumosCompuestos/fetchData.php",{params:{accion:"buscarInsumo",q:$scope.search.insumo}}).then(function(resp){
$scope.insumos = resp.data;


});

}

$scope.agregarCompuesto = function(){
    var nuevo = $scope.nuevo;
if(nuevo.insumos.length>1 && nuevo.descripcion!=""&&nuevo.nombre!="")
{
$http.post("PHP/new/insumosCompuestos/accionCompuesto.php?accion=agregarCompuesto",nuevo).then(function(resp){
if(resp.data.exito){
toastr.success("Se ha agregado insumo compusto con exito");
$scope.nuevo={nombre:"",descripcion:"",insumos:[],tmpCantidad:0};
$scope.insumoSelected = null;

}else{
    toastr.error("Upsss... ha ocurrido un error, intentalo mas tarde");
}

});


}else{
toastr.info("Valores invalido, debes de escoger al menos 2 insumos")

}
}


}