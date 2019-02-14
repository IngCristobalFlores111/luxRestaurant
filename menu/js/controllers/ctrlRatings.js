function ctrlRatings($scope,ratingsService){
$scope.ticket = {id:0,platillos:[]}
var ticketActual  = 0;
$scope.buscarTicket = function(){
    var idTicket = $scope.ticket.id;
    if(idTicket<=0){
        toastr.info("id de ticket invalido");
    }else{
        ratingsService.buscarTicket(idTicket).then(function(resp){
          var data = resp.data;
          if(data.length>0){
              var len = data.length; var i = 0;
              for(;i<len;i++){
                  data[i].open = false;
                  data[i].rating = 5;
              }
           $scope.ticket.platillos = data;
           ticketActual = idTicket;
          }else{
              toastr.info("No se encontró este número de ticket");
          }
        });
    }
}
$scope.abrirRatingPlatillo  = function(p){
    p.open = !p.open;
}
$scope.ratingPlatillo = function(p){

}
$scope.aceptarRate = function(p){
    ratingsService.aceptarRating(p.rating,p.comentario,p.id,ticketActual).then(function(resp){
        if(resp.data){
            p.rated = true;
        }else{
            toastr.info("Upsss... ha ocurrido un error,intentalo mas tarde");
        }
    });

}
$scope.closeModal = function(){
    $("#modalRating").hide();    
}


}