function ticketsService($http){
    this.init = function(){
        return $http.get("PHP/new/tickets/fetchData.php",{params:{accion:"init"}});
    }
    this.buscarCuentas = function(ticket){
        $.extend(ticket,{accion:"buscarCuentas"});
        return $http.post("PHP/new/tickets/fetchData.php?accion=buscarCuentas",ticket);
        
    }
    this.obtenerDetalles = function(idCuenta){
        return $http.get("PHP/new/tickets/fetchData.php",{params:{accion:"obtenerDetallesCuenta",idCuenta:idCuenta}});
        
    }
    this.exportarExcell = function(rows,header,savePath,sheetName){
        return $http.post("../../excelGen/gen/exportTickets.php",{header:header,rows:rows,savePath:savePath,sheetName:sheetName});
    }
    this.imprimirTicket = function(idCuenta){
        return $http.get("PHP/new/tickets/fetchData.php",{params:{accion:"imprimirTicket",idCuenta:idCuenta}});
        
    }
}