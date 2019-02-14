function ctrlProductosFacturas($scope, $http) {
    $scope.producto = { nombre :"",precio:0,descripcion:"",idUnidad:"1",codigo:""};
    $scope.unidades = [];
    $scope.productos = [];
    var accion = 0;
    $scope.headerText = "Agregar Nuevo Producto";
    $http.get("PHP/new/facturacion/productosFactura/fetchData.php", { params: { accion: "init" } }).then(function (resp) {
        $scope.unidades = resp.data.result0;
        $scope.productos = resp.data.result1;
        $scope.producto.idUnidad = "1";
    });
    function validarProducto(producto) {
        var errores = [];
        if (producto.nombre == "") {
            errores.push("Nombre no puede estar vacio");
        }
        if (producto.descripcion=="") {
            errores.push("Descripcion no puede estar vacia");
        }
        if (producto.precio <= 0) {
            errores.push("Precio debe de ser mayor a 0");
        }
        if (producto.codigo =="") {
            errores.push("Codigo no puede estar vacio");
        }
        return errores;
    }
    function buscarUnidad(id) {
        var unidades = $scope.unidades; var len = unidades.length; var i = 0;
        for (; i < len; i++) {
            var u = unidades[i];
            if (u.id == id) {
                return u;
            }
        }
        return null;
    }

    function agregarProducto(producto) {
        return $http.post("PHP/new/facturacion/productosFactura/accionProductos.php?accion=agregarProducto", producto);
    }

    function modificarProducto(producto) {
        return $http.post("PHP/new/facturacion/productosFactura/accionProductos.php?accion=modificarProducto", producto);

    }

    function eliminarProducto(idProducto) {
        return $http.post("PHP/new/facturacion/productosFactura/accionProductos.php?accion=eliminarProducto", { idProducto: idProducto });

    }

    $scope.submitProducto = function () {
        var producto = $scope.producto;
        var errores = validarProducto(producto);
        if (errores.length > 0) {
            toastr.warning(errores.join("<br>"), "Error en formulario");
        } else {
            switch (accion) {
                case 0:
                    agregarProducto(producto).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Producto agregado exitosamente", "Producto Agregado");
                            producto.id = resp.data.idProducto;
                            var unidad = buscarUnidad(parseInt(producto.idUnidad));
                            producto.unidad = unidad.nombre;
                            $scope.productos.push(producto);

                        } else {
                            toastr.error("Upsss... no se pudo agregar producto, intantalo mas tarde", "Error =(");

                        }
                        $scope.producto = { nombre: "", precio: 0, descripcion: "", idUnidad: "1" };

                    });

                    break;
                case 1:
                    modificarProducto(producto).then(function (resp) {
                        if (resp.data.exito) {
                            toastr.success("Producto ha sido modificado exitosamente", "Producto Actualizado");
                            var unidad = buscarUnidad(parseInt(producto.idUnidad));
                            producto.unidad = unidad.nombre;
                            accion = 0;
                            $scope.headerText = "Agregar Nuevo Producto";
                            $scope.producto = { nombre: "", precio: 0, descripcion: "", idUnidad: "1" };


                        }
                    });


                    break;
            }
           
        }


    };

    $scope.modificarProducto = function (index) {
        var producto = $scope.productos[index];
        producto.index = index;
        producto.idUnidad = producto.idUnidad.toString();
        accion = 1;
        $scope.producto = producto;
        $scope.headerText = "Modificar Producto Id:"+producto.id;

    }

    $scope.eliminarProducto = function (index) {
        var producto = $scope.productos[index];
        bootbox.confirm({
            title: "Estas seguro que deseas eliminar el prodcuto "+producto.nombre,
            message: "Realmente estas seguro de dar de baja este producto?",
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
                    eliminarProducto(producto.id).then(function (resp) {

                        if (resp.data.exito) {
                            toastr.success("Producto eliminado exitosamente", "Has eliminado un producto");
                            $scope.productos.splice(index, 1);
                        } else {
                            toastr.error("Upsss... no se pudo eliminar el producto, intentalo mas tarde", "Error =(");

                        }

                    });
                }
            }
        });
    }

}