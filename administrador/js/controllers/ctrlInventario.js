function ctrlInventario($scope, $http,excelExportService) {
    $scope.exportarExcel = function(){
        var header = {
          "idInsumo":"integer",
          "costo individual":"price",
          "idAlmacen":"integer",
          "idProveedor":"integer",
          "id":"integer",
          "Nombre":"string",
          "Proveedor":"string",
          "Almacen":"string",
          "cantidad":"price",
          "Costo":"price"

        };
        var d = new Date();
        var str_fecha  = d.toLocaleDateString().split("/").join("_");
        var savePath="/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_inventario_"+str_fecha+".xlsx";

        excelExportService.exportarExcel(header,$scope.insumosAlmacen,savePath).then(function(resp){
          window.open("excellExport/export_inventario_"+str_fecha+".xlsx");
          
        });
    }
$scope.detalle = function(i){
    $http.get("PHP/new/inventario/fetchData.php",{params:{accion:"insumoDetalleHistorial",id:i.idInsumo}}).then(function(resp){
    var html='<div class="table-responsive"><table class="table table-bordered table-hover">';
    html+='<thead><tr>';
    html+="<th>Nombre</th>";
    html+="<th>Almacen</th>";
    html+="<th>Proveedor</th>";
    html+="<th>Cantidad</th>";
    html+="<th>Usuario</th>";
    html+="<th>Razon</th><th>Fecha</th></thead><tbody>";
    var insumos = resp.data; var len = insumos.length; var j = 0;
    
    for(;j<len;j++){
    var ins = insumos[j];
    html+="<tr>";
    html+="<td>"+ins.nombre+"</td>";
    html+="<td>"+ins.almacen+"</td>";
    html+="<td>"+ins.proveedor+"</td>";
    html+="<td>"+ins.cantidad+"</td>";
    html+="<td>"+ins.usuario+"</td>";
    html+="<td>"+ins.razon+"</td>";
    html+="<td>"+ins.fecha+"</td>";
   html+="</tr>";    
}
html+="</tbody></table></div>";
bootbox.alert({
    title: "detalles de insumo "+i.nombre,
    message:html,
    callback: function () {
       
        
    }
})
    

    });
}
$scope.ajuste ={sumar:0,restar:0,insumo:{},cantidad_old:0,razon:""};

    $scope.registrarInventario = function () {
        $http.post("PHP/new/inventario/accionInventario.php?accion=registrarInventario").then(function (resp) {
            if (resp.data.exito) {
                toastr.success("Tu inventario historico ha sido actualizado exitosamente");
            } else {
                toastr.error("Upsss... ha ocurrido un error intentalo mas tarde");
            }
        });
    }

    $scope.busqueda = { insumo: "", historial: { qinsumo: "", fecha: "", insumo: null } };
    $scope.insumos = []; // busqueda de insumos
    $scope.insumos2 = [];
    $scope.entrada = { proveedor: "0", insumo: null, almacen:"0",cantidad:0 };
    $scope.proveedores = []; // proveedores de insumo de entrada
    $scope.almacenes = [];
    $scope.insumosAlmacen = [];
    $http.get("PHP/new/inventario/fetchData.php?accion=almacenesBasic").then(function (resp) {
        $scope.almacenes = resp.data;
        $scope.entrada.almacen = "1";
    });
    $http.get("PHP/new/inventario/fetchData.php?accion=almacenInsumos").then(function (resp) {
        $scope.insumosAlmacen = resp.data;
    });
    $scope.bucarInsumo2 = function () {
        var insumo = $scope.busqueda.historial.qinsumo;
        $http.post("PHP/new/inventario/fetchData.php?accion=buscarInsumos", { q: insumo }).then(function (resp) {
            $scope.insumos2 = resp.data;
            $scope.busqueda.historial.insumo = null;
        });
    }
    $scope.bucarInsumo = function () {
        var insumo = $scope.busqueda.insumo;
        $http.post("PHP/new/inventario/fetchData.php?accion=buscarInsumos", { q: insumo }).then(function (resp) {
            $scope.insumos = resp.data;
            $scope.entrada.insumo = null;
        });
    }
    $scope.seleccionarInsumo2 = function (index) {
        var insumo = $scope.insumos2[index];
        $scope.busqueda.historial.insumo = insumo;
    }
    $scope.seleccionarInsumo = function (index) {
        var insumo = $scope.insumos[index];
        $scope.entrada.insumo = insumo;
        $http.post("PHP/new/inventario/fetchData.php?accion=proveedoresInsumo", { idInsumo: insumo.id }).then(function (resp) {
            $scope.proveedores = resp.data;
        });
    }
    $scope.historial = null;
    function agregarEntradaAlmacen(entrada) {
        var postData = { idAlmacen: entrada.almacen, idInsumo: entrada.insumo.id, idProveedor: entrada.proveedor, cantidad:entrada.cantidad };
        return $http.post("PHP/new/inventario/accionInventario.php?accion=altaInsumoAlmacen", postData);


    }
    function validarEntrada(entrada) {
        var errores = [];
        if (entrada.cantidad <= 0) {
            errores.push("Cantidad debe de ser mayor a 0");
        }
        if (entrada.insumo == null) {
            errores.push("Debes de selccionar un insumo");
        }
        return errores;

    }
    function buscarAlmacen(id) {
        var almacenes = $scope.almacenes; var len = almacenes.length; var i = 0;
        for (; i < len; i++) {
            var a = almacenes[i];
            if (a.id === id) {
                return a;
            }

        }
        return null;
    }
    function buscarProveedor(id) {
        id = parseInt(id);
        var proveedores = $scope.proveedores; var len = proveedores.length; var i = 0;
        for (; i < len; i++) {
            var p = proveedores[i];
            if (p.id == id) {
                return p;
            }

        }
        return null;
    }
    function getInsumoAlmacenIndex(idAlmacen,idInsumo,idProveedor) {
        var insumos = $scope.insumosAlmacen; var len = insumos.length; var i = 0;
        for (; i < len; i++) {
            var insumo= insumos[i];
            if (insumo.idAlmacen == idAlmacen &&insumo.idInsumo== idInsumo && insumo.idProveedor==idProveedor) {
                return i;
            }

        }
        return null;
    }
    function actuliazarCantidad(insumo,razon) {

        insumo.razon = razon;
        console.log(insumo);
        $http.post("PHP/new/inventario/accionInventario.php?accion=actulizarCantidad", insumo).then(function(resp){
            if (resp.data.exito) {
                toastr.success("Cantidad de " + insumo.nombre + " actualizada", "Insumo actualizado");
                modalAjuste.modal("hide");
                
                insumo.costo = parseFloat(insumo.cantidad) * parseFloat(insumo.costo_individual);
            } else {
                toastr.error("Upsss... no se pudo actualizar este insumo en almacen por el momento", "Error =(");
            }
    
    
        });     
    }
    $scope.submitEntrada = function () {
        var entrada = $scope.entrada;
        var errores = validarEntrada(entrada);
        if (errores.length > 0) {
            toastr.error(errores.join("<br>"), "Error =(");
        } else {
            agregarEntradaAlmacen(entrada).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Se ha agregado este insumo al alamcen exitosamente", "Alta de almacen");
                    var prov = buscarProveedor(entrada.proveedor); 
                    prov = prov.nombre;
                    var idAlmacen = entrada.almacen;
                    var almacen = buscarAlmacen(entrada.almacen);
                    almacen = almacen.nombre;
                    if (resp.data.ids.actual) {
                        $scope.insumosAlmacen.push({
                            idInsumo: resp.data.ids.actual,
                            id: resp.data.ids.actual,
                            nombre: entrada.insumo.nombre,
                            proveedor: prov,
                            almacen: almacen,
                            idAlmacen: idAlmacen,
                            cantidad: entrada.cantidad,
                            costo: parseFloat(entrada.cantidad) * parseFloat(entrada.insumo.costo),
                            costo_individual: entrada.insumo.costo
                        });
                    } else {
                        var index = getInsumoAlmacenIndex(entrada.almacen, entrada.insumo.id, entrada.proveedor);
                        var nueva_cantidad = parseFloat($scope.insumosAlmacen[index].cantidad) + parseFloat(entrada.cantidad);
                        $scope.insumosAlmacen[index].cantidad = nueva_cantidad;
                        var nuevo_costo = nueva_cantidad * parseFloat(entrada.insumo.costo);
                        $scope.insumosAlmacen[index].costo = nuevo_costo;

                    }

                } else {
                    toastr.error("Upsss... no se ha podido agregar a almacen intentalo mas tarde","Error =(");
                }
                $scope.entrada = { proveedor: "1", insumo: null, almacen: "1", cantidad: 0 };
                $scope.insumos = [];
                $scope.busqueda.insumo = "";
            });

        }
    }
    var mostarModalAjuste = true;

$scope.submitMod = function(){
var ajuste = $scope.ajuste;
    actuliazarCantidad(ajuste.insumo,ajuste.razon);
}

    $scope.restarInsumo = function(){
    var resta = $scope.ajuste.restar;
    if(resta>0){
        $scope.ajuste.insumo.cantidad -=resta;
    }

    }
    $scope.sumarInsumo = function(){
    var suma = $scope.ajuste.sumar;
    if(suma>0){
    $scope.ajuste.insumo.cantidad+=suma;

    }
    }
    $scope.modificar = function (insumo) {
      //  var insumo = $scope.insumosAlmacen[index];
      if(mostarModalAjuste){
     $scope.ajuste.insumo = insumo;
     $scope.ajuste.cantidad_old = insumo.cantidad;
     $scope.ajuste.insumo.cantidad = parseFloat($scope.ajuste.insumo.cantidad);

      modalAjuste.modal("show");
      }else{

        bootbox.prompt({
            title: "Ingresa una nueva cantidad para "+insumo.nombre+" en el almacen "+insumo.almacen,
            inputType: 'number',
            value:insumo.cantidad,
            callback: function (result) {
                if (result != null) {
                    if (result >= 0) {
                        insumo.cantidad = result;
                       
                        actuliazarCantidad(insumo);
                    } else {
                        toastr.warning("Cantidad invalida solo numeros positivos", "Cantidad invalida");
                    }

                }
            }
        });
    }
    }
    $scope.buscarHistorial = function () {
        var historial = $scope.busqueda.historial;
        var success = true;
        if (historial.insumo == null) {
            toastr.warning("Debes de selccionar un insumo", "Selecciona un insumo");
            success = false;
        }
        if (historial.fechaInicio == "" || historial.fechaInicio==null||historial.fechaFin == "" || historial.fechaFin==null) {
            toastr.warning("Debes de seleccionar una fecha", "Selecciona una fecha");
            success = false;
        }
        if (success) {
            var fechaInicio = historial.fechaInicio.toISOString().slice(0, 10).replace(/-/g, "-");
            var fechaFin = historial.fechaFin.toISOString().slice(0, 10).replace(/-/g, "-");
            
            $http.post("PHP/new/inventario/fetchData.php?accion=historialInsumo", { fechaInicio: fechaInicio,fechaFin:fechaFin ,idInsumo: historial.insumo.id }).then(function (resp) {
                $scope.historial = resp.data;

            });

        }
    }


}