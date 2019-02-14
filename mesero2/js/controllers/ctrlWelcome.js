function ctrlWelcome($scope, $http) {
    $scope.ticket = { tipo: "", asunto: "", mensaje: "" };
  
    $scope.soporte = function () {
        $("#modalSoporte").modal("show");
    }
    $scope.enviar = function () {
        var tipo = $scope.ticket.tipo;
        var asunto = $scope.ticket.asunto;
        var mensaje = $scope.ticket.mensaje;
        if (tipo == "" || asunto == "" || mensaje == "") {
            toastr.info("Debes de llenar todos los campos para darte un mejor soporte");
        } else {
            toastr.info("Enviando mensaje...");
            $http.post("php/soporte.php", { tipo: tipo, asunto: asunto, mensaje: mensaje }).then(function (res) {
                toastr.clear();

                if (res.data.success) {
                    toastr.success("Mensaje enviado, te responderemos en breve");
                    $scope.ticket = { tipo: "", asunto: "", mensaje: "" };

                } else {
                    toastr.error("Upsss... algo a salido mal, intentalo mas tarde");
                    console.log(res.data.error);
                }

            });
        }
    }
}