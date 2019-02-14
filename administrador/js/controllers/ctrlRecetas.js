

function ctrlRecetas($scope, $http, $route) {
    $http.get("PHP/new/recetas/buscarPlatillo.php", { params: { q: "" } }).then(function (resp) {
        $scope.platillos = resp.data;
        $scope.platilloSelected = null;


    });

    function establecerInsumosActuales() {
        $http.get("PHP/new/recetas/fetchData.php", { params: { accion: "insumosProveedorPlatillo", id: $scope.platilloSelected.id } }).then(function (resp) {
            var data = resp.data;
            console.log(data);
            var len = data.length; 
            if (len != 0) {
                var i = 0;
                for (; i < len; i++) {
                    var insumo = data[i];
                    if ($scope.recetas[i].id == insumo.idInsumo) {
                        $scope.recetas[i].almacen = insumo.idAlmacen.toString();
                        $scope.recetas[i].proveedor = insumo.idProveedor.toString();
                    }
                }
            }
           
        });
    }
    $scope.establecerInsumos = function () {
        var recetas = $scope.recetas; var len = recetas.length; var i = 0;
        var fail = false;
        for (; i < len; i++) {
            var r = recetas[i];
            if (r.almacen == undefined) {
                toastr.info("Debes de seleccionar el almacen actual para todos los insumos");
                fail = true;
                break;
            }
            if (r.proveedor == undefined) {
                toastr.info("Debes de seleccionar el proveedor actual para todos los insumos");
                fail = true;
                break;
            }
            
        }

        if (!fail) {

            $http.post("PHP/new/recetas/accionRecetas.php?accion=establecerValoresActuales", { data: recetas, idPlatillo: $scope.platilloSelected.id }).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Platillo actualizado correctamente");
                } else {
                    toastr.error("Upsss... hubo un error, intentalo mas tarde");

                }
            });
        }
    }

    function getAlmacenesInsumo(id,index) {
        $http.get("PHP/new/recetas/fetchData.php", { params: { accion: "almacenesInsumo",id:id } }).then(function (resp) {
            $scope.recetas[index].almacenes = resp.data;
        });
    }
    function getProveedoresInsumo(id, index) {
        $http.get("PHP/new/recetas/fetchData.php", { params: { accion: "insumoProveedores", id: id } }).then(function (resp) {
            $scope.recetas[index].proveedores = resp.data;
        });
    }
    $scope.$route = $route;
    //console.log($route.current);
    $scope.platillos = []; // pool de platillos buscados
    $scope.qPlatillo = "";
    $scope.qInsumo = { search: "" };
    $scope.costoPlatillo = 0;
    $scope.platilloSelected = null;
    $scope.recetas = [];
    $scope.insumos = [];
    $scope.buscarPlatillo = function () {
        $http.get("PHP/new/recetas/buscarPlatillo.php", { params: { q: $scope.qPlatillo } }).then(function (resp) {
            $scope.platillos = resp.data;
            $scope.platilloSelected = null;
      

        });

    }
    function obtenerRecetas(idPlatillo) {
        return $http.get("PHP/new/recetas/fetchData.php", { params: { "accion": "obtenerInsumosPlatillo", "idPlatillo": idPlatillo } });

    }
    function buscarInsumos(q) {
        return $http.get("PHP/new/recetas/fetchData.php", { params: { "q": q, "accion": "buscarInsumos" } });

    }
    var once = true;
    function calcularTotal() {
        var recetas = $scope.recetas; var len = recetas.length; var i = 0;
        var total = 0;
        for (; i < len; i++) {
            total += parseFloat(recetas[i].costo);

        }
        return total.toFixed(3);
    }
    $scope.seleccionarPlatillo = function (index) {
        $scope.platilloSelected = $scope.platillos[index];
        $scope.platillos = [];
        obtenerRecetas($scope.platilloSelected.id).then(function (resp) {
            $scope.recetas = resp.data;
            var len = resp.data.length; var i = 0;
            for (; i < len; i++) {
                var id = resp.data[i].id;
                getAlmacenesInsumo(id, i);
                getProveedoresInsumo(id, i);
            }
            establecerInsumosActuales();
            $scope.costoPlatillo = calcularTotal();
        });

    }

    $scope.buscarInsumo = function () {
        var q = $scope.qInsumo.search;
        buscarInsumos(q).then(function (resp) {
            $scope.insumos = resp.data;
            console.log($scope.insumos);
        });



    }
    function isInsumoRepetido(idInsumo) {
        var recetas = $scope.recetas;
        var len = recetas.length; var i = 0;
        for (; i < len; i++) {
            var r = recetas[i];
            if (r.id == idInsumo) {
                return r;
            }
        }
        return null;

    }
    function agregarInsumoPlatillo(idInsumo, cantidad) {
        var idPlatillo = $scope.platilloSelected.id;
       return $http.post("PHP/new/recetas/accionRecetas.php?accion=agregarInsumo", { idInsumo: idInsumo, idPlatillo: idPlatillo, cantidad: cantidad });
    }
    function actualizarCantidad(idInsumo, cantidad) {

        return $http.post("PHP/new/recetas/accionRecetas.php?accion=actualizarCantidad", { cantidad: cantidad, idInsumo: idInsumo });

    }

    function eliminarInsumo(idInsumo) {
        return $http.post("PHP/new/recetas/accionRecetas.php?accion=eliminarInsumo", { idInsumo: idInsumo, idPlatillo: $scope.platilloSelected.id });

    }

    function actulizarCosto(idPlatillo,costo) {
        return $http.post("PHP/new/recetas/accionRecetas.php?accion=actualizarCostoPlatillo", { costo: costo, idPlatillo:idPlatillo });

    }
    $scope.agregarInsumo = function (insumo) {
        //var insumo = $scope.insumos[index];
        if (isInsumoRepetido(insumo.id) != null) {
            toastr.info("Este insumo ya esta agregado a este platillo", "Insumo existente");

        } else {
            var mensaje =""; var isCompuesto = false;
            if(insumo.costo_unitario){
            mensaje= "Cantidad de insumo " + insumo.nombre + " en "+insumo.unidad+" en el platillo " + $scope.platilloSelected.nombre;
            }else{
                mensaje="Introduce la proporcion de este insumo compuesto, este factor sera aplicado a todos los insumos que contiene "+insumo.nombre;
                isCompuesto = true;
            }
            bootbox.prompt({
                title: mensaje,
                inputType: 'number',
                callback: function (result) {
                    if (parseFloat(result) < 0) {
                        toastr.error("Cantida invalid", "Error");
                    }
                    else {
                        if (result != null) {
 if(isCompuesto){
    var idPlatillo = $scope.platilloSelected.id;
    $http.post("PHP/new/recetas/accionRecetas.php?accion=setCompuesto",{idPlatillo:idPlatillo,id:insumo.id,cantidad:result}).then(function(resp){
        var insumos = resp.data.insumos; var len= insumos.length;
   var i = 0;
   for(;i<len;i++){
       var ins = insumos[i];
       $scope.recetas.push(ins);
       getAlmacenesInsumo(ins.id,i);
       getProveedoresInsumo(ins.id,i);
   }


    });
    
 }else{

                            agregarInsumoPlatillo(insumo.id, result).then(function (resp) {
                                if (resp.data.exito) {
                                    insumo.cantidad = result;
                                    insumo.costo = parseFloat(insumo.costo_unitario) * parseFloat(result);
                                    insumo.costo = insumo.costo.toFixed(4);
                                    $scope.recetas.push(insumo);
                                    $scope.costoPlatillo = calcularTotal();
                                    var index_receta =  $scope.recetas.length -1;
                                    getAlmacenesInsumo(insumo.id,index_receta);
                                    getProveedoresInsumo(insumo.id,index_receta);

                                    toastr.success("Se ha agregado el insumo al platillo exitosamente", "Has agregado un insumo");
                                } else {
                                    toastr.error("Upss... No se ha podido agregar el insumo, intentalo mas tarde", "Error =(");

                                }

                            });
                        }
                        }

                    }
                }
            });
        }

       
    }

    $scope.modificarInsumo = function (insumo) {
        //var insumo = $scope.recetas[index];
        bootbox.prompt({
            title: "Ingresa la nueva cantidad de "+insumo.nombre+ " en "+insumo.unidad,
            inputType: 'number',
            callback: function (result) {
                if (result < 0) {
                    toastr.error("Cantiad invalida, solo valores positivos¡", "Error");

                }
                if (result > 0 && result != null) {
                   
                    actualizarCantidad(insumo.id, result).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha actualizado la cantidad del insumo", "Platillo actualizo");
                            insumo.cantidad = result;
                            var nuevo_costo = parseFloat(result*insumo.costo_unitario);
                            insumo.costo = nuevo_costo.toFixed(3);
                            $scope.costoPlatillo = calcularTotal();

                        } else {
                            toastr.error("Upsss... algo salio mal, intanlo mas tarde", "Error =(");
                        }
                    });
                }

            }
        });
    }
    function eliminarInumoById(id) {
        var recetas = $scope.recetas;
        var len = recetas.length; var i = 0;
        for (; i < len; i++) {
            if (recetas[i].id == id) {
                $scope.recetas.splice(i, 1);
            }
        }
    }
    $scope.eliminarInsumo = function (insumo) {
        //var insumo = $scope.recetas[index];
        bootbox.confirm({
            title: "En verdad quieres eliminar "+insumo.nombre+" de este platillo?",
            message: "Seguro que deseas eliminar este insumo del platillo?",
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
                    eliminarInsumo(insumo.id).then(function (resp) {
                        if (resp.data.exito) {
                            //eliminarInumoById(insumo.id);
                            $scope.costoPlatillo = calcularTotal();
                         $scope.recetas.splice($scope.recetas.indexOf(insumo),1);

                            toastr.success("Insumo eliminado exitosamente", "Has eliminado un insumo del platillo");
                        } else {
                            toastr.error("Upss... algo ha salido mal, intentalo mas tarde", "Error =(");
                        }


                    });
                }
            }
        });

    }

    $scope.actulizarCostoPlatillo = function () {
        actulizarCosto($scope.platilloSelected.id, $scope.costoPlatillo).then(function (resp) {
            if (resp.data.exito) {
                toastr.success("Se ha actualizado el costo del platillo", "Platillo actulizado");
            } else {
                toastr.error("Upsss... algo salio mal, intantalo mas tarde", "Error =(");
            }

        });

    }

}