function ctrlClientes($scope,$rootScope, $http) {



    $scope.tab = 1;

    $scope.setTab = function (tab) {
        $scope.tab = tab;
        if (tab == 2) {
            $scope.$broadcast("cargarContactos", $scope.cliente.id);
        }

    }

    $scope.estados = [];
    $scope.municipios = [];
    $scope.cliente = {
        nombre: "", RFC: "", email: "", direccion: "", noInt: "", noExt: "", colonia: "", codigoPostal: "", estado: "0", municipio:"0"
    };
    $scope.clientes = [];
    $scope.accion = 0;// 0-> agregar , 1->modificar
    $scope.headerForm = "Agregar Nuevo Cliente";
    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "estados" } }).then(function (resp) {
        $scope.estados = resp.data;

    });
    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "clientes" } }).then(function (resp) {
        if (resp.data[0].nombre != null) {
            $scope.clientes = resp.data;

        }
    });
    $scope.cargarMunis = function () {
        var idEstado = $scope.cliente.estado;
        $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "municipios", idEstado: idEstado } }).then(function (resp) {
            $scope.municipios = resp.data;
        });

    }
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function validateRFC(rfcStr) {
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
    function validarCliente(cliente) {
        var errores = [];
        for (var i in cliente) {
            if (cliente[i] == "") {
                errores.push(i + " No puede estar vacio");
            }
        }
        if (!validateEmail(cliente.email)) {
            errores.push("Email invalido");
        }
        if (!validateRFC(cliente.RFC)) {
            errores.push("RFC invalido");
        }
        


        return errores;
    }

    function agregarCliente(cliente) {
        return $http.post("PHP/new/facturacion/accionFacturacion.php?accion=altaCliente", { datos: cliente });

    }
    function modificarCliente(cliente) {
        return $http.post("PHP/new/facturacion/accionFacturacion.php?accion=modCliente", { datos: cliente });

    }
    function eliminarCliente(idCliente) {
        return $http.post("PHP/new/facturacion/accionFacturacion.php?accion=eliminarCliente", { idCliente: idCliente });

    }

    function buscarEstado(id) {
        var estados = $scope.estados; var len = estados.length; var i = 0;
        for (; i < len; i++) {
            var e = estados[i];
            if (e.id == id) {
                return e;
            }

        }
    }
    function buscarMuni(id) {
        var munis = $scope.municipios; var len = munis.length; var i = 0;
        for (; i < len; i++) {
            var e = munis[i];
            if (e.id == id) {
                return e;
            }

        }
    }

    $scope.submitCliente = function () {
        var cliente = $scope.cliente;
        var index = cliente.index;
        delete cliente.index;
        var errores = validarCliente(cliente);
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Errores en el formulario");
        } else {
            switch ($scope.accion) {
                case 0:
                    agregarCliente(cliente).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Cliente dado de alta exitosamente", "Se ha dado de alta un cliente");
                            var estado = buscarEstado(cliente.estado);
                            var muni = buscarMuni(cliente.municipio);
                            var domicilio = cliente.direccion + " " + cliente.noExt + " " + cliente.noInt + " cp." + cliente.codigoPostal + " " + muni.nombre + " " + estado.nombre;
                            cliente.domicilio = domicilio;
                            cliente.cantidad = 0;
                            cliente.id = resp.data.idCliente;
                            $scope.clientes.push(cliente);
                        } else {
                            toastr.error("Upsss... algo salio mal, intentalo mas tarde", "Error =(");
                        }
                        $scope.cliente = {
                            nombre: "", RFC: "", email: "", direccion: "", noInt: "", noExt: "", colonia: "", codigoPostal: "", estado: "0", municipio: "0"
                        };
                    });
                    break;
                case 1:
                    cliente.idCliente = cliente.id;
                    
                    modificarCliente(cliente).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Cliente se ha actualzado correctamente", "Cliente Actualizado");
                           // $scope.clientes[cliente.index] = cliente;
                        } else {
                            toastr.error("Upsss... no se ha podido modificar este cliente, intentalo mas tarde", "Error =(");
                        }
                        $scope.accion = 0;
                        $scope.headerForm = "Agregar Nuevo Cliente";
                        $scope.cliente = {
                            nombre: "", RFC: "", email: "", direccion: "", noInt: "", noExt: "", colonia: "", codigoPostal: "", estado: "0", municipio: "0"
                        };
                        var estado = buscarEstado(cliente.estado);
                        var muni = buscarMuni(cliente.municipio);
                        var domicilio = cliente.domicilio;
                        var tmp = domicilio.split(" ");
                        var len =tmp.length;
                        domicilio = tmp.myJoin(" ", 0, len - 3);
                        domicilio += " "+muni.nombre + " " + estado.nombre;
                        //cliente.direccion = direccion;
                        $scope.clientes[index].domicilio = domicilio;
                   

                    });

                    break;

            }

        }

    }
    $scope.modificarCliente = function (index) {
        var cliente = $scope.clientes[index];
        cliente.codigoPostal = parseInt(cliente.codigoPostal);
        cliente.index = index;
        $scope.cliente = cliente;
        $scope.cargarMunis();
        $scope.headerForm = "Modificar Cliente Id:"+cliente.id;
        $scope.accion = 1;

    }
    $scope.eliminarCliente = function (index) {
        var cliente = $scope.clientes[index];
        bootbox.confirm({
            title: "Seguro que deseas eliminar este cliente?",
            message: "En verdad deseas eliminar a "+cliente.nombre+" del sistema?",
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
                    eliminarCliente(cliente.id).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Cliente eliminado exitosamente", "Cliente eliminado");
                            $scope.clientes.splice(index, 1);
                        } else {
                            toastr.error("Upsss... no se ha podido eliminar este cliente, intentalo mas tarde", "Error =(");

                        }

                    });
               
                }

            }
        });
    }


}