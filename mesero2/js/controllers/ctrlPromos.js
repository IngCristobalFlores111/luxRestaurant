function ctrlPromos($scope,$rootScope,promosService){
    $scope.promos = [];
    promosService.obtenerPromos().then(function(resp){
        var data = resp.data;
        var len = data.length;
        if(len==0){
            toastr.info("No hay promociones actualmente");
            $("#modalPromos").modal("hide");
        }else{
            var i = 0;
            for(;i<len;i++){ // etablecer todos los items como cerrados sin input de entrada
                data[i].abierto = false;
            }
        }
        $scope.promos = data;

    });
    $scope.abrirPrompt = function(promo){

    promo.abierto=!promo.abierto; // establecer prompt visible
    
}
    $scope.agregarPromo = function(p){
        // posible cambio solo agregar 1 si la cantidad no es valida
        if(p.cantidad_pedido==undefined|| p.cantidad_pedido<0){
            toastr.info("Cantidad Invalida");
        }else{ // boradcast evento aplicar promo
            $rootScope.$broadcast("aplicarPromo",p);
            $("#modalPromos").modal("hide");
        }

    }

}