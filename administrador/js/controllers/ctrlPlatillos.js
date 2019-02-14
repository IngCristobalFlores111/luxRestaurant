function ctrlPlatillos($scope, $http, fileUpload) {
    // excel export
    $scope.exportarExcel = function(){
        var header = {
            id:'integer',
            imagen:"string",
            nombre:"string",
            descripcion:"string",
            costo:'price',            
            precio:'price',
            activo:'integer',
            categoria:"string",
            idCategoria:"integer"
        };
        var d = new Date();
        var hoy = d.toLocaleDateString();
        hoy=hoy.split("/");
         hoy = hoy.join("_");
        var savePath = "/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_platillos_"+hoy+".xlsx";
        var sheetName = "Exportacion luxrestaurant platillos "+hoy;

        var postData ={
            header:header,
            rows:angular.copy($scope.platillos),
            savePath:savePath,
            sheetName:sheetName
        };
        $http.post("../../excelGen/gen/generateExcell.php",postData).then(function(resp){
            window.open("excellExport/export_platillos_"+hoy+".xlsx");
            
        });
    }


    //
    
    $scope.platillos = [];
    $scope.categorias = [];
    $scope.qPlatillo = "";
    $scope.modalHeader = "Agregar Platillo";
    $scope.platillo = { preparado:true,nombre: "", idCategoria: "0", descripcion: "", img: "", activado: true, nombreArchivo: "", precio:0,costo: 0};
    $scope.accion = 0; // 0 => nuevo , 1=>modificar
    $http.post("PHP/new/platillos/fetchData.php?accion=init").then(function (resp) {
        $scope.platillos = resp.data.result0;
        $scope.categorias = resp.data.result1;
        $scope.platillo.idCategoria = "0";
    });
    $scope.inputImg = function (f) {
        var file = f.files;
        var tipo = file[0].type;
        var size = file[0].size;
        var errores = [];
        var tipoAceptados = ["image/jpeg", "image/png"]
        if (tipoAceptados.indexOf(tipo) == -1) {
            errores.push("Tipo de archivo incorrecto, solo archivos de imagen");
        }
        if (size > 3145728) {  // si es mayor a 3mb
            errores.push("Archivo debe de ser menor a 3mb");
        }
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Error al subir archivo");
            return;

        }
        fileUpload.uploadFileToUrl(file, "PHP/new/uploadImage.php", "../../../images/").then(function (resp) {
            var aResp = resp.data[0];
            if (!aResp.errors) {
                $scope.platillo.img = aResp.name;
                $scope.nombreArchivo = file[0].name;
            } else {
                toastr.error("Error al subir archivo, " + aResp.errors.join(","), "Error");

            }
        });
    }
    $scope.abrirModalPlatillo = function () {
        $scope.modalHeader = "Agregar Nuevo Platillo";
        $scope.accion = 0;
        modalPlatillo.modal("show");
    }
    $scope.triggerInput = function () {
        inputFile.trigger("click");
    }
    function validarPlatillo(platillo) {
        var errores  =[];
        if (platillo.nombre == "") {
            errores.push("Nombre no puede estar vacio");
        }
        if (platillo.descripcion == "") {
            errores.push("Descripcion no puede estar vacia");
        }
        if (platillo.precio <= 0) {
            errores.push("Precio tiene que se mayor que 0 ");
        }
        if (platillo.costo <= 0) {
            errores.push("Costo tiene que se mayor que 0 ");
        }

        if (platillo.img == "") {
            errores.push("Debes de subir una imagen para el platillo");
        }
        return errores;

    }
    function agregarPlatillo(platillo) {
      return  $http.post("PHP/new/platillos/accioPlatillo.php?accion=agregarPlatillo", platillo);
    }
    function modificarPlatillo(platillo) {
        return $http.post("PHP/new/platillos/accioPlatillo.php?accion=modificarPlatillo", platillo);
    }
    function eliminarPlatillo(idPlatillo) {
        return $http.post("PHP/new/platillos/accioPlatillo.php?accion=eliminarPlatillo", { id: idPlatillo });

    }
    function buscarCategoria(id)
    {
        var categorias = $scope.categorias;
        var len = categorias.length; var i = 0;
        for (; i < len; i++) {
            var cat = categorias[i];
            if (cat.id == id) {
                return cat;
            }
        }
        return null;
    }
    function obtenerCosto(idPlatillo){
        return  $http.get("PHP/new/platillos/fetchData.php?accion=costoPlatillo", { params: {"idPlatillo":idPlatillo}});

    }
    function findIndexByID(id) {
        var platillos = $scope.platillos;
        var len = platillos.length; var i = 0;
        for (; i < len; i++) {
            if (platillos[i].id == id) {
                return i;
            }
        }
        return null;

    }
    $scope.submitPlatillo = function () {
        var platillo = $scope.platillo;
        var errores = validarPlatillo(platillo);
        if (errores.length > 0) {
            toastr.error(errores.join("<br>"), "Error =(");
        } else {
            if ($scope.accion == 0) {  // accion agregar nuevo
                agregarPlatillo(platillo).then(function (resp) {
                    if (resp.data.exito) {
                        var categoria = buscarCategoria(platillo.idCategoria);
                        platillo.id = resp.data.idPlatillo;
                        platillo.categoria = categoria.nombre;
                        $scope.platillos.push(platillo);
                        $scope.platillo = { nombre: "", idCategoria: "0", descripcion: "", img: "", activado: true, nombreArchivo: "", precio: 0, costo: 0 };
                        toastr.success("Platillo agregado exitosamente", "Has agregado un platillo");

                    } else {
                        toastr.error("Upsss... Algo salio mal, intentalo mas tarde", "Error =(");
                    }
                });
            }
            if ($scope.accion == 1) {
                modificarPlatillo(platillo).then(function (resp) {
                    if (resp.data.exito) {
                        var cat = buscarCategoria(platillo.idCategoria);
                        platillo.categoria = cat.nombre;
                        toastr.success("Platillo actualizado correctamente", "Platillo actualizado");
                    } else {
                        toastr.error("Upsss... algo salio mal, intantalo mas tarde", "Error =(");
                    }

                });

            }




        }


    }
    $scope.setCostoInsumos = function () {
        obtenerCosto($scope.platillo.id).then(function (resp) {
            var costo = resp.data.costo;
            if (costo == null) {
                toastr.info("Debes de asignar insumos a este platillo primero", "Platillo sin insumos");
            } else {
                $scope.platillo.costo = costo;
            }
        });

    }
    $scope.modificarPlatillo = function (p) {
        var activado = (p.activado == 1) ? true : false;
        var preparado = (p.preparado == 1) ? true : false;
        
        p.activado = activado;
        p.preparado = preparado;
        
        p.idCategoria = p.idCategoria.toString();
        $scope.platillo = p;
        modalPlatillo.modal("show");
        $scope.modalHeader = "Modificar Platillo ID:"+p.id;
        $scope.accion = 1;// accion modificar
    }
    $scope.eliminarPlatillo = function (p) {
        bootbox.confirm({
            title: "Seguro que deseas eliminar este platillo?",
            message: "Estas seguro de eliminar "+'<span class="label label-primary">'+p.nombre+"</span> del sistema?",
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
                    eliminarPlatillo(p.id).then(function (resp) {
                        if (resp.data.exito) {
                            var index = findIndexByID(p.id);
                            $scope.platillos.splice(index, 1);
                            toastr.success("Platillo eliminado exitosamente", "Has eliminado un platillo");
                        } else {
                            toastr.error("Upsss... no se puede eliminar este platillo", "Error =(");
                        }

                    });
                }
            }
        });


     
    }

}