function ctrlPromo($scope,$rootScope,promoService){
$scope.search = {platillo:""};
$scope.platillos = [];
$scope.frecuencias = [];
$scope.promos = [];
$scope.promo = {cantidad_promocion:0,activo:true,descripcion:"",platillo:null,cantidad:0,descuento:0,fecha_inicio:"",fecha_fin:"",frecuencia:""};
promoService.obtenerPromos().then(function(resp){
    $scope.promos = resp.data;    
   
});
promoService.obtenerFrecuencias().then(function(resp){
    $scope.frecuencias = resp.data;    
});
$scope.buscarPlatillo =function(){
    var q = $scope.search.platillo;
    if(q!=""){
        promoService.buscarPlatillo(q).then(function(resp){
            
            $scope.platillos = resp.data;
        });
    }
}
$scope.selectPlatillo = function(p){
    $scope.promo.platillo = p;
}
$scope.deselectPlatillo = function(){
    $scope.promo.platillo = null;
}
$scope.modPromo = function(p){
    var broad = {promo:p,frecuencias: angular.copy($scope.frecuencias)};
    $rootScope.$broadcast("modPromo",broad);
$("#modalPromo").modal("show");


}
function updateById(promo){
    var promos = $scope.promos; var len = promos.length; var i = 0;
   var id = promo.id;
    for(;i<len;i++){
        var p = promos[i];
        if(p.id==id){
            $scope.promos[i] = promo;
            break;
        }

    }
    
}
$scope.$on("promoChanged",function(event,data){
    var frec = buscarFrecuencia(data.frecuencia);
    data.frecuencia = frec.nombre;
    updateById(data);

});
$scope.eliminarPromo = function(p){
    bootbox.confirm({
        title: "¿Seguro que deseas eliminar esta promoción?",
        message: "Esta promoción será eliminada del sistema",
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
            promoService.eliminarPromo(p.id).then(function(resp){
                if(resp.data.exito){
                    toastr.success("Se ha eliminado la promoción exitosamente");
                    $scope.promos.splice($scope.promos.indexOf(p),1);

                }else{toastr.error("Upsss...ha ocurrido un error, contacta a soporte");}
            });
           }
        }
    });
}
function buscarPlatillo(id){
    var platillos = $scope.platillos;
    var len  = platillos.length;
    var i = 0;
    for(;i<len;i++){
        var p = platillos[i];
        if(p.id==id){
            return p;
        }
    }
    return null;
}
function buscarFrecuencia(id){
    var frecs = $scope.frecuencias; var len = frecs.length;
    var i = 0;
    for(;i<len;i++){
        var f = frecs[i];
        if(f.idFrecuencia==id){
            return f;
        }
    }
    return null;
}
$scope.agregarPromo = function(){
    var promo = $scope.promo;
    console.log(promo);
    if( promo.cantidad_promocion<0,promo.platillo==null ||promo.descripcion==""|| promo.cantidad<0,promo.descuento<0|| promo.fecha_fin=="" || promo.fecha_inicio==""|| promo.frecuencia==undefined){
        toastr.info("Valores invalidos, favor de revisarlos");
    }else{
        promoService.altaPromo(promo).then(function(resp){
            if(resp.data.exito){
                toastr.success("Se ha dado de alta promocion exitosamente");
                promo.id= resp.data.idPromo;
                promo.descuento = promo.descuento/100;
                var plato = buscarPlatillo(promo.platillo.id);
                promo.nombre_platillo = plato.nombre;
                var frec = buscarFrecuencia(promo.frecuencia);
                promo.frecuencia = frec.nombre;
                $scope.promos.push(promo);
            }else{
                toastr.error("Upsss... ha ocurrido un error, contacta a soporte");
            }
        });
    }
}

}