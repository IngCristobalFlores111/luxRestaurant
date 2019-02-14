function ctrlContactos($scope,$rootScope, $http) {
    $scope.contactos = [];
    $scope.contacto = { nombre: "", email: "" };
    $scope.accion = 0; // 0->agregar, 1->modificar
    var anteriorSelected = {};
    $scope.selectContacto = function(index)
    {
        anteriorSelected.selected = false;
        var contacto = $scope.contactos[index];
        $scope.contacto = contacto;
        $scope.accion = 1;
        anteriorSelected = contacto;
        contacto.selected = true;



    }
    var idCliente = 0;
    $scope.$on("cargarContactos", function (event, id) {
        $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "contactos", idCliente: id } }).then(function (resp) {
            $scope.contactos = resp.data;
            idCliente = id;

        });

    });


    function eliminarContacto(idContacto) {
        return $http.post("PHP/new/facturacion/accionFacturacion.php?accion=eliminarContactoCliente", { idContacto:idContacto });

    }
    function modificarContacto(contacto) {
        return $http.post("PHP/new/facturacion/accionFacturacion.php?accion=modificarContactoCliente", contacto);

    }
    function agregarContacto(contacto) {
        return $http.post("PHP/new/facturacion/accionFacturacion.php?accion=agregarContactoCliente", contacto);


    }
    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function validarContacto(contacto) {
        var errores = [];
        var nombre = contacto.nombre;
        var email = contacto.email;
        if (nombre == "" || nombre == undefined) {
            errores.push("Nombre no puede estar vacio");
        }
        if (email == "" || email == undefined) {
            errores.push("Correo no puede estar vacio");
        }

        if (!validateEmail(email)) {
            errores.push("Correo invalido");
        }

        return errores;

    }
    $scope.submitContacto = function () {
        var contacto = $scope.contacto;
        var errores = validarContacto(contacto);
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Datos del contacto invalido");
            return;
        }

        switch ($scope.accion) {
            case 0:  // agregar
                contacto.idCliente = idCliente;
                agregarContacto(contacto).then(function (resp) {
                    if (resp.data.exito) {
                        toastr.success("Contacto agregado exitosamente", "Contacto Agregado");
                        contacto.idContacto = resp.data.idContacto;
                        $scope.contactos.push(contacto);

                    } else {
                        toastr.error("Upsss... no se pudo agregar contacto,intentalo mas tarde", "Error =(");

                    }
                    $scope.contacto = { nombre: "", email: "" };


                });

                break;
            case 1:
                modificarContacto(contacto).then(function (resp) {
                    if (resp.data.exito) {
                       toastr.success("Contacto actualizado exitosamente", "Contacto Actualizado");
                       $scope.accion = 0;

                    } else {
                        toastr.error("Upsss... no se pudo actualizar el contacto,intentalo mas tarde", "Error =(");

                    }
                    $scope.contacto = { nombre: "", email: "" };

                });

                break;

        }

    }



    $scope.eliminarContacto = function () {
        var contacto = $scope.contacto;
        bootbox.confirm({
            title: "Seguro que deseas eliminar este contacto?",
            message: "Realemente deseas eliminar "+contacto.nombre+" de los contactos de este cliente?",
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
                    eliminarContacto(contacto.idContacto).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Contacto eliminado exitosamente", "Contacto Eliminado");
                            var index = $scope.contactos.indexOf(contacto);
                            $scope.contactos.splice(index,1);

                        } else {
                            toastr.error("Upsss... no se pudo eliminar contacto,intentalo mas tarde", "Error =(");

                        }

                    });
                }

            }
        });
    }



}