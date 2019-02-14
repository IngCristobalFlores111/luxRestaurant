function ctrlStatistics($scope,plotService){
$scope.ventasFecha = {ventaschartData:[{label:"",value:0}],show:false,fechaInicio:"",fechaFin:"",total:0,gastos:0,ganancia:0};
$scope.ventas = [];
$scope.refresh = function(){
    $scope.ventasFecha.ventaschartData = angular.copy($scope.ventasFecha.ventaschartData);
}
$scope.obtenerVentasFecha = function(){
var ventasFecha = $scope.ventasFecha;
  var fechaInicio = ventasFecha.fechaInicio.toISOString().substring(0, 10);
  var fechaFin = ventasFecha.fechaFin.toISOString().substring(0, 10);
  if(fechaFin==""||fechaFin==undefined || fechaInicio==""||fechaInicio==undefined){
    toastr.info("Debes de seleccionar un rango de fechas primero");
    }else{
        plotService.ventasFecha(fechaInicio,fechaFin).then(function(resp){
            var data = resp.data;
            if(data.datos.length==0){
                toastr.info("No se han generado datos hata el momento");
            }
            $scope.ventas = data.datos;
            var totales= data.totales;
            ventasFecha.total=totales.total;
            ventasFecha.gastos=totales.gastos;
            ventasFecha.total=totales.total;
            ventasFecha.ganancia = totales.ganancia;
            ventasFecha.cantidad = totales.cantidad;
            $scope.ventasFecha.ventaschartData = data.top;
            setTimeout(function() {
                $("#refresh").trigger("click");
            }, 500);
        });
    }
}

}