function ctrlModalSettings($scope,$http) {
    $scope.usr = usr;
    $scope.updateUsr = function () {
        var usr = $scope.usr;
        if (usr.nombre == "" || usr.usr == "" || usr.pass == "") {
            toastr.info("Informacion invalida,debes de llenar todos los campos");
        } else {
            $http.post("php/accionMesero.php?accion=updateUsr", { pass: usr.pass, nombre: usr.nombre, user: usr.usr }).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Informacion actualizada correctamente", "Usuario actualizado");
                } else {
                    toastr.error("Upsss... ha ocurrrido un error, intentalo mas tarde");
                }

            });

        }
    }
}