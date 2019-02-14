function ctrlGastosCategorias($scope,$http){
$scope.categoria = {nombre:""};
$scope.categorias = [];
$http.get("PHP/new/categorias/fetchData.php",{params:{accion:"gastosCategorias"}}).then(function(resp){
   $scope.categorias = resp.data;
});
$scope.agregarCategoria = function(){
    var nombre = $scope.categoria.nombre.trim();
    if(nombre!=""){
    $http.post("PHP/new/categorias/accionCategorias.php?accion=altaCategoriaGasto",{nombre:nombre}).then(function(resp){
     if(resp.data.exito){
         toastr.success("Se ha dado de alta categoria exitosamente");
         $scope.categorias.push({nombre:nombre,id:resp.data.id});
     }else{
         toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
     }

    });
    }
}
$scope.modificar = function(c){
    bootbox.prompt({
        title: "Nuevo nombre para la categoria '"+c.nombre+"'",
        inputType: 'text',
        callback: function (result) {
            if(result!=null&&result!=""){
                $http.post("PHP/new/categorias/accionCategorias.php?accion=actualizarCategoriaGasto",{nombre:result,id:c.id}).then(function(resp){
                  if(resp.data.exito){
                      toastr.success("Se ha modificado categoria exitosamente");
                      c.nombre = result;
                  }else{
                    toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
                }
                });
                    
            }

        }
    });
}
$scope.eliminar = function(c){
    bootbox.confirm({
        title: "¿Seguro que deseas eliminar esta categoria?",
        message: "¿Realmente deseas eliminar la categoria '"+c.nombre+"'",
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
                $http.post("PHP/new/categorias/accionCategorias.php?accion=eliminarCategoriaGasto",{id:c.id}).then(function(resp){
                  var data = resp.data;
                  if(data.exito){
                      toastr.success("Categoria eliminada exitosamente");
                      $scope.categorias.splice($scope.categorias.indexOf(c),1);

                  }else{
                    toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
                    
                  }

                });
            }
        }
    });
}

}