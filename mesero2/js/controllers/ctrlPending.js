function ctrlPending($scope, $rootScope, $http, $interval,$timeout) {

$scope.moreInfoLlevar = function(idPedido,id){
    
    $http.get("php/fetchData.php",{params:{accion:"infoLlevar",idPedido:idPedido,id:id}}).then(function(resp){
       var html ="<h2>"+resp.data.nombreCliente+"</h2>";
       html+="<h4>"+resp.data.nombre+" x"+resp.data.cantidad+"</h4>";
       html+="Favor de entregar al domicilio:<br>";
       html+="<label>"+resp.data.domicilio+"</label><br>";
      html+="<div style='margin:0 auto;text-align:center;'>"
      html+="<img src='../images/"+resp.data.imagepath+"' class='img-responsive img-thumbnail' style='width:300px;height:300px' />";
      html+="</div>";
        bootbox.alert({
            title:"Pedido para llevar",
            message: html,
            callback: function () {


            }
        })

    });
}


    function getElapsedTime(date_now, date_future) {
        var delta = Math.abs(date_future - date_now) / 1000;

        // calculate (and subtract) whole days
        var days = Math.floor(delta / 86400);
        delta -= days * 86400;

        // calculate (and subtract) whole hours
        var hours = Math.floor(delta / 3600) % 24;
        delta -= hours * 3600;

        // calculate (and subtract) whole minutes
        var minutes = Math.floor(delta / 60) % 60;
        delta -= minutes * 60;

        // what's left is seconds
        var seconds = delta % 60;
        if (isNaN(hours) || isNaN(minutes) || isNaN(seconds)) {
            return null;
        }
        seconds = parseFloat(seconds.toFixed(0));
        var str_days = (days > 0) ? (days + " dias ") : "";
        var str_hours = (hours > 0) ? (hours + " horas ") : "";
        var str_seconds = (seconds > 0) ? (seconds + " segundos ") : "";
        var str_minutes = (minutes > 0) ? (minutes + " minutos ") : "";
        return "Hace " + str_days +str_hours+str_minutes+ str_seconds;



    }
    function refreshTime() {
        var platillos = $scope.platillos; var len = platillos.length; var i = 0;
        var hoy = new Date();
        for (; i < len; i++) {
            var f = platillos[i].fecha_llegada.replace(/-/g, '/');
            var fecha = new Date(f);
            fecha = new Date(fecha.getTime()); // corrcion hora del servidor por 5 horas

            $scope.platillos[i].tiempo = getElapsedTime(hoy, fecha);
           
        }
    }
    $interval(refreshTime,1000);
    $interval(forceUpdate,15000);
    

    $scope.usr = usr;
    $scope.fecha = new Date();
    $interval(function () {
        $scope.fecha = new Date();
    }, 1000);
    $scope.soloBebidas = false;
    $scope.filtarBebidas = function () {
        $scope.soloBebidas = !$scope.soloBebidas;
    }
    function forceUpdate() {
        $.get("php/fetchData.php", { accion: "pending", force_update: "1" }, function (data) {
            data = data.trim();
            var j = JSON.parse(data);
            if (j.update) {
                $scope.platillos = j.platillos;
                $scope.$apply();
            
            }



        });

    }
    $scope.show = { enCocina: true, paraServir: true };
    $scope.toggleContainer = function (section) {
        switch (section) {
            case "cocina":
                $scope.show.enCocina = !$scope.show.enCocina;
                break;
            case "porServir":
                $scope.show.paraServir = !$scope.show.paraServir;

                break;
        }

    }
    $scope.platillos = [];
    function getPending(force_update)
    {
        return $http.get("php/fetchData.php", { params: { accion: "pending", force_update:force_update } });
    }
    getPending('1').then(function (resp) {
        

        $scope.platillos = resp.data.platillos;

    });
    function updatePending() {


        $.get("php/fetchData.php", { accion: "pending", force_update: "0" }, function (data) {
            data = data.trim();
            var j = JSON.parse(data);
            if (j.update) {
                $scope.platillos = j.platillos
                $scope.platillos = angular.copy($scope.platillos);
                
                $scope.$apply();
                if(location.href.search("mesa?")!=-1){
                ion.sound({
                    sounds: [
                        {name: "door_bell"}
                    ],
                
                    // main config
                    path: "sounds/",
                    preload: true,
                    multiplay: true,
                    volume: 100
                });
                
                // play sound
                ion.sound.play("door_bell");
            }
            }



        });
    }
    $interval(updatePending, 4500);
    $interval(forceUpdate, 15000);

    function servirPlatilloDb(cantidad,idPedidoPlatillo) {
        return $http.post("php/accionMesero.php?accion=servirPlatillo", { cantidad: cantidad, id: idPedidoPlatillo });

    }

    $scope.servirPlatillo = function (index) {
        var platillo = $scope.platillos[index];
        var cantidad_disponible = platillo.cantidad_terminado - platillo.cantidad_servido;
        if (cantidad_disponible == 1) {
            servirPlatilloDb(1, platillo.id).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success(resp.data.msg);
                    updatePending();
                } else {
                    toastr.error("Upsss... No se pudo servir platillo en el sistema, contacta a soporte tecnico");
                }

            });
        } else {
            bootbox.prompt({
                title: "Que Cantidad del paltillo " + platillo.nombre + " Serviste en " + platillo.mesa + "?",
                inputType: 'number',
                callback: function (result) {
                    if (result != null && result > 0) {
                        if (result <= cantidad_disponible) {
                            servirPlatilloDb(result, platillo.id).then(function (resp) {
                                if (resp.data.exito) {
                                    toastr.success(resp.data.msg);
                                    updatePending();
                                } else {
                                    toastr.error("Upsss... No se pudo servir platillo en el sistema, contacta a soporte tecnico");
                                }

                            });
                        } else {
                            toastr.info("Cantidad invalida se puede servir un maximo de " + cantidad_disponible + " del platillo " + platillo.nombre);
                        }

                    }

                }
            });

        }
    }

}