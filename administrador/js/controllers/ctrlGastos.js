function ctrlGastos($scope,gastosService){
    $scope.ganancias = [];
    $scope.filtro = {rango:"0"};
    $scope.cambiarRangoGanancias = function(){
var rango  = $scope.filtro.rango;
gastosService.getEarnings(rango).then(function(resp){

    $scope.ganancias = resp.data;    

});


    }
    function getMysqlDate () {
        now = new Date();
        year = "" + now.getFullYear();
        month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
        day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
        hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
        minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
        second = "" + now.getSeconds(); if (second.length == 1) { second = "0" + second; }
        return year + "-" + month + "-" + day + " " + hour + ":" + minute + ":" + second;
      }
    $scope.categorias = [];
    $scope.gastos = [];
$scope.nuevo = {nombre:"",descripcion:"",idCategoriaGasto:"",total:0};
gastosService.getCategories().then(function(resp){

    $scope.categorias = resp.data;

});
gastosService.getExpenses().then(function(resp){
    $scope.gastos = resp.data;    

});
gastosService.getEarnings("0").then(function(resp){
$scope.ganancias = resp.data;

});
function getCategoryName(id){
var cats = $scope.categorias;
var len = cats.length;
var i = 0;
for(;i<len;i++){
    var c = cats[i];
    if(c.id==id){
        return c.nombre 
    }
}
return null;

}
$scope.agregarGasto = function(){
var gasto = angular.copy($scope.nuevo);
    var r = gastosService.addExpense(gasto);
if(r!=0){
    r.then(function(resp){
if(resp.data.exito){
    toastr.success("Se ha agregado gasto con exito");
    gasto.idGasto =resp.data.idGasto;
    gasto.categoria = getCategoryName(gasto.idCategoriaGasto);
    gasto.fecha= getMysqlDate();
    $scope.gastos.push(gasto);
    $scope.nuevo = {nombre:"",descripcion:"",idCategoriaGasto:"",total:0};
    
}else{
    toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
}

    });
}else{
    toastr.info("Debes de llenar los campos de manera adecuada");
}
}
$scope.deshacerGasto = function(){

    $scope.nuevo = {nombre:"",descripcion:"",idCategoriaGasto:"",total:0};
    

}
$scope.modGasto = function(g){
    bootbox.prompt({
        title: "Modifica el total del gastos "+g.nombre,
        message:g.descripcion+"<br><label>Cantidad Anterior:</label>$"+g.total.toLocaleString(),
        inputType: "number",
        callback: function (result) {
            if(result!=null&& result>0){
                g.total = result;
                gastosService.updateExpense(g).then(function(resp){
                   if(resp.data.exito){
                       toastr.success("Se ha actualizado gasto exitosamente");
                   }else{
                       toastr.error("Upsss... algo salio mal");
                   }

                });
            }
        }
    });

}
$scope.eliminarGasto  = function(g){
    bootbox.confirm({
        title: "¿Seguro que desas eliminar este gasto?",
        message: "¿Realmente estas seguro de eliminar el gasto "+g.nombre+" del sistema?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancelar'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirmar'
            }
        },
        callback: function (result) {
if(result){
    gastosService.deleteExpense(g.idGasto).then(function(resp){
if(resp.data.exito){
    toastr.success("Se ha eliminado gasto exitosamente");
    var index = $scope.gastos.indexOf(g);
    $scope.gastos.splice(index,1);
}else{
    toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
}

    });
}
        }
    });

}

}