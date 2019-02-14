function ratingsService($http){
    this.initRatings = function(){
        return $http.get("PHP/new/ratings/fetchData.php",{params:{accion:"initRatings"}});
        
    }
    this.buscarRatings = function(filtros){
       var getData=  {accion:"buscarRatings"};
       $.extend(getData,filtros);
        return $http.get("PHP/new/ratings/fetchData.php",{params:getData});
        
    }
}