(function () {


    var app = angular.module("app", ["ngRoute", "ngSanitize"]);
    app.config(function ($routeProvider) {
        $routeProvider
        .when("/", {
            templateUrl: "views/main.htm",
            controller:ctrlPedidos
        });


    });

   
})()