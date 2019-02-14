function usersService($http){

this.getSessions = function(){
   return  $http.get("PHP/new/users/fetchData.php",{params:{accion:"usuarioSesiones"}});
}
this.getUsersSimple = function(){
    return  $http.get("PHP/new/users/fetchData.php",{params:{accion:"usuarios"}});
}
this.getUsrProductivity = function(idUsr){

    return  $http.get("PHP/new/users/fetchData.php",{params:{accion:"usuarioProductividad",id:idUsr}});
}
this.buscarSesiones = function(idUsr,fechaInicio,fechaFin){
    return  $http.get("PHP/new/users/fetchData.php",{params:{accion:"filtrarSesiones",idUsr:idUsr,fechaInicio:fechaInicio,fechaFin:fechaFin}});
    
}


}