
function ctrlOrdenar($scope,$rootScope,$http){
$scope.busqueda = {q:"",qPlatillo:""};
$scope.clientes = [];
$scope.platillos = [];
$scope.platillosPedidos = [];
$scope.clienteSelected = null;
$scope.nuevo = {nombre:"",domicilio:""};

$scope.agregarCliente = function(){
var nuevo = $scope.nuevo;
if(nuevo.nombre==""||nuevo.domicilio==""){
toastr.info("Debes de llenar todos los campos del cliente");
}else{
$http.post("php/accionOrden.php?accion=agregarCliente",nuevo).then(function(resp){
 if(resp.data.exito){
     toastr.success("Cliente Agregado exitosamente");
     modalClientes.modal("hide");
     nuevo.idCliente = resp.data.idCliente;
     $scope.clientes.push(angular.copy(nuevo));

 }else{
     toastr.error("Upss... ha ocurrido un error,intentalo mas tarde");
 }


});
}

}

$scope.triggerModalCliente = function(){
modalClientes.modal("show");

}


$scope.pedir = function(){
$http.post("php/accionOrden.php?accion=ordenar",{idCliente:$scope.clienteSelected.idCliente,platillos:$scope.platillosPedidos}).then(function(resp){
 if(resp.data.exito){
     toastr.success("Pedidos ordenados exitosamente");
     $scope.platillosPedidos =[];
     $scope.clienteSelected = null;
 }else{
     toastr.error("Upsss... algo salio mal, contacta a soporte");
 }


});

}

$scope.restarPlatillo =function(index){
 var p = $scope.platillosPedidos[index];
 p.cantidad--;
 if(p.cantidad<=0){
     $scope.platillosPedidos.splice(index,1);
 }

}
$scope.agregarPlatillo = function(p){

    if(p.cantidad==undefined||p.cantidad==0 || p.cantidad==""){
        p.cantidad = 1;
    }
   if(p.comentarios==undefined||p.comentarios ==""){

    p.comentarios ="Sin Comentarios";
   }

    if(p.cantidad<0){
        toastr.info("Cantidad invalida");
    }else{
        p.abierto = false;
        $scope.platillosPedidos.push(angular.copy(p));
    }
  


}

$scope.openPlatillo = function(p){
    p.abierto = true;
}
$http.get("php/fetchData.php",{params:{accion:"clientes"}}).then(function(resp){
$scope.clientes = resp.data;
});
$http.get("php/fetchData.php",{params:{accion:"platillos"}}).then(function(resp){
    $scope.platillos = resp.data;
    });
   $scope.selectCliente = function(c){
      $scope.clienteSelected = c;

   }   

$scope.deselect = function(){
$scope.clienteSelected = null;
}
}