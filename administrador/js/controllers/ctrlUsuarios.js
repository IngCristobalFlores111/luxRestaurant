function ctrlUsers($scope, $http,usersService) {
   
    
    function secondsToString(seconds)
    {
    var numdays = Math.floor(seconds / 86400);
    var numhours = Math.floor((seconds % 86400) / 3600);
    var numminutes = Math.floor(((seconds % 86400) % 3600) / 60);
    var numseconds = ((seconds % 86400) % 3600) % 60;
    return numdays + " dias " + numhours + " horas " + numminutes + " minutos " + numseconds + " segundos";
    }
$scope.productividadUsr = function(usr){

    var html="<div class='table-responsive'><table class='table table-bordered table-hover'>";
    html+='<thead>';
    html+='<tr><th>Tiempo trabajado</th><th>Total Vendido</th></tr>';
    html+="</thead><tbody><tr>";
    
    usersService.getUsrProductivity(usr.id).then(function(resp){
   var data = resp.data;
   console.log(data);
   var trabajado = (data.trabajado==null)?'Sin tiempo trabajado':secondsToString(data.trabajado);
        html+="<td>"+trabajado+"</td><td>$"+data.total.toLocaleString()+"</td>";
        html+="</tr></tbody></table></div>";
        bootbox.alert({
            title:"Productividad del usuario "+usr.nombre,
            message: html,
            callback: function () {
            }
        });

    });

  


}



$scope.tab = 1;
$scope.setTab = function(tab){
$scope.tab = tab;

}

    $scope.usrs = [];
    $scope.tipos = [];
    $scope.headerModal = "Agegar Usuario";
    $scope.accion = 0;
    $scope.usr = { nombre: "", user: "", pass: "", idTipo :"0"};
    $http.post("PHP/new/users/fetchData.php?accion=usuarios").then(function (resp) {
        $scope.usrs = resp.data;

    });
    tipos_usuarios = [];
    $http.post("PHP/new/users/fetchData.php?accion=roles").then(function (resp) {
        $scope.tipos = resp.data;
        $scope.usr.idTipo = "1";
        tipos_usuarios = resp.data;
        
    });

    $scope.abrirModalUsr = function () {
        $scope.accion = 0;
        $scope.usr = { nombre: "", user: "", pass: "", idTipo: "1" };
        $scope.headerModal = "Agegar Usuario";
        modalUsr.modal("show");

    }

    function validarUsr(usr)
    {
        var errores = [];
        if (usr.nombre == "") {
            errores.push("Nombre no puede estar vacio");
        }
        if (usr.user == "") {
            errores.push("Usuario no puede estar vacio");
        }
        if (usr.pass == "") {
            errores.push("Contrase&ntildea no puede estar vacia");
        }
        return errores;

        

    }

    function buscarRol(id) {
        var roles = $scope.tipos;
        var len = roles.length; var i = 0;
        for (; i < len; i++) {
            var r = roles[i];
            if (r.id == id) {
                return r;
            }

        }
        return null;

    }
    function altaUsuario(usr) {
        return $http.post("PHP/new/users/accionUsr.php?accion=altaUsr", usr);
    }
    function modificarUsr(usr) {
        return $http.post("PHP/new/users/accionUsr.php?accion=editarUsr", usr);
    }

    $scope.submitUsr = function () {
        var usr = $scope.usr;
        var errores = validarUsr(usr);
        if (errores.length > 0) {
            toastr.error(errores.join("<br>"), "Error =(");
        } else {
            switch ($scope.accion) {

                case 0:
                    altaUsuario(usr).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Usuario agregado exitosamente", "Has agregado un usuario");
                            usr.id = resp.data.iduser;
                            var rol = buscarRol(usr.idTipo);
                            usr.rol = rol.nombre;
                            $scope.usrs.push(usr);
                            $scope.usr = { nombre: "", user: "", pass: "", idTipo: "0" };


                        } else {
                            toastr.error("Upsss... no se pudo agregar este usuario, intantalo mas tarde", "Error =(");
                        }

                    });

                    break;
                case 1:
                    modificarUsr(usr).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Usuario actualizado exitosamente", "Has actualizado un usuario");
                        } else {
                            toastr.error("Upsss... no se pudo modificar este usuario, intantalo mas tarde", "Error =(");

                        }

                    });

                    break;

            }


        }


    }


    $scope.modificarUsr = function (u) {
        $scope.accion = 1;
        $scope.usr = u;
        $scope.headerModal = "Modificar usuario ID: "+u.id;
        modalUsr.modal("show");

    }
    function eliminarUsrById(id) {
        var users = $scope.usrs; var len = users.length; var i = 0;
        for (; i < len; i++) {
            if (users[i]) {
                if (users[i].id == id) {
                    $scope.usrs.splice(i, 1);
                    break;
                }
            }
        }
    }
    $scope.eliminarUsr = function (u) {
        bootbox.confirm({
            title: "Seguro que deseas eliminar este usuario?",
            message: "Realmente deseas dar de baaja a " + u.nombre,
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

                    $http.post("PHP/new/users/accionUsr.php?accion=eliminarUsr", { id: u.id }).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Usuario eliminado exitosamente");
                            eliminarUsrById(u.id);
                        } else {
                            toastr.error("Upsss... algo salio mal, contacta a soporte");
                        }
                    });

                }

            }
        });
    }


}