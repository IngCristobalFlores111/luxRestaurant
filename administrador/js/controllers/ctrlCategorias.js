function ctrlCategorias($scope, $http) {
    $scope.categorias = [];
    $scope.unidades = [];
    $scope.unidadesFactura = [];
    $scope.nuevo = { categoria: "", unidad: "",unidadFacturacion:"" };

    $scope.agregaUnidadFacturacion = function () {
        var unidad = $scope.nuevo.unidadFacturacion;
        if (unidad == "") {
            toastr.warning("Unidad no puede estar vacia", "Valor invalido");
        } else {
            $http.post("PHP/new/categorias/accionCategorias.php?accion=agregarUnidadFact", { unidad: unidad }).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Unidad agregada exitosamente", "Has agregado una unidad");
                    $scope.unidadesFactura.push({ id: resp.data.idUnidad, nombre: unidad, cantidad: 0 });

                } else {
                    toastr.error("Upsss... no se pudo dar de alta unidad, intentalo mas tarde", "Error =(");

                }
                $scope.nuevo.unidadFacturacion = "";
            });
        }

    }
    $scope.modificarUnidadFact = function (unidad) {

        //var unidad = $scope.unidadesFactura[index];
        bootbox.prompt({
            title: "Asigna un nuevo nombre a la unidad '"+unidad.nombre+"'",
            inputType: 'text',
            callback: function (result) {
                if (result != null && result != "") {
                    $http.post("PHP/new/categorias/accionCategorias.php?accion=modificarUnidadFact", { nombre: result, idUnidad: unidad.id }).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha modificado la unidad exitosamente", "Unidad Actualizada");
                            // $scope.unidadesFactura[index].nombre = result;
                            unidad.nombre = result;
                        } else {
                            toastr.error("Upsss... No se ha podido modificar la unidad, intentalo mas tarde", "Error =(");
                        }

                    });
                }
            }
        });

    }
    function eliminarUnidadFactura(id) {
        var units = $scope.unidadesFactura;
        var len = units.length; var i = 0;
        for (; i < len; i++) {
            if (units[i].id == id) {
                $scope.unidadesFactura.splice(i, 1);
                break;
            }
        }

    }
    $scope.eliminarUnidadFact = function (unidad) {
    //    var unidad = $scope.unidadesFactura[index];
        bootbox.confirm({
            title: "Seguro que deseas eliminar la unidad '"+unidad.nombre+"'",
            message: "Realmente deseas eliminar esta unidad del sistema?",
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
                    $http.post("PHP/new/categorias/accionCategorias.php?accion=eliminarUnidadFact", { idUnidad: unidad.id }).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha eliminado la unidad exitosamente", "Unidad Eliminada");
                           // $scope.unidadesFactura.splice(index, 1);
                            eliminarUnidadFactura(unidad.id);
                        } else {
                            toastr.error("Upsss... No se ha podido eliminar esta unidad,intentalo mas tarde", "Error =(");
                        }

                    });
                }
            }
        });

    }

    $http.get("PHP/new/categorias/fetchData.php", { params: { accion: "categorias" } }).then(function (resp) {
        $scope.categorias = resp.data;
    });
    $http.get("PHP/new/categorias/fetchData.php", { params: { accion: "unidades" } }).then(function (resp) {
        $scope.unidades = resp.data.u1;
        $scope.unidadesFactura = resp.data.u2;

    });
    // CRUD Categorias
    function agregarCategoria(nombre) {
        return $http.post("PHP/new/categorias/accionCategorias.php?accion=agregarCategoria", { nombre: nombre });

    }
    function modificarCategoria(nombre, idCategoria) {
        return $http.post("PHP/new/categorias/accionCategorias.php?accion=modficarNombreCategoria", { nombre: nombre, idCategoria:idCategoria });
    }
    function eliminarCategoria(idCategoria) {
        return $http.post("PHP/new/categorias/accionCategorias.php?accion=eliminarCategoria", { idCategoria: idCategoria });

    }
    // CRUD Unidades
    function agregarUnidad(nombre) {
        return $http.post("PHP/new/categorias/accionCategorias.php?accion=agregarUnidad", { nombre:nombre });
    }
    function modificarUnidad(nombre, idUnidad) {
        return $http.post("PHP/new/categorias/accionCategorias.php?accion=modificarUnidad", { nombre: nombre, idUnidad :idUnidad});
    }
    function eliminarUnidad(idUnidad) {
        return $http.post("PHP/new/categorias/accionCategorias.php?accion=eliminarUnidad", { idUnidad: idUnidad });
    }

    $scope.agregarCategoria = function () {
        var categoria = $scope.nuevo.categoria;
        if (categoria == "") {
            toastr.warning("Nueva categoria no puede estar vacia", "Valor invalido");
        } else {
            agregarCategoria(categoria).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Se ha agregado la categoria exitosamente", "Has agregado una nuva categoria");
                    $scope.categorias.push({ id: resp.data.idCategoria, nombre:categoria,cantidad:0 });
                } else {
                    toastr.error("Upsss... no se ha podido agregar categoria, intentalo mas tarde", "Error =(");
                }
                $scope.nuevo = { categoria: "" };

            });

        }
    }
    $scope.modCategoria = function (categoria) {
        //var categoria = $scope.categorias[index];
        bootbox.prompt({
            title: "Establece el nuevo nombre de la categoria "+categoria.nombre,
            inputType: 'text',
            callback: function (result) {
                if (result != null && result != "") {
                    modificarCategoria(result, categoria.id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha actulizado en nombre de la categoria", "Categoria actualizada");
                            // $scope.categorias[index].nombre = result;
                            categoria.nombre = result;
                        } else {
                            toastr.error("Upsss... No seha podido actaulziar la categoria, intentalo mas tarde", "Error =(");

                        }

                    });
                }
            }
        });
    }
    function eliminarCategoriaById(id) {
        var cats = $scope.categorias; var len = cats.length; var i = 0;
        for (; i < len; i++) {
            if (cats[i].id == id) {
                $scope.categorias.splice(i, 1);
                break;
            }
        }

    }
    $scope.eliminarCategoria = function (categoria) {
       // var categoria = $scope.categorias[index];
        bootbox.confirm({
            title: "Estas seguro que deseas eliminar esta categoria?",
            message: "En verdad deseas eliminar la categoria '" + categoria.nombre+"'",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function (result) {
                if (result) {
                    eliminarCategoria(categoria.id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha eliminado la categoria " + categoria.nombre + " Exitosamente", "Has eliminado una categoria");
                           // $scope.categorias.splice(index, 1);
                            eliminarCategoriaById(categoria.id);
                        } else {
                            toastr.error("Upsss...No se ha podido eliminar la categoria, intentalo mas tarde", "Error =(");
                        }

                    });

                }
            }
        });

    }
    $scope.agregarUnidad = function () {
        var unidad = $scope.nuevo.unidad;
        if (unidad == "") {
            toastr.warning("Unidad no puede ser vacia", "Valor invalido");
        } else {
            agregarUnidad(unidad).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Unidad agregada exitosmamente", "Has agregado una unidad");
                    $scope.unidades.push({ id: resp.data.idUnidad, nombre: unidad, cantidad:0 });
                } else {
                    toastr.error("Upsss... no se ha podido agregar unidad, intentalo mas tarde", "Error =(");
                }

            });
        }
    }

    $scope.modificarUnidad = function (unidad) {
        //var unidad = $scope.unidades[index];
        bootbox.prompt({
            title: "Establece un Nuevo Nombre de la unidad "+unidad.nombre,
            inputType: 'text',
            callback: function (result) {
                if (result != null && result != "") {
                    modificarUnidad(result, unidad.id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha actulizado la unidad exitosamente", "Unidad Actulizada");
                            //  $scope.unidades[index].nombre = result;
                            unidad.nombre = result;
                        }

                    });
                }
            }
        });
    }
    function eliminarUnidadById(id) {
        var units = $scope.unidades; var len = units.length; var i = 0;
        for (; i < len; i++) {
            if (units[i].id == id) {
                $scope.unidades.splice(i, 1);
                break;
            }
        }
    }
    $scope.eliminarUnidad = function (unidad) {
        //var unidad = $scope.unidades[index];
        bootbox.confirm({
            title: "Estas seguro que deseas eliminar esta unidad?",
            message: "Realmente deseas eliminar la unidad '"+unidad.nombre+"'",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm'
                }
            },
            callback: function (result) {
                if (result) {
                    eliminarUnidad(unidad.id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha eliminado la unidad exitosamente", "Unidad eliminada");
                            //$scope.unidades.splice(index, 1);
                            eliminarUnidadById(unidad.id);
                        } else {
                            toastr.error("Upsss... no se ha podido eliminar esta unidad,intentalo mas tarde", "Error =(");
                        }

                    });
                }
            }
        });
    }

}