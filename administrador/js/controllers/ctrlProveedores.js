function ctrProveedores($scope, $http) {
    $scope.proveedor = { nombre: "", RFC: "", email: "", domicilio: "" };
    $scope.proveedores = [];
    $scope.accion = 0; // 0 -> nuevo , 1 ->modificar
    $scope.headerText = "Nuevo Proveedor";
    $http.get("PHP/new/inventario/fetchData.php", { params: { accion: "obtenerProveedores" } }).then(function (resp) {
        $scope.proveedores = resp.data;

    });

    function ValidaRfc(rfcStr) {
        var strCorrecta;
        strCorrecta = rfcStr;
        if (rfcStr.length == 12) {
            var valid = '^(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
        } else {
            var valid = '^(([A-Z]|[a-z]|\s){1})(([A-Z]|[a-z]){3})([0-9]{6})((([A-Z]|[a-z]|[0-9]){3}))';
        }
        var validRfc = new RegExp(valid);
        var matchArray = strCorrecta.match(validRfc);
        if (matchArray == null) {
            return false;
        }
        else {
            return true;
        }

    }
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function validarProveedor(proveedor) {
        var errores = [];
         if(proveedor.nombre==""){
            errores.push("Nombre no puede estar vacio");

         }
         if(proveedor.domicilio==""){
            errores.push("domicilio no puede estar vacio");
                        
         }
        if (!ValidaRfc(proveedor.RFC)) {
            errores.push("RFC incorrecto");
        }
        if (!validateEmail(proveedor.email)) {
            errores.push("Correo con formato incorrecto");
        }
        return errores;

    }
    function agregarProveedor(proveedor) {
        return $http.post("PHP/new/inventario/accionInventario.php?accion=altaProveedor", proveedor);

    }
    function modificarProveedor(proveedor) {
        return $http.post("PHP/new/inventario/accionInventario.php?accion=modificarProveedor", proveedor);

    }
    $scope.submitProveedor = function () {
        var proveedor = $scope.proveedor;
        var errores = validarProveedor(proveedor);
        if (errores.length > 0) {
            toastr.error(errores.join("<br>"), "Error =(");
        } else {
            switch ($scope.accion) {
                case 0:
                    agregarProveedor(proveedor).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Proveedor agregado exitosamente", "Se ha agregado un nuevo proveedor");
                            proveedor.id = resp.data.idProveedor;
                            proveedor.cantidad = 0;
                            $scope.proveedores.push(proveedor);
                            $scope.proveedor = { nombre: "", RFC: "", email: "", domicilio: "" };

                        } else {
                            toastr.error("Upsss... no se ha podido agregar proveedor, intentalo mas tarde", "Error =(");
                        }

                    });
                    break;
                case 1:
                    modificarProveedor(proveedor).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha actualizado la informacion del proveedor", "Proveedor actualizado");
                        } else {
                            toastr.error("Upsss... no se ha podidi actualzar este proveedor, intentalo mas tarde", "Error =(");

                        }
                        $scope.proveedor = { nombre: "", RFC: "", email: "", domicilio: "" };
                        $scope.headerText = "Nuevo Proveedor";
                        $scope.accion = 0;

                    });
                    break;
            }


        }
    }
    $scope.modificar = function (p) {
        //var p = $scope.proveedores[index];
        $scope.proveedor = p;
        $scope.headerText = "Editar proveedor Id:" + p.id;
        $scope.accion = 1;

    }
    function eliminarProvedor(id) {
        return $http.post("PHP/new/inventario/accionInventario.php?accion=eliminarProvedor", { id: id });
    }
    function eliminarProvById(id) {
        var provs = $scope.proveedores; var len = provs.length; var i = 0;
        for (; i < len; i++) {
            if (provs[i].id == id) {
                $scope.proveedores.splice(i, 1);
                break;
            }

        }

    }
    $scope.eliminar = function (p) {
        bootbox.confirm({
            title: "Seguro que deseas eliminar este proveedor",
            message: "Realmente estas seguro de eliminar a " + p.nombre+" del sistema?",
            buttons: {
                cancel: {
                    className:"btn-danger",
                    label: '<i class="fa fa-times"></i> Cancelar'
                },
                confirm: {
                    className:"btn-primary",
                    label: '<i class="fa fa-check"></i> Confirmar'
                }
            },
            callback: function (result) {
                if (result) {
                    var id = p.id;
                    eliminarProvedor(id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Se ha eliminado proveedor con exito");
                            eliminarProvById(id);
                        } else {
                            toastr.error("Upsss... ha ocurrido un error,contacta a soporte");
                        }
                    });
                }
            }
        });


    }

}