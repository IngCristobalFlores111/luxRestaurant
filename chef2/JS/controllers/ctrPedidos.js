function ctrlPedidos($scope, $http, $interval) {
    $scope.filterQ = function()
    {
        var q = $scope.q;
        if (q != "") {
            var len = g_pedidos.length; var i = 0; var filtered = [];
            q = q.toLowerCase();
            for (; i < len; i++) {
                var p = g_pedidos[i];
                var nombre = p.nombre.toLowerCase(); var comentario = p.comentarios.toLowerCase(); var desc = p.descripcion.toLowerCase();
                var mesa = p.mesa.toLowerCase();
                if (nombre.indexOf(q) != -1 || comentario.indexOf(q) != -1 || desc.indexOf(q) != -1 || mesa.indexOf(q)!=-1) {
                    filtered.push(p);
                }
            }
            console.log(filtered);
            $scope.rows = getRows(3, filtered);

        } else {
            $scope.rows = getRows(3, g_pedidos);
        }

    }
    $scope.updateUsr = function () {
        var usr = $scope.usr;
        if (usr.nombre == "" || usr.usr == "" || usr.pass == "") {
            toastr.info("Informacion invalida,debes de llenar todos los campos");
        } else {
            $http.post("PHP/accionChef.php?accion=updateUsr", { pass: usr.pass, nombre: usr.nombre, user: usr.usr }).then(function (resp) {
                if (resp.data.exito) {
                    toastr.success("Informacion actualizada correctamente", "Usuario actualizado");
                } else {
                    toastr.error("Upsss... ha ocurrrido un error, intentalo mas tarde");
                }

            });

        }
    }
    $scope.abrirConfig = function () {
        $("#modalUsrSetting").modal("show");

    }
     $scope.usr = usr;
     $interval(function () {
         var hoy = new Date(); 
         usr.timestamp = usr.timestamp.replace(/-/g, '/');
         var f = new Date(usr.timestamp);
        f = new Date(f.getTime());
        $scope.usr.tiempo=  getElapsedTime(hoy, f);
     }, 1000);

     $scope.logOut = function () {
         $http.post("PHP/logOut.php").then(function () {
             location.href = "../login/session.html";
         });
     }

    $scope.abrirModal = function (p) {
        var html = "<label>Descripci&oacute;n del platillo:</label><br>" + p.descripcion + "<br>";
        html += "<label>Comentarios del mesero:</label><br>" + p.comentarios + "<br>";
        html += "<label class='label label-info'>" + p.mesa + "</label>";
        html += "<label class='label label-success'>Cantidad x" + (p.cantidad - p.cantidad_terminado) + "</label><br>";
        var hoy = new Date();
        var fecha_llegada = new Date(p.fecha_llegada.trim().replace(/-/g, '/'));
        var tiempo_llegada = getElapsedTime(hoy,fecha_llegada);
        html += "<label>LLego: <i class='fa fa-clock-o' aria-hidden='true'></i> " + tiempo_llegada + "</label>";
        $scope.modal = { nombre: p.nombre, descripcion: html, img: p.imagepath };

        $("#modalPlatilloDetalle").modal("show");
    }
    $scope.modal = { nombre: "", descripcion: "", img:"" };
    var g_rows = [];
    var g_pedidos = [];
    $scope.filtros = { categoria: undefined };

    function refreshPedidos() {
        $http.get("PHP/fetchData.php", { params: { accion: "refreshPedidos" } }).then(function (resp) {
            if (resp.data != 0) {
                g_pedidos = resp.data;
                g_rows = getRows(3, g_pedidos);
                $scope.rows = g_rows;
                refreshTime();
            }
            });
    }
    $interval(refreshPedidos, 2500);
    function parseDate(input, format) {
        format = format || 'yyyy-mm-dd'; // default format
        var parts = input.match(/(\d+)/g),
            i = 0, fmt = {};
        // extract date-part indexes from the format
        format.replace(/(yyyy|dd|mm)/g, function (part) { fmt[part] = i++; });

        return new Date(parts[fmt['yyyy']], parts[fmt['mm']] - 1, parts[fmt['dd']]);
    }
    function getElapsedTime(date_now, date_future) {
        var delta = Math.abs(date_future - date_now) / 1000;

        // calculate (and subtract) whole days
        var days = Math.floor(delta / 86400);
        delta -= days * 86400;

        // calculate (and subtract) whole hours
        var hours = Math.floor(delta / 3600) % 24;
        delta -= hours * 3600;

        // calculate (and subtract) whole minutes
        var minutes = Math.floor(delta / 60) % 60;
        delta -= minutes * 60;

        // what's left is seconds
        var seconds = delta % 60;
        if (isNaN(hours) || isNaN(minutes) || isNaN(seconds)) {
            return null;
        }
        seconds = parseFloat(seconds.toFixed(0));
        var str_days = (days > 0) ? (days + " dias ") : "";
        var str_hours = (hours > 0) ? (hours + " horas ") : "";
        var str_seconds = (seconds > 0) ? (seconds + " segundos ") : "";
        var str_minutes = (minutes > 0) ? (minutes + " minutos ") : "";
        return "Hace " + str_days + str_hours + str_minutes + str_seconds;



    }
    $scope.tiempo = new Date();
    $interval(function () { $scope.tiempo = new Date(); }, 1000);
    function refreshTime() {
        var rows = $scope.rows;
        var len = rows.length; var i = 0;
        var hoy = new Date();
        for (; i < len; i++) {
            var j = 0; var pedidos = rows[i]; var len2 = pedidos.length;
            for (; j < len2; j++) {
                var pedido = pedidos[j];
                var f = pedido.fecha_llegada.trim();
                f = f.replace(/-/g, '/');
               // f = f.replace(' ', 'T');
                 
                var fecha = new Date(f);
                fecha = new Date(fecha.getTime());
           
                $scope.rows[i][j].tiempo = getElapsedTime(hoy, fecha);
                //console.log($scope.rows[i][j].fecha_llegada);
            }

        }
    }
    $interval(refreshTime, 3000);


    function filtarPorCategoria(idCategoria) {
        var len = g_rows.length; var i = 0;
        var filtered_rows = [];
        for (; i < len; i++) {
            var pedidos = g_rows[i];

            var j = 0; var len2 = pedidos.length;
            for (; j < len2; j++) {
                var pedido = pedidos[j];
                if (pedido.idCategoria == idCategoria) {
                    filtered_rows.push(pedido);
                }

            }

        }
        return filtered_rows;
    }
    function getRandomColor() {
        var colors = ['#1abc9c', '#2ecc71', '#3498db', '#9b59b6', '#34495e', '#16a085', '#27ae60', '#2980b9', '#8e44ad', '#f1c40f', '#e67e22', '#e74c3c', '#c0392b', '#2c3e50', '#d35400', '#f39c12'];
        return colors[Math.floor(Math.random() * colors.length) + 0];
    }
    $scope.rows = [];
    $scope.categorias = [];
    function getRows(breakpoint, pedidos) {
        var len = pedidos.length; var i = 0;
        var rows = []; var temp = [];
        for (; i < len; i++) {
            if (i % breakpoint == 0 && i != 0) {
                rows.push(temp);
                temp = [];
            }
            temp.push(pedidos[i]);
        }
        var len2 = rows.length * breakpoint;
        if (len > len2) {
            //var leftOvers = len - len2;
            i = len2; temp = [];
            for (; i < len; i++) {
                temp.push(pedidos[i]);
            }
            rows.push(temp);
        }

        return rows;
    }
    $http.get("PHP/fetchData.php?accion=init").then(function (resp) {
        var data = resp.data;
        g_pedidos = data.pedidos;
      //  $scope.pedidos = g_pedidos;
       // console
        g_rows = getRows(3, g_pedidos);
        $scope.rows = g_rows;
        refreshTime();
        var categorias = data.categorias;
        var len = categorias.length; var i = 0;
        for (; i < len; i++) {
            categorias[i].style = { 'background-color': getRandomColor() };
        }
        $scope.categorias = categorias;

    });
    var anterior = 0;
    $scope.pedidos = [];
    $scope.filtrarCategoria = function (c) {
        if (c == anterior) {
            $scope.rows = g_rows;
            anterior = 0;
        } else {
            var filtered = filtarPorCategoria(c.idCategoria);
            $scope.rows = getRows(3, filtered);
            //  console.log(filtered);
        }
        anterior = c;
    }
    function eliminarPedido(id) {
        var len = g_pedidos.length; var i = 0;
        
        for (; i < len; i++) {
            var p = g_pedidos[i];
            if (p.id == id) {
                g_pedidos.splice(i, 1);
                break;
            }

        }
    }
    $scope.despachar = function (p) {
        var cantidad = p.cantidad - p.cantidad_terminado;
        if (cantidad > 0) {
            p.cantidad_terminado++;
            
            $http.post("PHP/accionChef.php?accion=despachar", { id: p.id }).then(function (resp) {
                if (resp.data.exito) {

                    if ((p.cantidad - p.cantidad_terminado) == 0) {
                        eliminarPedido(p.id);

                        g_rows = getRows(3, g_pedidos);
                        $scope.rows = g_rows;
                    }
                }
            });
        }

    }
}