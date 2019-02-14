(function () {


    var app = angular.module("app", ["ngRoute"]);
    app.config(function($routeProvider) {
        $routeProvider
        .when("/", {
            templateUrl : "views/ordenar.htm",
            controller:ctrlOrdenar
        }).when("/pedidos",{
        templateUrl:"views/pedidos.htm",
        controller:ctrlPedidos

        });      
    });
app.controller("ctrlCobrar",ctrlCobrar);
   
})()