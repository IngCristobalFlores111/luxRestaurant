function ctrlAlmacenes($scope, $http) {
  
    $scope.almacen = { nombre: "" };
    $scope.almacenes = [];
    $scope.almacenAccion = 0;
    $scope.headerAlmacen = "Nuevo Almacen";
    var AlmacenTmp = {};
    $http.post("PHP/new/inventario/fetchData.php?accion=obtenerAlmacenes").then(function (resp) {
        $scope.almacenes = resp.data;
    });
    function agregaNuevoAlmacen(nombre) {
        return $http.post("PHP/new/inventario/accionInventario.php?accion=nuevoAlmacen", { nombreAlmacen: nombre });
    }
    function editarAlmacen(nombre,idAlmacen)
    {
        return $http.post("PHP/new/inventario/accionInventario.php?accion=modificarAlmacen", { idAlmacen:idAlmacen, nombreAlmacen: nombre });

    }
    function eliminarAlmacen(idAlmacen) {

        return $http.post("PHP/new/inventario/accionInventario.php?accion=eliminarAlmacen", { idAlmacen:idAlmacen });
    }
    $scope.agregarAlmacen = function () {
        var nuevoNombre = $scope.almacen.nombre;
        if (nuevoNombre == "") {
            toastr.warning("Nombre del almacen no puede estar vacio", "Nombre invalido de almacen");

        } else {
            if ($scope.almacenAccion == 0) {
                agregaNuevoAlmacen(nuevoNombre).then(function (resp) {
                    if (resp.data.exito) {
                        toastr.success("Almacen agregado", "Has agregado un almacen");
                        $scope.almacenes.push({ id: resp.data.idAlmacen, nombre: nuevoNombre, cantidad: 0 });
                    } else {
                        toastr.error("Upsss... no se ha podido dar de alta este almacen, intentalo msa tarde", "Error =(");

                    }

                });
            }
            if ($scope.almacenAccion == 1) {
                editarAlmacen(nuevoNombre, AlmacenTmp.id).then(function (resp) {
                    if (resp.data.exito) {
                        toastr.success("Almacen editado exitosamente", "Has actualizado el nombre del almacen");
                        $scope.almacenes[AlmacenTmp.index].nombre = nuevoNombre;
                        $scope.almacen = { nombre: "" };
                        $scope.almacenAccion = 0;
                        $scope.headerAlmacen = "Nuevo Almacen";



                    } else {
                        toastr.error("Upsss... algo salio mal, intantalo  mas tarde", "Error =(");
                    }
                });
            }

        }

    }

    $scope.modificarAlmacen = function (index) {
        var a = $scope.almacenes[index];
        $scope.headerAlmacen = "Editar Almacen Id:" + a.id;
        $scope.almacen.nombre = a.nombre;
        $scope.almacenAccion = 1;
        AlmacenTmp.id = a.id;
        AlmacenTmp.index = index;

    }

    $scope.eliminarAlmacen = function (index) {
        var a = $scope.almacenes[index];
        bootbox.confirm({
            title: "Seguro que deseas eliminar este almacen?",
            message: "Seguro que deseas eliminar el almacen '"+a.nombre+"'",
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
                    eliminarAlmacen(a.id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Almacen eliminado exitosamente", "Has eliminado este almacen");
                            $scope.almacenes.splice(index, 1);
                        } else {
                            toastr.error("Upsss... no se ha podido eliminar el almacen", "Error =(");
                        }
                    });
                }
            }
        });



    }


}