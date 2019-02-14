function ctrlTickets($scope,ticketsService,printService){
$scope.mesas = [];
$scope.tickets  = [];
$scope.mesaSelected = null;
$scope.$on("mesas",function(event,data){
    $scope.mesas = data;
});
$scope.tickets = [];
$scope.abrirMesa = function(mesa){
    $scope.mesaSelected = mesa;
    ticketsService.obtenerTicketsMesa(mesa.id).then(function(resp){
        $scope.tickets  = resp.data;
    });
}
$scope.imprimirTicket = function(t){
    ticketsService.imprimirTicket(t.id).then(function(resp){
        if(resp.data.exito){
            window.open("../administrador/tickets/ticket_"+t.id+".pdf");

        }else{
            toastr.error("Upsss... algo salio mal, contacta a soporte");
        }
    });
}
$scope.regresar = function(){
    $scope.mesaSelected = null;
    
}
}