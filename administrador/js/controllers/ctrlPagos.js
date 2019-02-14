function ctrlPagos($scope,pagosService,excelExportService,DTOptionsBuilder, DTColumnBuilder,DTColumnDefBuilder){
//filtar tabla agurpada por dia
$scope.filtros2 = {fechaInicio:"",fechaFin:new Date()};
$scope.filtarTablaDias = function(){
var filtros = $scope.filtros2;
if(filtros.fechaInicio==""|| filtros.fechaInicio==undefined ||filtros.fechaFin==""|| filtros.fechaFin==undefined){
    toastr.info("Debes de seleccionar un rango de fechas");
}else{
    pagosService.filtarCuentasPorDia(filtros.fechaInicio,filtros.fechaFin).then(function(resp){
        $scope.cuentasPorDia = resp.data;
    });
}
}
$scope.exportarExcel = function(){
    var headers = {
        "idMetodoPago":"integer",
        "Total":"price",
        "fecha":"date",
        "metodo de Pago":"string"
    };
    var d = new Date();
    var str_fecha  = d.toLocaleDateString().split("/").join("_");
    var savePath="/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_pagos_"+str_fecha+".xlsx";

    excelExportService.exportarExcel(headers,angular.copy($scope.cuentasPorDia),savePath).then(function(resp){
        window.open("excellExport/export_pagos_"+str_fecha+".xlsx");
        
    });
}

//


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
                            text:      '<i class="fa fa-print" aria-hidden="true"></i> Imrpimir',
                            titleAttr: 'Print'
                        }
                    ]
                  )
    ;    
    


    $scope.filtros ={inicio:"",fin:"",metodoPago:""};
$scope.metodosPago = [];
$scope.cuentasSearch = [];

$scope.buscarCuentas = function(){
var filtros = $scope.filtros;
if(filtros.inicio==""||filtros.inicio==undefined ||filtros.fin==""||filtros.fin==undefined){

    toastr.info("Debes de seleccionar un rango de fechas");
}else{
    pagosService.buscarCuentas(filtros.metodoPago,filtros.inicio,filtros.fin).then(function(resp){
        $scope.cuentasSearch = resp.data;        


    });

}

}

pagosService.obtenerMetodosPago().then(function(resp){
    
    $scope.metodosPago = resp.data;
    });

    $scope.getDate = function(date){
   var d = new Date(date);
   d.setDate(d.getDate() +1);
        return d;
    }
$scope.cuentasPorDia =[];
    pagosService.obtenerCuentasPorDia().then(function(resp){

$scope.cuentasPorDia = resp.data;
});
$scope.detalles = function(c){
pagosService.obtenerDetalles(c.idMetodoPago,c.fecha).then(function(resp){
var cuentas = resp.data;
var len = cuentas.length; var i = 0;
var html ="<div class='table-responsive'><table class='table table-bordered table-hover table-striped'>";
html+="<thead><tr><th>Total</th><th>Fecha</th><th>Usuario</th></tr></thead><tbody>";
for(;i<len;i++){
    var cuenta = cuentas[i];
    
html+="<tr><td>$"+cuenta.total.toLocaleString()+"</td><td>"+cuenta.fecha+"</td><td>"+cuenta.usuario+"</td></tr>";

}
html+="</tbody></table></div>";
bootbox.alert({
    title:"Detalles de cuentas del dia "+c.fecha+" Metodo de pago "+c.metodoPago,
    message: html,
    callback: function () {
        console.log('This was logged in the callback!');
    }
})

});
}


}