function ctrlInsumos($scope, $http,excelExportService) {
// exportar a excel
$scope.exporarExcel = function(){
var header = {
    "idUnidad":"integer",
    "id":"integer",
    "Nombre":"string",
    "Descripcion":"string",
    "costo":"price",
    "unidad":"string"
};

var d = new Date();
    var str_fecha  = d.toLocaleDateString().split("/").join("_");
    var savePath="/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_insumos_"+str_fecha+".xlsx";
    excelExportService.exportarExcel(header,$scope.insumos,savePath).then(function(resp){
        window.open("excellExport/export_insumos_"+str_fecha+".xlsx");
        
    });

}

//

$scope.tab= 1;
$scope.setTab = function(tab){
$scope.tab=tab;

}

    $scope.nuevo = { nombre: "", unidad: "0", descripcion: "", costo: 0 };
    $scope.unidades = [];
    $scope.insumos = [];
    $scope.headerText = "Agregar Insumo";
    $scope.accion = "Agregar";
    $scope.disabled = false;
    $scope.selectProveedores = { proveedores:[] };  // proveedores de select-ui
    $scope.proveedores = [];
    $http.get("PHP/new/obtenerInfo.php", { params: { accion: "unidades" } }).then(function (resp) {

        $scope.unidades = resp.data;
        $scope.nuevo.idUnidad = "1";

    });
    $http.get("PHP/new/obtenerInfo.php", { params: { accion: "insumos" } }).then(function (resp) {
        $scope.insumos = resp.data;

    });
    $http.get("PHP/new/obtenerInfo.php", { params: { accion: "proveedores" } }).then(function (resp) {
        $scope.proveedores = resp.data;
    });

    function obtenerProveedoresInsumo(idInsumo) {
        return $http.get("PHP/new/obtenerInfo.php", { params: { accion: "proveedoresInsumo", idInsumo:idInsumo } });
    }
    $scope.modificarInsumo = function (insumo) {
        //var insumo = $scope.insumos[index];
        insumo.idUnidad = insumo.idUnidad.toString();
        $scope.accion = "Modificar";
        $scope.headerText = "Modificar Insumo ID:"+insumo.id;
        $scope.nuevo = { id:insumo.id,nombre: insumo.nombre, idUnidad: insumo.idUnidad, descripcion: insumo.descripcion, costo:insumo.costo };
        if (!insumo.proveedores) {
            obtenerProveedoresInsumo(insumo.id).then(function (resp) {
                $scope.selectProveedores.proveedores = resp.data;

            });
        } else {
            $scope.selectProveedores.proveedores = insumo.proveedores;
        }
        $("html, body").animate({ scrollTop: 0 }, "slow");

    }
   
    $scope.eliminarInsumo = function (insumo) {
     //   var insumo = $scope.insumos[index];
        bootbox.confirm({
            title: "Seguro que deseas eliminar este insumo?",
            message: "Realmente deseas eliminar el insumo "+insumo.nombre+" del sistema?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirmar'
                }
            },
            callback: function (result) {
                if (result) {
                  //  eliminarInsumoById(insumo.id);
                    eliminarInsumo(insumo.id);

                }
            }
        });


    };

    function validarInsumo() {
        var insumo = $scope.nuevo;
        var costo = parseFloat(insumo.costo);
        var success = true;
        if (costo <= 0) {
            toastr.warning("Costo tiene que ser mayor a 0", "Valor invalido");
            success = false;
        }
        if (insumo.nombre == "") {
            toastr.warning("Nombre no puede estar vacio", "Valor invalido");
            success = false;

        }
        if (insumo.descripcion == "") {
            toastr.warning("Descripci&oacuten no puede estar vacia", "Valor invalido");
            success = false;

        }
        if ($scope.selectProveedores.proveedores.length == 0) {
            toastr.warning("Debes de seleccionar uno o varios proveedores para este insumo");
            success = false;
        }

        return success;


    }
    function agregarInsumo(insumo) {
        insumo.unidad = insumo.idUnidad;
        insumo.proveedores = $scope.selectProveedores.proveedores;
        $.post("PHP/new/accionInsumo.php?accion=agregarInsumo", insumo, function (data) {
            var resp = JSON.parse(data);
            if (resp.exito) {
                insumo.id = resp.idInsumo;
                var unidad = buscarUnidad(insumo.unidad);
                insumo.unidad = unidad.nombre;
                $scope.insumos.push(insumo);
                $scope.nuevo = { nombre: "", unidad: "0", descripcion: "", costo: 0 };
                toastr.success("Insumo agregado correctamente", "Insumo agregado");
                $scope.selectProveedores.proveedores = [];
                $scope.$apply();
            } else {
                toastr.error("Upsss... algo salio mal", "Error =(");

            }

        });

    }
    function buscarUnidad(id) {
        var unidades = $scope.unidades; var len = unidades.length; var i = 0;
        for (; i < len; i++) {
            var u = unidades[i];
            if (u.idUnidad == id) {
                return u;
            }

        }
        return null;
    }
    function modificarInsumoById(insumo, id) {

        var insumos = $scope.insumos; var len = insumos.length; var i = 0;
        for (; i < len; i++) {
            if (insumos[i]) {
                if (insumos[i].id == id) {
                    insumos[i] = insumo;
                }
            }        }
    }
    function actualizarInsumo(insumo) {
        insumo.unidad = insumo.idUnidad;
        insumo.proveedores = $scope.selectProveedores.proveedores;
        $.post("PHP/new/accionInsumo.php?accion=modificarInsumo", insumo, function (data) {
            var resp = JSON.parse(data);
            if (resp.exito) {
                var unidad = buscarUnidad(insumo.unidad);
                insumo.unidad = unidad.nombre;
                // $scope.insumos.push(insumo);
                modificarInsumoById(insumo, insumo.id);
                $scope.nuevo = { nombre: "", unidad: "0", descripcion: "", costo: 0 };
                toastr.success("Insumo modificaro correctamente", "Insumo Actualizado");
                $scope.headerText = "Agregar Insumo";
                $scope.accion = "Agregar";
                $scope.selectProveedores = { proveedores: [] };
                $scope.$apply();
            } else {
                toastr.error("Upsss... algo salio mal", "Error =(");

            }

        });
    }
    function eliminarInsumoById(id) {
        var insumos = $scope.insumos;
     //   console.log(insumos);
        var len = insumos.length;
        var i = 0;
        for (; i < len; i++) {
            if (insumos[i]) {
                if (insumos[i].id == id) {
                    $scope.insumos.splice(i, 1);
                }
            }
        }
    }
    function eliminarInsumo(idInsumo) {
        $.post("PHP/new/accionInsumo.php?accion=eliminarInsumo", { id: idInsumo }, function (data) {
            var resp = JSON.parse(data);
            if (resp.exito) {
                toastr.success("Se ha eliminado el insumo correctamente", "Insumo eliminado");
              //  $scope.insumos.splice(index, 1);
                eliminarInsumoById(idInsumo);
                $scope.$apply();
            } else {
                toastr.info("No se puede eliminar este insumo por que hay platillos que lo tienen", "Insumo no se puede eliminar");
            }

        });

    }

    $scope.submitInsumo = function () {
        console.log($scope.selectProveedores.proveedores);
        if (validarInsumo()) {
            switch ($scope.accion) {
                case "Agregar":
                    agregarInsumo($scope.nuevo);
                    $scope.headerText = "Agregar Insumo";


                    break;
                case "Modificar":
                    actualizarInsumo($scope.nuevo);


                    break;
            }


        }
    }
    $scope.undoInsumo = function () {

        $scope.nuevo = { nombre: "", unidad: "0", descripcion: "", costo: 0 };

    }

}