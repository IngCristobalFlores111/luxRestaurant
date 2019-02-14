function ctrlCajas($scope,$http){
    $scope.tab=1;
    $scope.mod = {};
    $scope.nueva = {nombre:""};
    $scope.cajas = [];
    $scope.cajasHistorial = [];
     $scope.setTab = function(tab){
    $scope.tab=tab;

 }   
 $http.get("PHP/new/cajas/fetchData.php",{params:{accion:"cajas"}}).then(function(resp){
     var data = resp.data;
$scope.cajas = data.cajas
$scope.cajasHistorial = data.historial;

 });

 $scope.agregarCaja = function(){
var nombre = $scope.nueva.nombre;
if(nombre!=""){
$http.post("PHP/new/cajas/accionCaja.php?accion=altaCaja",{nombre:nombre}).then(function(resp){
var data = resp.data;
if(data.exito){
    toastr.success("Caja agregada exitosamente");
    $scope.cajas.push({nombre:nombre,id:data.idCaja,fecha_inicio:"caja nueva",fecha_fin:"caja nueva",total_inicio:0,total_fin:0,usuario:"Sin Usuario"});
}else{
    toastr.error("Upss... ha ocurrido un error contacta a soporte");
}

});


}else{
toastr.info("Debes de especificar un nombre para la nuva caja");
}

 }
 $scope.aceptarMod = function(){
var caja = $scope.mod;
console.log(caja);
if(caja.total_inicio>0&&caja.total_fin>0&&caja.nombre!=""){
$http.post("PHP/new/cajas/accionCaja.php?accion=actualizarCaja",caja).then(function(resp){
if(resp.data.exito){
    toastr.success("Caja actualizada correctamente");
}else{
    toastr.error("Upsss... ha ocurrido un error, contacte a soporte");
}

});

}else{
    toastr.info("Valores invalidos");
}

 }
 $scope.configCaja = function(caja){
    modalConfigCaja.modal("show");
$scope.mod = caja;
 }
 $scope.eliminarCaja = function(caja){
    bootbox.confirm({
        title: "Seguro que deseas eliminar esta caja?",
        message: "Realmente estas seguro de eliminar "+caja.nombre+" del sistema?",
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
    $http.post("PHP/new/cajas/accionCaja.php?accion=eliminarCaja",{id:caja.id}).then(function(resp){
if(resp.data.exito){
    toastr.success("Se ha eliminado caja exitosamente");
    var index = $scope.cajas.indexOf(caja);
    $scope.cajas.splice(index,1);
}


    });
    
}
        }
    });


 }
}