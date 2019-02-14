function ctrlEnvioCorreos($scope, $http) {
    $scope.correo = { asunto: "", mensaje: "", pdf: true, xml: true };
    $scope.contactos = [];
    var folio = '';
    $scope.$on("cargarContactos", function (event, data) {
        folio = data.folio;
        $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "contactos", idCliente: data.idCliente } }).then(function (resp) {
            var contactos = resp.data; var len = contactos.length; var i = 0;
            for (; i < len; i++) { contactos[i].selected = true;}
            $scope.contactos = contactos;
        
        });
    });

    function validarEnvio(correo,contactos) {
        var errores = [];
        if (correo.asunto == "" || correo.mensaje == "") {
            errores.push("Asunto o mensaje no pueden estar vacios");
        }
        var len = contactos.length; var i = 0;
        var contValido = 0;
        for (; i < len; i++) {
            if (contactos[i].selected) {
                contValido++;
            }
        }
        if (contValido == 0) {
            errores.push("Debes de seleccionar al menos un contacto");
        }
        return errores;
    }

    function enviarCorreos(correo, contactos) {
        return $http.post("phpMailer/enviarCorreos.php", { contactos: contactos, asunto: correo.asunto, mensaje: correo.mensaje, folio: folio,pdf:correo.pdf,xml:correo.xml });
    }
    $scope.enviarCorreos = function () {
        var correo = $scope.correo;
        var contactos = $scope.contactos;
        var errores = validarEnvio(correo,contactos);
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Formulario invalido");
        } else {
            toastr.info("Enviando Correos...");
            enviarCorreos(correo, contactos).then(function (resp) {
                if (resp.data == 1) {
                    toastr.success("Correos enviados existosamente", "Correos enviados");
                } else {
                    toastr.error("Upsss... no se pudieron enviar los correos, intentalo mas tarde", "Error =(");
                }
                $scope.correo = { asunto: "", mensaje: "", pdf: true, xml: true };

            });

        }
    }
}