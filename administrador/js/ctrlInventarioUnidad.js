function ctrlInventarioUnidad($scope,$http){
$scope.busqueda = {platillo:""};
$scope.producto = {actual:false,cantidad:0,idAlmacen:"",idProveedor:"",idPlatillo:""};
$scope.platillos = [];
$scope.proveedores = [];
$scope.almacenes = [];
$scope.inventario =[];
$scope.selected = null;

$scope.descontar= function(item){

$http.post("PHP/new/inventario/accionInventario.php?accion=setInventarioUnidadActual",{actual:item.actual,id:item.id,idPlatillo:item.idPlatillo}).then(function(resp){
if(resp.data.exito){
    toastr.success("Se ha actualizado este producto, ahora se descontara de este");
    resetActual(item.idPlatillo,item.id);
}else{
    toastr.error("Upsss... ha ocurrido un error, contacta soporte");
}

});



};


$http.get("PHP/new/inventario/fetchData.php",{params:{accion:"obtenerAlmacenesProveedores"}}).then(function(resp){
$scope.proveedores= resp.data.proveedores;
$scope.almacenes= resp.data.almacenes;
});
$http.get("PHP/new/inventario/fetchData.php",{params:{accion:"obtenerInventarioUnidad"}}).then(function(resp){
  $scope.inventario = resp.data;
    });

function onDupliacate(producto){

    var prods = $scope.inventario; var len = prods.length; var i =0;
    for(;i<len;i++){
      var p = prods[i];

      if(p.idAlmacen==producto.idAlmacen&& p.idProveedor==producto.idProveedor && p.idPlatillo==producto.idPlatillo)
       {
           var nuevaCant = parseInt(p.cantidad)+parseInt(producto.cantidad);
          p.cantidad = nuevaCant;
        return true;
        }
    }
return false;

}

$scope.actualizarCantiad = function(item){
    bootbox.prompt({
        title: "Actualiza la cantidad de "+item.nombre+" que ahora tienes en invetnario",
        inputType: 'number',
        callback: function (result) {
            if(result>=0 && result!=null){
                $http.post("PHP/new/inventario/accionInventario.php?accion=actualizarInventarioUnidadCantidad",{cantidad:result,id:item.id}).then(function(resp){
                  if(resp.data.exito){
                    item.cantidad = result;
                    toastr.success("Se ha actualizado la cantidad exitosamente");
                  }else{
                      toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
                  }

                });

            }else{
                toastr.info("Cantidad invalida");
            }
        }
    });

}
function obtenerNombrePlatillo(id){
    id = id.toString();
var platillos = $scope.platillos; var len = platillos.length; var i = 0;
for(;i<len;i++){
var p = platillos[i];

p.id = p.id.toString();
if(p.id==id){
return p.nombre;

}
}
return null;



}

function resetActual(idPlatillo,id){
var inventario=$scope.inventario; var len = inventario.length; var i = 0;
for(;i<len;i++){
    var inv = inventario[i];
    if(inv.idPlatillo==idPlatillo&&inv.id!=id){
        inv.actual = '0';
    }

}

}
$scope.agregarProducto = function(){
var p = $scope.producto;

if(p.cantidad<=0 || p.idAlmacen=="" || p.idProveedor=="" || p.idPlatillo==""){
toastr.info("Debes llenar todos los campos del producto");
}else{
$http.post("PHP/new/inventario/accionInventario.php?accion=agregarInventarioUnidad",p).then(function(resp){
   if(resp.data.exito){
       if(p.actual){
           resetActual(p.idPlatillo);
           p.actual ="1";
       }
    toastr.success("Se ha agregado producto al inventario con exito");
    if(!onDupliacate(p)){
        var almacen = selectAlmacenes.find("option:selected").text();
        var proveedor = selectProveedores.find("option:selected").text();
        p.proveedor = proveedor;
        p.almacen = almacen;
        p.id = resp.data.idProducto;
        p.nombre = $scope.selected.nombre;
        $scope.inventario.push(p);
    }
    
    $scope.producto = {actual:false,cantidad:0,idAlmacen:"",idProveedor:"",idPlatillo:""};
    $scope.selected = null;
    $scope.platillos =[];    


   }else{
toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
   }


});



}


}




$scope.buscarPlatillo = function(){
var q = $scope.busqueda.platillo;
q = q.trim();
$http.get("PHP/new/inventario/fetchData.php",{params:{q:q,accion:"buscarPlatillo"}}).then(function(resp){
$scope.platillos = resp.data;
});
}
var anterior = {};
$scope.selectPlatillo = function(index){
anterior.selected = false;
    var p =  $scope.platillos[index];
$scope.producto.idPlatillo = p.id;
p.selected = true;
anterior = p;
$scope.selected = p;
$scope.platillos =[];

}


    
}