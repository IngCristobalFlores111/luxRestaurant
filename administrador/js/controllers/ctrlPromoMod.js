function ctrlPromoMod($scope,$rootScope,promoService){
 
        Date.prototype.toYMD = Date_toYMD;
        function Date_toYMD() {
            var year, month, day;
            year = String(this.getFullYear());
            month = String(this.getMonth() + 1);
            if (month.length == 1) {
                month = "0" + month;
            }
            day = String(this.getDate());
            if (day.length == 1) {
                day = "0" + day;
            }
            return year + "-" + month + "-" + day;
        }
   
$scope.promo = {};
$scope.frecuencias = [];
    $scope.$on("modPromo",function(event,data){
      var obj =  angular.copy(data); 
      var prom = obj.promo;
      prom.fecha_inicio = new Date(prom.fecha_inicio);
      prom.fecha_fin = new Date(prom.fecha_fin);
      prom.fecha_inicio.setDate(prom.fecha_inicio.getDate() +1);
      prom.fecha_fin.setDate(prom.fecha_fin.getDate() +1);
      
       $scope.promo = prom;
       $scope.frecuencias =obj.frecuencias;
       $scope.promo.frecuencia = prom.idFrecuencia.toString();
         $scope.promo.activo = (prom.activo==1);
       
});
$scope.actualizarPromo = function(){
    var promo=$scope.promo;

    if(promo.cantidad<0||promo.descuento<0||promo.descripcion.trim()=="" || promo.fecha_inicio==""|| promo.fecha_inicio==undefined ||promo.fecha_fin==""|| promo.fecha_fin==undefined){
        toastr.info("Debes de llenar todos los campos con valores validos");
    }else{
        promo.fecha_fin=  promo.fecha_fin.toYMD();
        promo.fecha_inicio=  promo.fecha_inicio.toYMD();
        
promoService.modificarPromo(promo).then(function(resp){
    if(resp.data.exito){
        toastr.success("Se ha actualizado la promoción correctamente");
      $rootScope.$broadcast("promoChanged",promo);
    }else{
        toastr.error("Upsss... ha ocurrido un error, contacta a soporte");

    }
    $("#modalPromo").modal("hide");
});
    }
}

}