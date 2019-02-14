function promoService($http){
    this.buscarPlatillo = function(q){
        return $http.get("PHP/new/promos/fetchData.php",{params:{q:q,accion:"buscarPlatillo"}});

    }
    this.obtenerFrecuencias =function(){
        return $http.get("PHP/new/promos/fetchData.php",{params:{accion:"frecuencias"}});
        
    }
    this.obtenerPromos = function(){
        return $http.get("PHP/new/promos/fetchData.php",{params:{accion:"promos"}});
        
    }
    this.altaPromo = function(promo){
        return $http.post("PHP/new/promos/accionPromo.php?accion=altaPromo",promo);
        
    }
    this.modificarPromo = function(promo){
        return $http.post("PHP/new/promos/accionPromo.php?accion=modificarPromo",promo);

        
    }
    this.eliminarPromo = function(id){
        return $http.post("PHP/new/promos/accionPromo.php?accion=eliminarPromo",{id:id});
        
        
    }

}