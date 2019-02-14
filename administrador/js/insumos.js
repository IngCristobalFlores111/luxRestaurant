(function () {
    var domUnidades = null;
    var domTableInsumos = null;
    var domBtnAgregar = null;
    var domformInsumo = null;
    var dataTable = null;
    var domheaderForm = null;
    var domInputsInsumo = null;
    var g_insumos = [];
    var g_unidades = [];
    function renderUnidades() {
        $.get("php/new/obtenerInfo.php?accion=unidades", function (data) {
            var unidades = JSON.parse(data);
            g_unidades = unidades;
            var html = ''; var len = unidades.length; var i = 0;
            for (; i < len; i++) {
                var u = unidades[i];
                html += "<option value='" + u.idUnidad + "'>" + u.nombre + "</option>";
            }
            domUnidades.html(html);

        });
    }

    function renderInsumos() {
        $.get("php/new/obtenerInfo.php?accion=insumos", function (data) {
            var insumos = JSON.parse(data);
            g_insumos = insumos;
            var html = ''; var len = insumos.length; var i = 0;
            for (; i < len; i++) {
                var insumo = insumos[i];
                html += "<tr><td>"+insumo.nombre+"</td>";
                html += "<td>" + insumo.descripcion + "</td>";
                html += "<td>" + insumo.unidad + "</td>";
                html += "<td>$" + insumo.costo.toLocaleString() + "</td>";
                html += '<td><div  class="btn-group"><button data-accion="modificar" data-unidad="'+insumo.idUnidad +'" data-nombre="' + insumo.nombre + '" data-id="' + insumo.id + '"  class="btn btn-primary modificar-insumo"><i class="fa fa-cog" aria-hidden="true"></i></button> <button data-accion="eliminar" data-nombre="' + insumo.nombre + '" data-id="' + insumo.id + '" class="btn btn-danger eliminar-insumo"><i class="fa fa-times" aria-hidden="true"></i></button></div></td>';
            }
            domTableInsumos.html(html);
            initDT();


        });
    }

    function initDT() {
        dataTable.dataTable({
            "sPaginationType": "bootstrap_full",
            "bScrollCollapse": true,
            "autoWidth": true,
            "responsive": true

        });
        $('.dataTables_filter input').each(function () {
            $(this).attr("placeholder", "Buscar...");
        })
    }
    function array2Object(a) {
        var data = {};
        a.forEach(function (obj) {
            data[obj.name] = obj.value;
        });
        return data;
    }
    function agregarInsumoDB(insumo) {
        return $.ajax({
            url: "PHP/new/accionInsumo.php?accion=agregarInsumo",
            type: "POST",
            data: insumo
        });
    }
    function modificarInsumoBD(insumo) {
        return $.ajax({
            url: "PHP/new/accionInsumo.php?accion=modificarInsumo",
            type:"POST",
            data: insumo
        });
    }
    function accionAgregar() {
        var insumo = array2Object(domformInsumo.serializeArray());
        var exito = true;
        if (insumo.costo == "" || insumo.descripcion == "" || insumo.nombre == "") {
            toastr.warning("Introduce todos los datos del insumo", "LLena el formulario");
            exito = false;
        }
        if (insumo.costo < 0) {
            toastr.warning("Costo invalido", "Selecciona un costo valido");
            exito = false;
        }

        if(exito) {
            agregarInsumoDB(insumo).done(function (data) {
                var resp = JSON.parse(data);
                if (resp.exito) {
                    toastr.success("Insumo agregado exitosamente", "Has agregado un insumo");
                    var unidad = domUnidades.find("option:selected").html();
                    var htmlBotones = '<td><div  class="btn-group"><button data-accion="modificar" data-unidad="' + insumo.unidad + '" data-nombre="' + insumo.nombre + '" data-id="' + resp.idInsumo + '"  class="btn btn-primary modificar-insumo"><i class="fa fa-cog" aria-hidden="true"></i></button> <button data-accion="eliminar" data-nombre="' + insumo.nombre + '" data-id="' + resp.idInsumo + '" class="btn btn-danger eliminar-insumo"><i class="fa fa-times" aria-hidden="true"></i></button></div></td>';
                    insumo.id = resp.idInsumo;
                    g_insumos.push(insumo);
                    insumo.costo = parseFloat(insumo.costo);
                    dataTable.dataTable().fnAddData([insumo.nombre, insumo.descripcion, unidad, "$" + insumo.costo.toLocaleString(), htmlBotones]);
                    domformInsumo.trigger("reset");
                } else {

                    toastr.error("Upsss... no se ha podido agregar este insumo, intentalo mas tarde", "Error =(");
                }

            });
        }
    }

    function obtenerUnidad(id){
        var len = g_unidades.length; var i = 0;
        for(;i<len;i++){
            var u = g_unidades[i];
            if(u.idUnidad == id){
                return u;
            }
        }
        return null;
    }
    function actualizarInsumoArray(idInsumo, nuevo) {
        var len = g_insumos.length; var i = 0;
        for (; i < len; i++) {
            if (g_insumos[i].id == idInsumo) {
                g_insumos[i] = nuevo;
            }

        }

    }

    function accionModificar(idInsumo) {
        var insumo = array2Object(domformInsumo.serializeArray());
        if (insumo.costo == "" || insumo.descripcion == "" || insumo.nombre == "") {
            toastr.warning("Introduce todos los datos del insumo", "LLena el formulario");
        } else {
            insumo.id = idInsumo;
            modificarInsumoBD(insumo).done(function (data) {
                var resp = JSON.parse(data);
                if (resp.exito) {
                    toastr.success("Insumo modificado exitosamente", "Has modificado un insumo");
                    insumo.id = idInsumo;
                    insumo.idUnidad = insumo.unidad;
                    actualizarInsumoArray(idInsumo, insumo);

                    var pos = dataTable.dataTable().fnGetPosition(modTr[0]);
                    var unidad = obtenerUnidad(insumo.unidad);
                    var htmlBotones = '<div  class="btn-group"><button data-accion="modificar" data-unidad="' + insumo.unidad + '" data-nombre="' + insumo.nombre + '" data-id="' + idInsumo + '"  class="btn btn-primary modificar-insumo"><i class="fa fa-cog" aria-hidden="true"></i></button> <button data-accion="eliminar" data-nombre="' + insumo.nombre + '" data-id="' + idInsumo + '" class="btn btn-danger eliminar-insumo"><i class="fa fa-times" aria-hidden="true"></i></button></div>';
                    dataTable.dataTable().fnUpdate([insumo.nombre, insumo.descripcion, unidad.nombre, "$" + insumo.costo.toLocaleString(), htmlBotones], pos);

                } else {
                    toastr.error("No se pudo modificar el insumo por el momento", "Error =(");
                }
                domformInsumo.trigger("reset");
                domBtnAgregar.data("accion", "agregar");
                domBtnAgregar.html('<i class="fa fa-plus" aria-hidden="true"></i> Agregar');
                domheaderForm.html("Agregar insumo");


            });
        }
    }
    function accionInsumo(e) {

        e.preventDefault();
        e.stopPropagation();
        var data = $(this).data();
        var accion = data.accion;
        switch (accion) {
            case "agregar":
                accionAgregar();
                break;
            case "modificar":
                accionModificar(data.id)

                break;

  
        }



    }
    function buscarInsumo(id) {
        var len = g_insumos.length; var i = 0;
        for (; i < len; i++) {
            var insumo = g_insumos[i];
            if (insumo.id == id) {
                return insumo;
            }
        }
        return null;

    }
    var modTr = null;

    function eliminarInsumoBD(id) {
        return $.ajax({
            url: "php/new/accionInsumo.php?accion=eliminarInsumo",
            type: "POST",
            data: { id:id }
        });
    }

    function accionBtn(e) {
        e.stopPropagation();
        var element = $(this);
        var data = element.data();
        switch (data.accion) {
            case "modificar":
                domheaderForm.html("Modificar insumo ID " + data.id);
                var insumo = buscarInsumo(data.id);
                domInputsInsumo.nombre.val(insumo.nombre);
                domInputsInsumo.descripcion.val(insumo.descripcion);
                domInputsInsumo.costo.val(insumo.costo);
                domInputsInsumo.unidades.val(insumo.idUnidad);
                domBtnAgregar.data("accion", "modificar");
                domBtnAgregar.data("id", data.id);
                domBtnAgregar.html('<i class="fa fa-cog" aria-hidden="true"></i></i> Modificar');
                modTr = element.parent().parent().parent();

                break;
            case "eliminar":
                bootbox.confirm({
                    title: "&iquestEliminar insumo?",
                    message: "&iquestSeguro que deseas eliminar este insumo?",
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> No'
                        },
                        confirm: {
                            label: '<i class="fa fa-check"></i> Si'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            eliminarInsumoBD(data.id).done(function (data) {
                                var resp = JSON.parse(data);
                                if (resp.exito) {
                                    toastr.success("Insumo eliminado existosamente", "Has eliminado un insumo");
                                    modTr = element.parent().parent().parent();
                                    var pos = dataTable.dataTable().fnGetPosition(modTr[0]);
                                    dataTable.dataTable().fnDeleteRow(pos);
                                    domformInsumo.trigger("reset");

                                } else {
                                    toastr.error("No se ha podido eliminar este insumo debido a que otros elementos del sistema dependen de este", "Error");

                                }

                            });
                        }
                    }
                });
     


                break;
        
        }
    }
    $(document).ready(function () {
        domUnidades = $("#insumoUnidades");
        domTableInsumos = $("#tableInsumos");
        domBtnAgregar = $("#btnAgregarInsumo");
        domformInsumo = $("#formInsumo");
        dataTable = $("#tableInsumosDt");
        domheaderForm = $("#headerForm");
        domInputsInsumo = { "nombre": domformInsumo.find("#insumoNombre"), "unidades": domUnidades, "descripcion": domformInsumo.find("#insumoDesc"), "costo": domformInsumo.find("#insumoCosto") };

        renderUnidades();
        renderInsumos();
        domBtnAgregar.data("accion", "agregar");
        domBtnAgregar.click(accionInsumo);
        domTableInsumos.on("click", "div.btn-group button", accionBtn);
    });



})()