function ratingsService($http){
    this.buscarTicket = function(idTicket){
        return $http.get("php/ratings/fetchData.php",{params:{accion:"buscarTicket",idTicket:idTicket}});

    }
    this.aceptarRating = function(estrellas,comentario,idPlatillo,idTicket){
        return $http.post("php/ratings/accionRating.php?accion=ingresarRating",{estrellas:estrellas,comentario:comentario,idPlatillo:idPlatillo,idTicket:idTicket});
        
    }
}