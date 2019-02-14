(function () {
    var app = angular.module("app", ["angular.morris","ngRoute", "datatables", "ui.select", "ngSanitize","datatables.buttons"]);

    app.run(function ($rootScope, $route) {
        $rootScope.$route = $route;
    });

    app.service('fileUpload', function ($http) {
        this.uploadFileToUrl = function (files, phpUploadFile, uploadDir) {
            var fd = new FormData();
            $.each(files, function (i, file) {
                fd.append('file-' + i, file);
            });
            fd.append('fileDir', uploadDir);  // upload dir from php file 
            fd.append('rand', "true");
            fd.append("names", "");

            return $http.post(phpUploadFile, fd, {
                transformRequest: angular.identity,
                headers: { 'Content-Type': undefined }
            });


        }
    });


    app.service('fileUploadOptions', function ($http) {
        this.uploadFileToUrl = function (files, phpUploadFile, uploadDir,rand,names) {
            var fd = new FormData();
            $.each(files, function (i, file) {
                fd.append('file-' + i, file);
            });
            fd.append('fileDir', uploadDir);  // upload dir from php file 
            fd.append('rand', (rand)?'true':'false');
            fd.append("names", names);

            return $http.post(phpUploadFile, fd, {
                transformRequest: angular.identity,
                headers: { 'Content-Type': undefined }
            });


        }
    });

    app.filter('propsFilter', function () {
        return function (items, props) {
            var out = [];

            if (angular.isArray(items)) {
                var keys = Object.keys(props);

                items.forEach(function (item) {
                    var itemMatches = false;

                    for (var i = 0; i < keys.length; i++) {
                        var prop = keys[i];
                        var text = props[prop].toLowerCase();
                        if (item[prop].toString().toLowerCase().indexOf(text) !== -1) {
                            itemMatches = true;
                            break;
                        }
                    }

                    if (itemMatches) {
                        out.push(item);
                    }
                });
            } else {
                // Let the output be the input untouched
                out = items;
            }

            return out;
        };
    });
    app.config(function ($routeProvider) {
        $routeProvider
        .when("/", {
            templateUrl: "htms/home.htm",
            activetab: 'home'
        })
        .when("/recetas", {
            templateUrl: "htms/recetas.htm",
            controller: ctrlRecetas,
            activetab:'recetas'
        })
         .when("/insumos", {
             templateUrl: "htms/insumos-ng.htm",
             controller: ctrlInsumos,
             activetab:'insumos'
         })
            .when("/platillos", {
             templateUrl: "htms/editor.htm",
             controller: ctrlPlatillos,
                activetab:'platillos'
            })
         .when("/usuarios", {
             templateUrl: "htms/users.htm",
             controller: ctrlUsers,
             activetab:'users'
         })
             .when("/statistics", {
                 templateUrl: "htms/statistics-ng.htm",
                 controller: ctrlStatistics,
                 activetab:'statistics'
             })
             .when("/statisticsold", {
                templateUrl: "htms/statistics.htm",
                activetab:'statisticsold'
            })
                .when("/topsale", {
                    templateUrl: "htms/topsale.htm",
                    controller: ctrlTopSale,
                    activetab:'topsale'
                })
            .when("/categorias", {
                templateUrl: "htms/categorias.htm",
                controller: ctrlCategorias,
                activetab:'categorias'
            })
            .when("/facturacion", {
                templateUrl: "htms/facturacion.htm",
                controller: ctrlFacturacion,
                activetab:"facturacion"
            })
         .when("/inventario", {
             templateUrl: "htms/inventario.htm",
             controller: function($scope){
                 $scope.tab = 1;
                 $scope.setTab = function (tab) {
                     $scope.tab = tab;
                 }
             }
             , activetab: "inventario"
             
         })
        .when("/factura", {
            templateUrl: "htms/factura_detalle.htm",
            controller: ctrlFacturaDetalle,
            activetab: "factura"
        })
          .when("/usuario", {
              templateUrl: "htms/usuario.htm",
              controller: ctrlUsuario,
              activetab: "usuario"
          }).when("/cajas", {
            templateUrl: "htms/cajas.htm",
            controller: ctrlCajas,
            activetab: "cajas"
        }).when("/pagos", {
            templateUrl: "htms/pagos.htm",
            controller: ctrlPagos,
            activetab: "pagos"
        }).when("/gastos", {
            templateUrl: "htms/gastos.htm",
            controller: ctrlGastos,
            activetab: "gastos"
        })
        .when("/promos", {
            templateUrl: "htms/promociones.htm",
            controller: ctrlPromo,
            activetab: "promociones"
        }).when("/tickets", {
            templateUrl: "htms/tickets.htm",
            controller: ctrlTickets,
            activetab: "tickets"
        }).when("/ratings", {
            templateUrl: "htms/ratings.htm",
            controller: ctrlRatings,
            activetab: "ratings"
        })

       
    });

    app.controller("ctrlAlmacenes", ctrlAlmacenes);
    app.controller("ctrlProveedores", ctrProveedores);
    app.controller("ctrlInventario", ctrlInventario);

    app.controller("ctrlFacturas", ctrlFacturas);
    app.controller("ctrlClientes", ctrlClientes);
    app.controller("ctrlConfigFactuas", ctrlConfigFacturas);
    app.controller("ctrlProductosFacturas", ctrlProductosFacturas);
    app.controller("ctrlGenerarFactura", ctrlGenerarFactura);
    app.controller("ctrlEnvioCorreos", ctrlEnvioCorreos);

    app.controller("ctrlContactos", ctrlContactos);
    app.controller("ctrlInventarioUnidad",ctrlInventarioUnidad);
    app.controller("ctrlInsumosCompuestos",ctrlCompuesto);
    app.controller("ctrlCrudCompuesto",ctrlCrudCompuesto);
    app.controller("ctrlSesiones",ctrlSessiones);
    app.controller("ctrlProductividad",ctrlProductividad);
    app.controller("ctrlPromoMod",ctrlPromoMod);
    app.service("usersService",usersService);
    app.service("pagosService",pagosService);
    app.service("gastosService",gastosService);
    app.service("promoService",promoService);
    app.service("ticketsService",ticketsService);
    app.service("plotService",plotService);
    app.service("excelExportService",excelExportService);
    app.service("ratingsService",ratingsService);
    app.controller("ctrlGastosCategorias",ctrlGastosCategorias);
    app.controller("graficasCtrl",ctrlGraficas);
    app.controller("ctrlNavBar",ctrlNavBar);
    
      app.directive('starRating', starRating);
    
    
})()