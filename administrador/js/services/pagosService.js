function pagosService($http){
this.obtenerCuentasPorDia = function(){
return $http.get("PHP/new/pagos/fetchData.php",{params:{accion:"cuentasPorDia"}});

}
this.obtenerDetalles = function(idMetodoPago,fecha){

    return $http.get("PHP/new/pagos/fetchData.php",{params:{accion:"detallesDia",idMetodoPago:idMetodoPago,fecha:fecha}});
}
this.obtenerMetodosPago = function(){
    return $http.get("PHP/new/pagos/fetchData.php",{params:{accion:"metodosPago"}});
}
this.buscarCuentas = function(idMetodoPago,inicio,fin){

    return $http.get("PHP/new/pagos/fetchData.php",{params:{accion:"buscarCuentas",inicio:inicio,fin:fin,idMetodoPago:idMetodoPago}});
    
}
this.filtarCuentasPorDia = function(inicio,fin){
    return $http.get("PHP/new/pagos/fetchData.php",{params:{accion:"buscarCuentasPorDia",inicio:inicio,fin:fin}});
    
}

}