function ticketsService($http){
    this.obtenerTicketsMesa = function(idMesa){
        return $http.get("php/tickets/fetchData.php",{params:{accion:"ticketsMesa",idMesa:idMesa}});

    }
    this.imprimirTicket = function(idCuenta){
        return $http.get("php/tickets/fetchData.php",{params:{accion:"imprimirTicket",idCuenta:idCuenta}});
        
    }
}