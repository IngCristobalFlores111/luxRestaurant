function promosService($http){
    this.obtenerPromos = function(){
        return $http.get("php/promos/fetchData.php",{params:{accion:"promos"}});

    }
    this.obtenerTotalCuenta = function(idPedido){
        return $http.post("php/promos/fetchData.php?accion=obtenerTotal",{idPedido:idPedido});

    }
    this.obtenerDescuentoItem = function(idPlatillo,cantidad,precio){
        return $http.get("php/promos/fetchData.php",{params:{accion:"obtenerDescuentoItem",idPlatillo:idPlatillo,cantidad:cantidad,precio:precio}});
        
    }
}