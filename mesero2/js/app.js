(function () {
   

    var app = angular.module("app", ["ngRoute", "ngSanitize"]);

    app.config(function ($routeProvider) {
        $routeProvider
        .when("/", {
            templateUrl: "views/main.htm",
            controller: ctrlMain
        }).when("/mesa", {
            templateUrl: "views/mesa.htm",
            controller: ctrlMesa
        })

        
    });

    app.controller("ctrlMesas", ctrlMesas);
    app.controller("ctrlPending", ctrlPending);
    app.controller("ctrlNav", ctrlNav);
    app.controller("ctrlAgregarMesasModal", ctrlAgregarMesasModal);
    app.controller("ctrlQuitarMesas", ctrlQuitarMesas);
    app.controller("ctrlCobrar", ctrlCobrar);
    app.controller("ctrlModalSettings", ctrlModalSettings);
    app.controller("ctrlFacturar", ctlFacturar);
    app.controller("welcomeCtrl", ctrlWelcome);
    app.controller("ctrlCaja", ctrlCaja);
    app.controller("ctrlPromos",ctrlPromos);
    app.controller("ctrlTickets",ctrlTickets);
    
    app.service("promosService",promosService);
    app.service("printService",printService);
    app.service("ticketsService",ticketsService);
    
    
    
})()