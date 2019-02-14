function ctrlMesa($scope, $rootScope, $http, $interval, $routeParams) {
    $scope.platillos = [];
    $scope.platillosMesa = [];
 // nuevo promos 25/9/2017
  $scope.$on("aplicarPromo",function(event,data){  // evento de promocion 
    $http.post("php/accionMesero.php?accion=agregarPlatillo", { force: false, 
        comentario: data.comentarios, 
        cantidad: data.cantidad_pedido*data.cantidad,
         idplatillo: data.idPlatillo, 
         idpedido: $scope.mesa.idpedido, 
         categoria: data.idCategoria,
         preparado:data.preparado }).then(function (res) {
            if (res.data.depleted) {
                var depleted = res.data.depleted;
                var len = depleted.length; var i = 0;
                var content = '';
                for (; i < len; i++) {
                    var d = depleted[i];
                    content = "insumo:" + d.insumo + ", cantidad actual:" + d.cantidad, ",cantidad necesaria:" + d.cantidad_platillo + "<br>";
                }
                toastr.info("No hay suficientes insumos para vender este platillo¡<br>" + content,"No suficiente inventario");
                bootbox.confirm({
                    title: "Vender de todas maneras?",
                    message: "Deseas vender " + data.nombre+" de igual manera?",
                    buttons: {
                        cancel: {
                            className: "btn-danger",
                            label: '<i class="fa fa-times"></i> Cancel'
                        },
                        confirm: {
                            className:"btn-primary",
                            label: '<i class="fa fa-check"></i> Confirm'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            $http.post("php/accionMesero.php?accion=agregarPlatillo", { force: true, comentario: data.comentarios, cantidad: data.cantidad_pedido*data.cantidad, idplatillo: data.idPlatillo, idpedido: $scope.mesa.idpedido, categoria: data.idCategoria }).then(function (resp) {
                                if (resp.data.exito) {
                                    var platillo = data;
                                    platillo.cantidad_servido = 0;
                                    platillo.cantidad = data.cantidad_pedido*data.cantidad;
                                    platillo.idPedidoPlatillo = resp.data.idPedidoPlatillo;
                                    $scope.platillosMesa.push(angular.copy(platillo));
                                    toastr.success("Platillo pedido exitosamente");
                                    platillo.abierto = false;
                                } else {
                                    toastr.error("Upsss... algo salio mal, intentalo mas tarde");
                                }

                            });

                        }
                    }
                });
            } else {
                if (res.data.exito) {
                    var platillo = data;
                    
                    platillo.cantidad_servido = 0;
                    platillo.idPedidoPlatillo = res.data.idPedidoPlatillo;
                    $scope.platillosMesa.push(angular.copy(platillo));
                    toastr.success("Platillo pedido exitosamente");
                    platillo.abierto = false;
                } else {
                    toastr.error("Upsss... algo salio mal, intentalo mas tarde");
                }
            }

    });
        
  });

 //

$scope.mostrarPromos =function(){
    $("#modalPromos").modal("show");
}

    $scope.show = { platillos: true, pedidos: true };
    $scope.hideShow = function (tipo) {
        if (tipo == 1) {
            $scope.show.pedidos = !$scope.show.pedidos;
        } else {
            $scope.show.platillos = !$scope.show.platillos;

        }
    }
    $scope.modal = { nombre: "", img: "", descripcion: "" };
    $scope.mesa = { fecha_llegada: new Date(), ocupado: 0, comensales: 0, idmesa: 0, nombre: "", idpedido: 0, comentario: "" };

    $scope.abrirMesa = function () {
        $scope.mesa.ocupado = 1;

    }
    function restarPlatilloBd(idPedidoPlatillo) {
        return $http.post("php/accionMesero.php?accion=restarPlatillo", { id: idPedidoPlatillo });

    }
    function abrirMesaBd() {
        return $http.get("php/accionMesero.php", { params: { accion: "abrirMesa", idMesa: $scope.mesa.idmesa, comensales: $scope.mesa.comensales, comentario: $scope.mesa.comentario } });

    }


    function init() {
        //  var url = location.href;
        var idMesa = $routeParams.id;

        if (!idMesa) {
            location.href = "index.html";
        } else {

            $http.get("php/fetchData.php", { params: { accion: 'initMesa', idMesa: idMesa } }).then(function (resp) {
                var data = resp.data;
                var mesa = resp.data.mesa;
                if (mesa) {
                    if (mesa.fecha_llegada != null) {
                        mesa.fecha_llegada = new Date(mesa.fecha_llegada.replace(/-/g, '/'));

                    } else {
                        mesa.fecha_llegada = new Date();
                    }
                    $scope.mesa = mesa;
                    $scope.platillos = data.platillos;
                    $scope.platillosMesa = data.platillosMesa;
                    if (mesa.ocupado == 0) {
                        bootbox.prompt({
                            title: "Cuantos Comensales han llegado?",
                            inputType: 'number',
                            callback: function (NoComensales) {
                                if (NoComensales != null && NoComensales > 0) {
                                    $scope.mesa.comensales = parseInt(NoComensales);
                                    bootbox.prompt({

                                        title: "Tienes algun comentario para este pedido?",
                                        inputType: "text",
                                        callback: function (comentario) {
                                            $scope.mesa.comentario = comentario;
                                            $scope.$apply();
                                            abrirMesaBd().then(function (resp) {
                                                $scope.mesa.idpedido = resp.data;
                                                $scope.mesa.fecha_llegada = new Date();
                                                $scope.mesa.ocupado = 1;
                                                location.reload();
                                            });

                                        }
                                    });
                                } else {
                                    bootbox.prompt({

                                        title: "Tienes algun comentario para este pedido?",
                                        inputType: "text",
                                        callback: function (comentario) {
                                            $scope.mesa.comensales = 1;
                                            $scope.mesa.comentario = (comentario == undefined) ? "sin comentarios" : comentario;
                                            $scope.$apply();
                                            abrirMesaBd().then(function (resp) {
                                                $scope.mesa.idpedido = resp.data;
                                                $scope.mesa.fecha_llegada = new Date()
                                                $scope.mesa.ocupado = 1;
                                                location.reload();

                                            });

                                        }
                                    });
                                }
                            }
                        });
                    } else {


                    }
                } else {
                    location.href = "index.html";

                }
            });
        }


    }
    init();
    $scope.openPlatillo = function (p) {
        p.abierto = !p.abierto;
    }
    $scope.agregarPlatillo = function (platillo) {
        // var platillo = $scope.platillos[index];
        if (platillo.cantidad < 0) {
            toastr.info("Cantidad de platillo invalida");
        } else {
            if (platillo.cantidad == 0 || platillo.cantidad == undefined) {
                platillo.cantidad = 1;
            }
            $http.post("php/accionMesero.php?accion=agregarPlatillo", { preparado:platillo.preparado,force: false, comentario: platillo.comentarios, cantidad: platillo.cantidad, idplatillo: platillo.idplatillo, idpedido: $scope.mesa.idpedido, categoria: platillo.idCategoria }).then(function (res) {

                if (res.data.depleted) {
                    var depleted = res.data.depleted;
                    var len = depleted.length; var i = 0;
                    var content = '';
                    for (; i < len; i++) {
                        var d = depleted[i];
                        content = "insumo:" + d.insumo + ", cantidad actual:" + d.cantidad, ",cantidad necesaria:" + d.cantidad_platillo + "<br>";
                    }
                    toastr.info("No hay suficientes insumos para vender este platillo¡<br>" + content,"No suficiente inventario");
                    bootbox.confirm({
                        title: "Vender de todas maneras?",
                        message: "Deseas vender " + platillo.nombre+" de igual manera?",
                        buttons: {
                            cancel: {
                                className: "btn-danger",
                                label: '<i class="fa fa-times"></i> Cancel'
                            },
                            confirm: {
                                className:"btn-primary",
                                label: '<i class="fa fa-check"></i> Confirm'
                            }
                        },
                        callback: function (result) {
                            if (result) {
                                $http.post("php/accionMesero.php?accion=agregarPlatillo", { force: true, comentario: platillo.comentarios, cantidad: platillo.cantidad, idplatillo: platillo.idplatillo, idpedido: $scope.mesa.idpedido, categoria: platillo.idCategoria }).then(function (resp) {
                                    if (resp.data.exito) {
                                        platillo.cantidad_servido = 0;
                                        platillo.idPedidoPlatillo = resp.data.idPedidoPlatillo;
                                        $scope.platillosMesa.push(angular.copy(platillo));
                                        toastr.success("Platillo pedido exitosamente");
                                        platillo.abierto = false;
                                    } else {
                                        toastr.error("Upsss... algo salio mal, intentalo mas tarde");
                                    }

                                });

                            }
                        }
                    });
                } else {
                    if (res.data.exito) {
                        platillo.cantidad_servido = 0;
                        platillo.idPedidoPlatillo = res.data.idPedidoPlatillo;
                        $scope.platillosMesa.push(angular.copy(platillo));
                        toastr.success("Platillo pedido exitosamente");
                        platillo.abierto = false;
                    } else {
                        toastr.error("Upsss... algo salio mal, intentalo mas tarde");
                    }
                }
            });


        }

    }

    function eliminarByID(id) {
        var platillos = $scope.platillosMesa;
        var len = platillos.length; var i = 0;

        for (; i < len; i++) {
            var p = platillos[i];
            if (p.idPedidoPlatillo == id) {
                $scope.platillosMesa.splice(i, 1);
                break;
            }
        }
    }

    $scope.restarPlatillo = function (platillo) {
      //  var platillo = $scope.platillosMesa[index];
        if (platillo.cantidad_servido == 0) {
            if (platillo.cantidad >= 0) {
                restarPlatilloBd(platillo.idPedidoPlatillo).then(function (resp) {
                   
                    if (resp.data.exito) {
                       // toastr.success("Platillo actualizado correctamente");
                    }
                });

                platillo.cantidad--;
                if (platillo.cantidad == 0) {
                    //eliminarByID(platillo.idPedidoPlatillo);
                    $scope.platillosMesa.splice($scope.platillosMesa.indexOf(platillo),1);
                }
            }
        } else {
            toastr.info("No es posible quitar platillo, ya esta preparandose en cocina");
        }

    }
    $scope.pagarCuenta = function () {
        var platillos = $scope.platillosMesa;
        var len = platillos.length; var i = 0;
        var platillos_no_servidos = false;
        for (; i < len; i++) {
            var cantidad = platillos[i].cantidad;
            var cantidad_servido = platillos[i].cantidad_servido;
            if (cantidad > cantidad_servido) {
                bootbox.confirm({
                    title: "¿Seguro que desas continuar?",
                    message: "Todavia hay platillos que no se han servido, pero puedes proceder a cobrar esta cuenta",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancelar'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Confirmar'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            $rootScope.$broadcast("cobrar", { platillos: $scope.platillosMesa, mesa: $scope.mesa });

                            $("#modalCobrar").modal("show");
                            platillos_no_servidos = true;
                        }
                    }
                });
                platillos_no_servidos = true;
                break;
            }
        }
        if (!platillos_no_servidos) {
            $("#modalCobrar").modal("show");
            $rootScope.$broadcast("cobrar", {platillos:$scope.platillosMesa,mesa:$scope.mesa});
        }
       
    }

    $scope.abrirModal = function (p, tipo) {
        if (tipo == 0) {
            $scope.modal = { nombre: p.nombre, img: p.imagepath, descripcion: p.descripcion };
        } else {
            var html = "<label >Comentarios:</label>" + p.comentarios;
            html += "<br><label>En espera x" + (p.cantidad - p.cantidad_servido) + "</label><br>";
            html += "<label>Servidos x" + p.cantidad_servido + "</label><br>";
            $scope.modal = { nombre: p.nombre, img: p.imagepath, descripcion: html };

        }
        $("#modalPlatilloDetalle").modal("show");
    }
    $scope.terminar = function () {
        console.log($scope.mesa);
        $http.post("php/accionMesero.php?accion=terminarCuenta", { idPedido: $scope.mesa.idpedido, idMesa: $scope.mesa.idmesa }).then(function (resp) {
            if (resp.data.exito) {
                toastr.success("Se ha terminado la cuenta exitosamente");
                location.href = "index.html";
            } else {
                toastr.error("Upsss... algo salio mal, intentalo mas tarde");
            }

        });
    }

}