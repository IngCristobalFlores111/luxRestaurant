function ctrlSessiones($scope,usersService){
    $scope.usuarios = [];
    $scope.filtrosUsers = [];
    $scope.filtros = {inicio:"",fin:"",usr:""};
    var allUsers = [];
    usersService.getSessions().then(function(resp){
       var users = resp.data;
       var len = users.length; var i = 0;
       for(;i<len;i++){
           var u = users[i];
           if(u.fin && u.inicio){
           u.inicio = new Date(u.inicio.replace(/-/g, '/').trim());
           u.fin = new Date(u.fin.replace(/-/g, '/').trim()); 
           users[i] = u;  
           }       
       }        
       $scope.usuarios = users;
allUsers = angular.copy(users);

    });
    usersService.getUsersSimple().then(function(resp){
        $scope.filtrosUsers = resp.data;        
      
    });

$scope.aplicarFiltros = function(){
var len = allUsers.length; var i = 0;
var filtros = $scope.filtros;
var inicio =filtros.inicio;
var fin = filtros.fin;
var usr =filtros.usr;
if((usr==undefined||usr=="")&&(inicio==undefined||inicio=="") &&(fin==undefined||fin=="")){
toastr.info("Debes de seleccionar los filtros adecuadamente");
return;
}
usersService.buscarSesiones(usr,inicio.toISOString().substring(0, 10),fin.toISOString().substring(0, 10)).then(function(resp){
$scope.usuarios= resp.data;

});


}
$scope.quitarFiltros = function(){
$scope.usuarios = allUsers;
$scope.filtros.usr = undefined;

}


}