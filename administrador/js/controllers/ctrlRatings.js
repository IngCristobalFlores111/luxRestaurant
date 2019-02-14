function ctrlRatings($scope,ratingsService){
$scope.toDate = function(d){
    return new Date(d);
}
    $scope.starsArray = function(stars){
        var starsA = [];
        var i = 0;
        for(;i<stars;i++){
            starsA.push(i);
        }
        return starsA;
    }

    $scope.ratings = [];
    $scope.platillos = [];
    $scope.top5Mas = [];
    $scope.top5Menos = [];
    
    ratingsService.initRatings().then(function(resp){
        var data = resp.data;
        $scope.ratings = data.ratings;
        $scope.platillos = data.platillos; 
        $scope.top5Mas = data.topMas;
        $scope.top5Menos = data.topMenos;
    });
    $scope.filtros = {porRating:false,platillo:"0",rating:5,fechaInicio:"",fechaFin:new Date()};
    $scope.ratingPlatillo = function(rating){

    }
    $scope.buscarRating = function(){
        var filtros = $scope.filtros;
        if(filtros.fechaInicio=="" ||filtros.fechaInicio==undefined || filtros.fechaFin=="" ||filtros.fechaFin==undefined ){
            toastr.info("Debes de establecer un rango de fechas");
        }else{
            ratingsService.buscarRatings(filtros).then(function(resp){
                $scope.ratings = resp.data;
            });

        }
    }
}