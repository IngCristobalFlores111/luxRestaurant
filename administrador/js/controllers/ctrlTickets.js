function ctrlTickets($scope,ticketsService,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder){
//dt declarations
$scope.vm = {};
$scope.vm.dtInstance = {};   
$scope.vm.dtColumnDefs = [DTColumnDefBuilder.newColumnDef(2).notSortable()];
$scope.vm.dtOptions = DTOptionsBuilder.newOptions()
                  .withOption('paging', true)
                  .withOption('searching', false)
                  .withOption('info', false)
                  .withOption('dom', 'Bfrtip')
                  .withButtons([
                                      {
                                          extend:    'copy',
                                          text:      '<i class="fa fa-files-o"></i> Copiar',
                                          titleAttr: 'Copy'
                                      },
                                      {
                                          extend:    'excel',
                                          text:      '<i class="fa fa-file-text-o"></i> Excel',
                                         titleAttr: 'Excel'
                                      },
                                      {
                                          extend:    'print',
                                          text:      '<i class="fa fa-print" aria-hidden="true"></i> Imprimir',
                                          titleAttr: 'Print'
                                      }
                                  ]
                                )
                  ;

//

$scope.users = [];
$scope.metodosPago  =[];
$scope.platillos =[];
$scope.cuentas = [];
$scope.ticket = {idCuenta:0,llevar:false,monto:0,platillos:"",fechaInicio:"",fechaFin:"",usr:"",metodoPago:""};

ticketsService.init().then(function(resp){
    var data = resp.data;
    $scope.users = data.usuarios;
    $scope.ticket.usr="todos";
    $scope.metodosPago = data.metodosPago;
    $scope.ticket.metodoPago="todos";
    $scope.platillos =data.platillos;
    $scope.cuentas = data.cuentas;
    

});
$scope.buscarCuentas = function(){
   var ticket = angular.copy($scope.ticket);
   if(ticket.idCuenta>0){
    ticketsService.buscarCuentas(ticket).then(function(resp){
        $scope.cuentas = resp.data;
    });
   }else{
   if(ticket.idCuenta<0 || ticket.fechaInicio=="" ||ticket.fechaInicio==undefined || ticket.fechaFin=="" ||ticket.fechaFin==undefined ){
       toastr.info("Debes de seleccionar un rango de fechas");
   }else{
       ticketsService.buscarCuentas(ticket).then(function(resp){
           $scope.cuentas = resp.data;
       });
   }
}
}
$scope.detalles = {};
$scope.mostrarDetalles = function(cuenta){
     $scope.detalles = angular.copy(cuenta);

    ticketsService.obtenerDetalles(cuenta.id).then(function(resp){
    $scope.detalles.platillos = resp.data;
    modalTickets.modal("show");
    });
}
$scope.imprimirTicket = function(){
    ticketsService.imprimirTicket($scope.detalles.id).then(function(resp){
        if(resp.data.exito){
            var url_ticket ="tickets/ticket_"+$scope.detalles.id+".pdf";
            window.open(url_ticket);
        }else{
            console.log(resp.data);
        }
    }); 

}
$scope.exportarExcel = function(){
var cuentas = angular.copy($scope.cuentas);
    var header = {
        descuento:'price',
        id:'integer',
        fecha:'date',
        total:'price',
        metodoPago:'string',        
        usuario:'string',        
        llevar:'string'

    };

    var d = new Date();
    var hoy = d.toLocaleDateString();
    hoy=hoy.split("/");
     hoy = hoy.join("_");
    var savePath = "/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_"+hoy+".xlsx";
    var sheetName = "Exportacion luxrestaurant tickets "+hoy;
    ticketsService.exportarExcell(cuentas,header,savePath,sheetName).then(function(resp){
       window.open("excellExport/export_"+hoy+".xlsx");
    });
    
}

}