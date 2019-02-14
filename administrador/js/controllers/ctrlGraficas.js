function ctrlGraficas($scope,plotService){
    $scope.platillos = [];
    plotService.obtenerPlatillos().then(function(resp){
        $scope.platillos = resp.data;        
    });
$scope.filtros = {
    porPlatillo:false,
    rango:"date",
    fechaInicio:"",
    fechaFin:new Date(),
    platillo:""
};
function getPlatilloNombre(id){
    var platillos = $scope.platillos; var len = platillos.length;
    var i = 0;
    for(;i<len;i++){
        var p = platillos[i];
        if(p.id==id){
            return p.nombre;
        }
    }
    return null;
}
$scope.clearFrom = function(){
    $scope.filtros = {
        porPlatillo:false,
        rango:"date",
        fechaInicio:"",
        fechaFin:"",
        platillo:""
    };

}
$scope.bardata = [];
$scope.topDonut = [{label:"",value:0}];
$scope.descripcion = "";
$scope.totales = {total:0,gastos:0,ganancias:0};
$scope.promedios = {promedio_total:0,promedio_gastos:0,promedio_ganancias:0};
  $scope.xaxis = 'y';
  $scope.yaxis = '["a"]';

$scope.plotResult = function(){
var filtros = angular.copy($scope.filtros);
filtros.fechaFin = filtros.fechaFin.toISOString().substring(0, 10);
filtros.fechaInicio = filtros.fechaInicio.toISOString().substring(0, 10);
var str_tmp = (filtros.porPlatillo || filtros.porPlatillo=="1")?getPlatilloNombre(filtros.platillo):"Todos los platillos";

  var request = plotService.obtenerDatos(filtros);
  if(request!=null){
      request.then(function(resp){
          var data = resp.data;
          if(data.grafica.length==0){
              toastr.info("No se han generado datos hasta el momento");
          }
        $scope.bardata = data.grafica;
        $scope.descripcion = "Desde "+filtros.fechaInicio+" Hasta "+filtros.fechaFin+" "+str_tmp;
        $scope.totales = data.totales;
        $scope.promedios  = {promedio_total:data.promedios.promedio_total,
            promedio_gastos:data.promedios.promedio_gastos,
            promedio_ganancias:parseFloat(data.promedios.promedio_total)-parseFloat(data.promedios.promedio_gastos)
        }
        $scope.topDonut =data.top;
    });
  }

}
$scope.exportarExcel = function(){
    var filtros= $scope.filtros;
    var header={
        cantidad:"integer",        
        fecha:"date",
        total:"price",
        costo:"price",
        ganancia:"price",
        nombre:"string"
    };
    var d = new Date();
    var hoy = d.toLocaleDateString();
    hoy=hoy.split("/");
     hoy = hoy.join("_");
    var savePath = "/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_graficas_"+hoy+".xlsx";
    var sheetName = "Exportacion luxrestaurant graficas "+hoy;   
    var postData = {
        
                    header:header,
                    fechaInicio:filtros.fechaInicio,
                    fechaFin:filtros.fechaFin,
                    savePath:savePath,
                    sheetName:sheetName
                 };  

    plotService.exportarExcel(postData).then(function(resp){
        console.log(resp.data);
        window.open("excellExport/export_graficas_"+hoy+".xlsx");
    });
}
    
}