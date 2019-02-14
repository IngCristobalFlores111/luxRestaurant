function ctrlConfigFacturas($scope, $http, fileUploadOptions) {
    $scope.config = { archivo_key: "", archivo_cer: "", password: "", pac_usuario: "", pac_password: "", moneda: "", tipoCambio:"" };
    $scope.configs = [];
    $scope.nueva = false;
    $scope.nuevoLugar = false;
    $scope.estados = []; 
    $scope.municipios = [];
    $scope.emisor = {};
    var anterior = 0;

    $scope.lugar = {};
    $scope.lugares = [];
    $scope.municipios2 = [];// municipios de lugar de expedicion 
    var lugarAnterior = {};

    function getLugarIndex(id) {
        var lugares = $scope.lugares; var len = lugares.length; var i = 0;
        for (; i < len; i++) {
            if (lugares[i].idLugarExpedicion == id) {
                return i;
            }
        }
    }

    $scope.eliminarLugar = function () {
        bootbox.confirm({
            title: "Seguro que deseas eliminar este lugar de expedicion?",
            message: "Realmente deseas eliminar este lugar de expedicion en "+$scope.lugar.direccion,
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
                    $http.post("PHP/new/facturacion/accionFacturacion.php?accion=eliminarLugar", { id: $scope.lugar.idLugarExpedicion }).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Lugar de expedicion eliminado exitosamente", "Lugar de expedicion actulizado");
                            var index = getLugarIndex($scope.lugar.idLugarExpedicion);
                            $scope.lugares.splice(index, 1);
                        } else {
                            toastr.error("Upsss... hubo un error al eliminar el lugar de expedicion, intentalo mas tarde", "Error =(");
                        }
                        $scope.nuevoLugar = true;
                        lugarAnterior.selected = false;
                        $scope.lugar = {};

                    });
                }

            }
        });
    }

    $scope.selectLugar = function (index) {

        $scope.lugar = $scope.lugares[index];
        $scope.lugares[index].selected = true;
        lugarAnterior.selected = false;
        $scope.nuevoLugar = false;
        lugarAnterior = $scope.lugar;
        $scope.cargarMunis2();

    }
    $scope.nuevoLugarExp = function () {
        $scope.nuevoLugar = true;
        lugarAnterior.selected = false;
        $scope.lugar = {};

    }
    $scope.cargarMunis2=function(){
        $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "municipios", idEstado: $scope.lugar.estado } }).then(function (resp) {
            $scope.municipios2 = resp.data;

        });
    }
    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "lugaresExpedicion" } }).then(function (resp) {
        $scope.lugares = resp.data;
        var init = resp.data[0];
        init.municipio = init.municipio.toString();
        $scope.lugares[0].selected = true;
        $scope.lugar = init;
        $scope.cargarMunis2();
        lugarAnterior = $scope.lugar;

    });


    function validarLugar(lugar) {
        var errores = [];
        for (var i in lugar) {
            var e = lugar[i];
            if (e == undefined || e == "") {
                errores.push(i + " Valor invalido");
            }
        }
        return errores;

    }
    $scope.submitLugar = function () {
        var lugar = $scope.lugar;
        var errores = validarLugar(lugar);
        if (errores.length > 0) {
            toastr.inf(errores.join("<br>"), "Errores en formulario");

        } else {
            if ($scope.nuevoLugar) {
                $http.post("PHP/new/facturacion/accionFacturacion.php?accion=altaLugar", { lugar: lugar }).then(function (resp) {

                    if (resp.data.exito) {
                        toastr.success("Lugar de expedicion dado de alta exitosamente", "Nuevo lugar de expedicion");
                        var lugar = $scope.lugar;
                        lugar.idLugarExpedicion = resp.data.idLugar;
                        lugar.selected = true;
                        $scope.lugares.push(lugar);
                        $scope.nuevoLugar = false;

                    } else {
                        toastr.error("Upsss... no se ha podido agregar lugar de expedicion, intentalo mas tarde", "Error =(");
                    }

                });
            }
            else {
                $http.post("PHP/new/facturacion/accionFacturacion.php?accion=actualizarLugar", { lugar: lugar }).then(function (resp) {

                    if (resp.data.exito) {
                        toastr.success("Lugar de expedicion actualizado exitosamente", "Lugar de expedicion actulizado");
                        
                    } else {
                        toastr.error("Upsss... hubo un error al actualzar el lugar de expedicion, intentalo mas tarde", "Error =(");
                    }
                });
            }

        }
    }


    function validateEmail(email) {
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    function validarEmisor(emisor) {
        var errores = [];
        for (var i in emisor) {
            var e = emisor[i];
            if (e==""|| e==undefined) {
                errores.push(e + " No puede estar vacio");
            }
        }
        if (!validateEmail(emisor.email)) {
            errores.push("Correo invalido");
        }
        return errores;
    }
    $scope.actualizarEmisor = function () {
        var emisor = $scope.emisor;
        var errores = validarEmisor(emisor);
        if (errores.length > 0) {
            toastr.info(errores.join("<br>"), "Valores invalidos");
        } else {
            $http.post("PHP/new/facturacion/accionFacturacion.php?accion=actualizarEmisor", { emisor: emisor }).then(function (resp) {

                if (resp.data.exito) {
                    toastr.success("Datos del emisor actualzados", "Emisor Actualizado");
                } else {
                    toastr.error("Upsss... no se pudieron actualizar los datos, intentalo mas tarde", "Error =(");

                }
            });
        }

    }

    $scope.cargarMunis = function () {
        $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "municipios", idEstado:$scope.emisor.estado } }).then(function (resp) {
            $scope.municipios = resp.data;
           
        });
    }
    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "emisor" } }).then(function (resp) {
        $scope.emisor = resp.data.emisor;
        $scope.estados = resp.data.estados;
        $scope.emisor.estado = resp.data.emisor.estado.toString();
        $scope.emisor.municipio = resp.data.emisor.municipio.toString();

        $scope.cargarMunis();
    });

    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "configs" } }).then(function (resp) {
        var inicial = resp.data[0];
        inicial.selected = true;
        resp.data[0].selected = true;
        $scope.configs = resp.data;
        $scope.config =inicial;
        anterior = 0;

    });
    $scope.selectConfig = function (index) {
        $scope.configs[anterior].selected = false;       
        var config = $scope.configs[index];
        $scope.config = config;
        config.selected = true;
        anterior = index;
        $scope.nueva = false;

    }
    $scope.inputKey = function (f) {
        var file = f.files;
        var size = file[0].size;
        var errores = [];
        var file_name = file[0].name;
        var pattern = /\.([0-9a-z]+)(?:[\?#]|$)/i;
        extension = file_name.match(pattern);
        if (extension[0] != ".key") {
            errores.push("Tipo de archivo incorrecto,solo .key son permitidos");
        }
        if (size > 3145728) {  // si es mayor a 3mb
            errores.push("Archivo debe de ser menor a 3mb");
        }
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Error al subir archivo");
            return;

        }
        fileUploadOptions.uploadFileToUrl(file, "PHP/new/uploadFile.php", "../../Facturacion/pruebas/", false, [file_name]).then(function (resp) {
            var aResp = resp.data[0];
            if (!aResp.errors) {
                $scope.config.archivo_key = file_name;
            } else {
                toastr.error("Error al subir archivo, " + aResp.errors.join(","), "Error");

            }
        });
    }
    $scope.inputCer = function (f) {
        var file = f.files;
        var size = file[0].size;
        var errores = [];
        var file_name = file[0].name;
        var pattern = /\.([0-9a-z]+)(?:[\?#]|$)/i;
        extension = file_name.match(pattern);
        if (extension[0] != ".cer") {
            errores.push("Tipo de archivo incorrecto,solo .cer son permitidos");
        }
        if (size > 3145728) {  // si es mayor a 3mb
            errores.push("Archivo debe de ser menor a 3mb");
        }
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Error al subir archivo");
            return;

        }
        fileUploadOptions.uploadFileToUrl(file, "PHP/new/uploadFile.php", "../../Facturacion/pruebas/", false, [file_name]).then(function (resp) {
            var aResp = resp.data[0];
            if (!aResp.errors) {
                $scope.config.archivo_cer = file_name;
            } else {
                toastr.error("Error al subir archivo, " + aResp.errors.join(","), "Error");

            }
        });
    }
    $scope.saveConfig = function () {

        if ($scope.nueva) {
            var nueva = $scope.config;
            $http.post("PHP/new/facturacion/accionFacturacion.php?accion=nuevaConfig", { config: nueva }).then(function (resp) {
                if (resp.data.exito) {
                    nueva.idConfig = resp.data.idConfig;
                    nueva.selected = true;
                    $scope.configs.push(nueva);
                    toastr.success("Nueva configuracion agregada exitosamente", "Config Agregada");
                } else {
                    toastr.error("Upsss... no se pudo subir nueva configuracion, intantalo mas tarde", "Error =(");
                }
                $scope.nueva = false;

            });



        } else { // editando config
            var config = $scope.configs[anterior];
            var idConfig = config.idConfig;
            var config_new = $scope.config;
            postData = { config: config_new, idConfig: idConfig };
            $http.post("PHP/new/facturacion/accionFacturacion.php?accion=modificarConfig", postData).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Configuracion actualizada correctamente", "Config. actualizada");
                    config = $scope.config;
                } else {
                    toastr.error("Upsss... no se ha podido actualziar, intentalo mas tarde", "Error =(");

                }

            });
        }
    }

    $scope.triggerInputKey = function () {
        inputKey.trigger("click");
    }
    $scope.tiggerInputCer = function () {
        inputCer.trigger("click");

    }

    $scope.nuevaConfig = function () {
        $scope.config = { archivo_key: "", archivo_cer: "", password: "", pac_usuario: "", pac_password: "", moneda: "", tipoCambio: "" };
        $scope.configs[anterior].selected = false;
        $scope.nueva = true;

    }
    $scope.eliminarConfig = function () {
        var idConfig = $scope.configs[anterior].idConfig;
        bootbox.confirm({
            title: "Estas seguro de eliminar la configuracio numero "+idConfig+"?",
            message: "Realmente deseas eliminar esta configuracion?",
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
                    $http.post("PHP/new/facturacion/accionFacturacion.php?accion=eliminarConfig", { idConfig: idConfig }).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Configuracion eliminada exitosamente", "Se ha eliminado un configuracion");
                            $scope.configs.splice(anterior, 1);
                            $scope.config = { archivo_key: "", archivo_cer: "", password: "", pac_usuario: "", pac_password: "", moneda: "", tipoCambio: "" };
                            $scope.nueva = true;
                        } else {
                            toastr.error("Upsss... no se ha podido eliminar la configuracion por el momento, intentalo mas tarde", "Error =(");

                        }
                    });

                }
            
            }
        });

    }
}