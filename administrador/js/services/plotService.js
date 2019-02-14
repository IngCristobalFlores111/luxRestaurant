function plotService($http){
    this.ventasFecha = function(fechaInicio,fechaFin){
        return $http.get("PHP/new/estadisticas/fetchData.php?accion=ventasFecha",{params:{fechaInicio:fechaInicio,fechaFin:fechaFin}});
    }
    this.obtenerPlatillos = function(){
        return $http.get("PHP/new/estadisticas/fetchData.php",{params:{accion:"platillos"}});
        
    }
    this.obtenerDatos = function(filtros){
        var fail = false;
      if(filtros.fechaInicio==undefined || filtros.fechaFin==undefined || filtros.fechaInicio=="" || filtros.fechaFin==""){
          toastr.info("Debes de seleccionar las fechas primero");
          fail  =true;
          
      }
      if(filtros.porPlatillo&&(filtros.platillo=="" || filtros.platillo==undefined)){
          toastr.info("Debes de seleccionar un platillo");
          fail = true;
      }
      if(!fail){
        filtros.porPlatillo = (filtros.porPlatillo)?"1":"0";
        return $http.get("PHP/new/estadisticas/fetchData.php?accion=graficas",{params:{fecha_from:filtros.fechaInicio,fecha_to:filtros.fechaFin,por_platillo:filtros.porPlatillo,idPlatillo:filtros.platillo}});
      }else{
          return null;
      }
      

    }
    this.exportarExcel = function(data){
       return $http.post("../../excelGen/gen/ventasExportFromDb.php",data);

    }

}