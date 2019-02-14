






function cleanForm()
{
    $("#dateFrom").val('');
    $("#dateTo").val('');
}

function obtenerMes(mes)
{
    switch(mes)
    {
        case 1: return "Enero"; break;
        case 2: return "Febrero"; break;
        case 3: return "Marzo"; break;
        case 4: return "Abril"; break;
        case 5: return "Mayo"; break;
        case 6: return "Junio"; break;
        case 7: return "Julio"; break;
        case 8: return "Agosto"; break;
        case 9: return "Septiembre"; break;
        case 10: return "Octubre"; break;
        case 11: return "Noviembre"; break;
        case 12: return "Diciembre"; break;
    }
    return "Error";

}

// obtiene los datos y despliega en graficas y en texto informacion estadisitica


function obtenerEstadisticas(opcion,platillo,fechaDesde,FechaHata)  // si platillo=null, no importa el platillo 
{
    switch(opcion)
    {
        case 2:  // por dia por todos los platillos

            $.post("PHP/obtenerEstadisticas.php", { opcion: 2, fechaDesde: fechaDesde, fechaHasta: FechaHata, platillo: platillo }, function (data) {
                var obj = JSON.parse(data);
                var sumas = obj.suma_todo;
                //totales
                var sum_total = sumas[0].total;
                var sum_costos = sumas[0].costo;
                var sum_ganancias = sumas[0].ganancia;
                //
                var promedios = obj.promedios_todo;
                //promedios
                var prom_total = promedios[0].total;
                var prom_costo = promedios[0].costo;
                var prom_ganancia = promedios[0].ganancia;
                //puntos de top_mes(mes)
                var top_mes = obj.top_mes;
                var plot_mes = [];
                var i = 0; var len = top_mes.length;
                for (i = 0; i < len; i++) {
                    plot_mes.push({
                        label: top_mes[i].mes,
                        value: top_mes[i].total
                    });
                }
                // puntos a graficas top_dia
                var top_dia = obj.top_dia;
                var plot_dia = [];
                i = 0; len = top_dia.length;
                for (i = 0; i < len; i++) {
                    plot_dia.push({
                        label: top_dia[i].fecha,
                        value: top_dia[i].total
                    });
                }





                // table promedios
                var table_promedios = '<div class="panel panel-primary">';
                table_promedios += '<div class="panel-heading"><label>Promedio vendido por dia</label></div>';
                table_promedios += '<div class="panel-body">';
                table_promedios += '<div class="table-responsive">';

                table_promedios += '<table class="table table-condensed table-hover"><thead>';
                table_promedios += '<tr class="info"><th>Total</th><th>Costo</th><th>Ganancia</th></tr></thead><tbody><tr>';
                table_promedios += '<td>' + prom_total + '</td>' + '<td>' + prom_costo + '</td>' + '<td>' + prom_ganancia + '</td>'
                table_promedios += '</tr></tbody></table></div></div>';
                DOM_promedio_vendido.html(table_promedios);
                // table sumas
                var table_sumas = '<div class="table-responsive"><table class="table table-condensed table-hover"><thead>';
                table_sumas += '<tr class="info"><th>Total</th><th>Costo</th><th>Ganancia</th></tr></thead><tbody><tr>';
                table_sumas += '<td>' + sum_total + '</td>' + '<td>' + sum_costos + '</td>' + '<td>' + sum_ganancias + '</td>'
                table_sumas += '</tr></tbody></table></div></div></div>';
                DOM_total_vendido.html(table_sumas);

                //headres de panels
                DOM_header1.html("<label>Top 3 dias mejor vendidos</label>");
                DOM_header2.html("<label>Top 3 meses mejor vendidos</label>");
                // header resultado
                DOM_results_header.html("<h4>Total vendido de " + fechaDesde + " hasta " + FechaHata+"</h4>");

                // refresh doms
                DOM_donut1.html('');
                DOM_donut2.html('');
                DOM_donut3.html('');
                // graficar
                Morris.Donut({
                    resize: true,
                    element: 'donut1',
                    data: plot_dia
                });
                Morris.Donut({
                    resize: true,
                    element: 'donut2',
                    data: plot_mes
                });

                var top_platillos = obj.top_platillos;
                len = top_platillos.length;
                i = 0;
                var plot_data_platillos = [];
                for (i = 0; i < len; i++) {
                    plot_data_platillos.push({
                        label: top_platillos[i].nombre,
                        value: top_platillos[i].cantidad

                    });

                }

                Morris.Donut({
                    resize: true,
                    element: 'donut3',
                    data: plot_data_platillos
                });


                DOM_top_platillos.show();



            });


            




            break;


        case 1:
            $.post("PHP/obtenerEstadisticas.php", { opcion: 1, fechaDesde: fechaDesde, fechaHasta: FechaHata, platillo: platillo }, function (data) {

                DOM_top_platillos.hide();
                var obj = JSON.parse(data);
                var sumas = obj.suma_todo;
                //totales
                var sum_total = sumas[0].total;
                var sum_costos = sumas[0].costo;
                var sum_ganancias = sumas[0].ganancia;
                //
                var promedios = obj.promedios_todo;
                //promedios
                var prom_total = promedios[0].total;
                var prom_costo = promedios[0].costo;
                var prom_ganancia = promedios[0].ganancia;
                //puntos de top_mes(mes)
                var top_mes = obj.top_mes;
                var plot_mes = [];
                var i = 0;var len = top_mes.length;
                for(i=0;i<len;i++)
                {
                    plot_mes.push({
                        label: top_mes[i].mes,
                        value: top_mes[i].total
                    });
                }
                // puntos a graficas top_dia
                var top_dia = obj.top_dia;
                var plot_dia = [];
                 i = 0;  len = top_dia.length;
                for (i = 0; i < len; i++) {
                    plot_dia.push({
                        label: top_dia[i].fecha,
                        value: top_dia[i].total
                    });
                }





                // table promedios
                var table_promedios = '<div class="panel panel-primary">';
                table_promedios += '<div class="panel-heading"><label>Promedio vendido por dia</label></div>';
                table_promedios += '<div class="panel-body">';
                table_promedios += '<div class="table-responsive">';

                 table_promedios += '<table class="table table-condensed table-hover"><thead>';
                table_promedios += '<tr class="info"><th>Total</th><th>Costo</th><th>Ganancia</th></tr></thead><tbody><tr>';
                table_promedios += '<td>' + prom_total + '</td>' + '<td>' + prom_costo + '</td>' + '<td>' + prom_ganancia + '</td>'
                table_promedios += '</tr></tbody></table></div></div></div>';
                DOM_promedio_vendido.html(table_promedios);
                // table sumas
                var table_sumas = '<div class="table-responsive"><table class="table table-condensed table-hover"><thead>';
                table_sumas += '<tr class="info"><th>Total</th><th>Costo</th><th>Ganancia</th></tr></thead><tbody><tr>';
                table_sumas += '<td>' + sum_total + '</td>' + '<td>' + sum_costos + '</td>' + '<td>' + sum_ganancias + '</td>'
                table_sumas += '</tr></tbody></table></div></div>';
                DOM_total_vendido.html(table_sumas);

                //headres de panels
                DOM_header1.html("<label>Top 3 dias mejor vendidos</label>");
                DOM_header2.html("<label>Top 3 meses mejor vendidos</label>");
                // header resultado
                DOM_results_header.html("<h4>" + platillo + " por total vendido de " + fechaDesde + " hasta " + FechaHata+"</h4>");

                // refresh doms
                DOM_donut1.html('');
                DOM_donut2.html('');
                // graficar
                Morris.Donut({
                    resize: true,
                    element: 'donut1',
                    data: plot_dia
                });
                Morris.Donut({
                    resize: true,
                    element: 'donut2',
                    data: plot_mes
                });
                
               

                

            });


            break;
        case 0:  // platillo especifico por dia por cantidad 

            $.post("PHP/obtenerEstadisticas.php", { opcion: 0, fechaDesde: fechaDesde, fechaHasta: FechaHata, platillo: platillo }, function (data) {
                DOM_top_platillos.hide();

                var obj = JSON.parse(data);
                var promedio_dia = obj.promedio_dia[0].promedio_dia;
                var promedio_mes = obj.promedio_mes[0].promedio_mes;
                var total = obj.total[0].total;
                var datos = obj.datos;
                var i = 0;
                var len = datos.length;
                var plot_data = [];
                for(i=0;i<len;i++)
                {
                    plot_data.push({
                        label: datos[i].fecha,
                        value: datos[i].cantidad

                    });
                    
                }

                datos = obj.datos2;
                i = 0;
                len = datos.length;
                var plot_data2 = [];
                for (i = 0; i < len; i++) {
                    plot_data2.push({
                        label: datos[i].mes,
                        value: datos[i].cantidad

                    });

                }



                // Cambiar DOM pertinente 

                var table_promedios = '<div class="panel panel-primary">';
                table_promedios += '<div class="panel-heading"><label>Promedio vendido</label></div>';
                table_promedios += '<div class="panel-body">';


                table_promedios += '<div class="table-responsive"><table class="table table-condensed table-hover">';
                table_promedios += '<thead><tr class="info"><th>Por dia </th> <th>Por mes</th></tr></thead> <tbody><tr>';
                table_promedios += "<td>" + promedio_dia + "</td>";
                table_promedios += "<td>" + promedio_mes + "</td>";
                table_promedios += "</tr></tbody></table></div>";
                
                table_promedios += '</div></div>';

                DOM_promedio_vendido.html(table_promedios);
                DOM_total_vendido.html(total + " "+platillo + " vendidos" );
                
                DOM_header1.html("<label>Top 3 dias mejor vendidos</label>");
                DOM_header2.html("<label>Top 3 meses mejor vendidos</label>");
                DOM_results_header.html("<h4>" + platillo + " por cantidad vendida de " + fechaDesde + " hasta " + FechaHata+"</h4>");
                // refresh doms
                DOM_donut1.html('');
                DOM_donut2.html('');
                // graficar
                Morris.Donut({
                    resize :true,
                    element: 'donut1',
                    data: plot_data
                });
                Morris.Donut({
                    resize: true,
                    element: 'donut2',
                    data: plot_data2
                });




            });



            break;

    }


}

var contentPage = null;
var nav_elements = null;
var platillosSelect = null;
$(document).ready(function () {
    contentPage = $("#contentPage");
    nav_elements = $("#nav li");
    platillosSelect = $("#platillosSelect");
    platillosSelect.hide();
});

function toRecetas(e) {
    if (nav_elements.length == 0) {
        nav_elements = $("#nav li");

    }
    nav_elements.removeClass("active-page");
    $(e).parent().addClass("active-page");
    location.reload();
}

function toInsumos(e) {
    if (nav_elements.length == 0) {
        nav_elements = $("#nav li");

    }
    if (contentPage.length == 0) {
        contentPage = $("#contentPage");
    }

    nav_elements.removeClass("active-page");
    $(e).parent().addClass("active-page");
    contentPage.load("htms/insumos.htm", function () {
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.src = "js/insumos.js";
        document.head.appendChild(s);
      
    });
}

function toUsers(e)
{
    if (nav_elements.length == 0) {
        nav_elements = $("#nav li");

    }
    if (contentPage.length == 0) {
        contentPage = $("#contentPage");
    }
    nav_elements.removeClass("active-page");
    $(e).parent().addClass("active-page");
    contentPage.load("htms/users.htm");

    $.post("PHP/loadUsrs.php", function (data, status) {
        $("#usersTable").html(data);
        $("#usersTable tbody tr td.pswd").each(function (i, element) {
            $(element).text("****");

        });

    });

}
    function editUsr(id)
    {
        $("#opModal").val("edit");
        $("#ModalTittle2").html("Editar informacion de usuario");
        $("#idUser").val(id);
        $.post("PHP/getUserdata.php",
     {
         id:id
     },
     function (data, status) {
         var a = data.split(":");
         var nombre = a[0];
         var rol = a[1];
         var user = a[2];
         var pass = a[3];

         $("#nombreUsr").val(nombre);
         $("#usrRol").val(rol);
         $("#usrname").val(user);
         $("#passWrd").val(pass);
         $("#myModal2").modal()


     });






    }
    function deleteUsr(id)
    {
    
        if(confirm("Seguro que desea eliminar a este usuario de manera permanente?"))
        {
            $.post("PHP/deleteUser.php",
        {
            id: id
       
        },
        function (data, status) {
            $("#usersTable").html(data);

            if ($(this).is(":checked")) {
                $.post("PHP/loadUsrs.php", function (data, status) {
                    $("#usersTable").html(data);

                });


            }
            else {



                $("#usersTable tbody tr td.pswd").each(function (i, element) {
                    $(element).text("****");

                });
            }
        });


        }



    }
    function SearchUser()
    {
        var query = $("#searchUser").val();

        //searchUser.php
        $.post("PHP/searchUser.php",
       {
           query:query
       },
       function (data, status) {


           $("#usersTable").html(data);



       });


    }
    function usrModalResult()
    {
        var action = $("#opModal").val();
        var nombre = $("#nombreUsr").val();
        var rol = $("#usrRol").val();
        var usr = $("#usrname").val();
        var pass = $("#passWrd").val();
        nombre = nombre.trim();
        rol = rol.trim();
        usr = usr.trim();
        pass = pass.trim();
        if(action=='add') // add user
        {

     
            var doit = true
            if (nombre == '' || rol == '' || usr == '' || pass == '')
                doit = false;


            if (doit) {

                var conf = confirm("Este seguro que desea agregar este usuario?");
                if (conf) {








                    $.post("PHP/insertUser.php",
               {
                   nombre: nombre,
                   usuario: usr,
                   rol: rol,
                   pass: pass
               },
               function (data, status) {
                   $("#usersTable").html(data);
                   if ($(this).is(":checked")) {
                       $.post("PHP/loadUsrs.php", function (data, status) {
                           $("#usersTable").html(data);

                       });


                   }
                   else {



                       $("#usersTable tbody tr td.pswd").each(function (i, element) {
                           $(element).text("****");

                       });
                   }



               });
                }
            }
            else
            {
                alert("Algunos campos no se han completado");
            }
        }

        if(action=="edit") // editar usuario
        {
            var id = $("#idUser").val();

            var doit = true
            if (nombre == '' || rol == '' || usr == '' || pass == '')
                doit = false;

            if(doit)
            {

                var conf = confirm("Seguro que deseas modificar este usuario?");
                if(conf)
                {
                    $.post("PHP/updateUser.php",
          {
              nombre: nombre,
              usuario: usr,
              rol: rol,
              pass: pass,
              id:id
          },
          function (data, status) {
              $("#usersTable").html(data);
              if ($(this).is(":checked")) {
                  $.post("PHP/loadUsrs.php", function (data, status) {
                      $("#usersTable").html(data);

                  });


              }
              else {



                  $("#usersTable tbody tr td.pswd").each(function (i, element) {
                      $(element).text("****");

                  });
              }


          });


                }




            }
            else
            {
                alert("No se han complatado todos los campos");

            }



        }

    }
    function addUser()
    {
        $("#opModal").val("add");
        $("#usrRol").val("");
        $("#nombreUsr").val("");

        $("#usrname").val("");

        $("#passWrd").val("");
        $("#ModalTittle2").html("Agregar nuevo usuario");

        $("#myModal2").modal()


    }
    function changeInputs(select)
    {

        var filter = $(select).val();
        if (filter == 'dia')
        {
            graficaDateFrom.attr("type", "date");
            graficaDateTo.attr("type", "date");

        }
        if (filter == 'mes')
        {
            graficaDateFrom.attr("type", "month");
            graficaDateTo.attr("type", "month");
        }
    }

    function plotResultMonth()
    {
        var monthFrom = $("#dateFrom").val();
        var monthTo = $("#dateTo").val();
   
        
       
        var yearFrom = '';
        var yearTo = '';
   
        var goOn = true;
        if (monthFrom.trim() == '' || monthTo.trim() == '') {
            alert("Introduce las fechas primero");
            goOn = false;

        }
        else {
            var dfrom = new Date(monthFrom);
            var dTo = new Date(monthTo);
     
            var f = monthFrom.split("-");
            monthFrom = f[1];
            yearFrom = f[0];

            var t = monthTo.split("-");
            monthTo = t[1];
            yearTo = t[0];

            if(dfrom>dTo)
            {
                alert("Fecha de partida debe de ser menor que la de destino");
                goOn = false;
            }


        }
        if(goOn) // si todo salio bien continua
        {

            if ($("#chPlatillo").is(":checked"))// filtrar por platillo
            {

                //cantChek
                if($("#cantChek").is(":checked")) // por solo cantidad
                {
                    var nombre = $("#selectPlatillo").val();




                    $.post("PHP/getPointsMonthFilterpQuant.php",
     {

         monthFrom: monthFrom,
         monthTo: monthTo,
         yearFrom: yearFrom,
         yearTo: yearTo,
         nomPlatillo: nombre
     },
     function (data, status) {
  

       
         var obj = JSON.parse(data);
         var Acantidades = [];
         //var Afechas = [];
         var plot_data = [];
         var len = obj.result.length;
         for (var i = 0; i < len; i++)
         {
             Acantidades.push(obj.result[i].cantidad);  //Y
            // Afechas.push(obj.result[i].monthDate); // x
             plot_data.push({
                 x: obj.result[i].mes, 
                 y: obj.result[i].cantidad
              
             });

         }

         // id plot = canvasCont
         
         $("#canvasCont").html('');
         // generate plot 
         new Morris.Line({
             // ID of the element in which to draw the chart.
             resize: true,
             element: 'canvasCont',
             // Chart data records -- each entry in this array corresponds to a point on
             // the chart.
             data: plot_data,

             // The name of the data record attribute that contains x-values.
             xkey: 'x',
             // A list of names of data record attributes that contain y-values.
             ykeys: ['y'],
             // Labels for the ykeys -- will be displayed when you hover over the
             // chart.
             labels: ['Cantidad']
         });

         
         var from_date = yearFrom + "-" + monthFrom + "-01";
         var to_date = yearTo + "-" + monthTo + "-29";
         obtenerEstadisticas(0, nombre, from_date, to_date);





     });






                }
                else // por total 
                {
                    var nombre = $("#selectPlatillo").val();

                    $.post("PHP/getPointsMonthFilterPlatillo.php",
       {
           monthFrom: monthFrom,
           monthTo: monthTo,
           yearFrom: yearFrom,
           yearTo: yearTo,
           nomPlatillo: nombre
       },
       function (data, status) {
     
           var yTotales = [];
           var yCostos = [];
           var yGanancias = [];
           var obj = JSON.parse(data);

           var len = obj.result.length;

           

           var plot_data = [];
           for (var i = 0; i < len; i++)
           {
               yTotales.push(obj.result[i].total);
               yCostos.push(obj.result[i].costo);
               yGanancias.push(obj.result[i].ganancia);
               plot_data.push({
                   x: obj.result[i].mes,
                   a: yTotales[i],
                   b: yCostos[i],
                   c:yGanancias[i]

               });




           }

           $("#canvasCont").html('');
          


           new Morris.Line({
               // ID of the element in which to draw the chart.
               resize: true,
               element: 'canvasCont',
               // Chart data records -- each entry in this array corresponds to a point on
               // the chart.
               data:plot_data,

               // The name of the data record attribute that contains x-values.
               xkey: 'x',
               // A list of names of data record attributes that contain y-values.
               ykeys: ['a', 'b','c'],

               // Labels for the ykeys -- will be displayed when you hover over the
               // chart.
               labels: ['Total', 'Costo','Ganancia']
           });

           var from_date = yearFrom + "-" + monthFrom + "-01";
           var to_date = yearTo + "-" + monthTo + "-29";
           obtenerEstadisticas(1, nombre, from_date, to_date);


       });



                }

            }
            else {

                $.post("PHP/getPointsMonthFilter.php",
       {
           monthFrom:monthFrom,
           monthTo: monthTo,
           yearFrom: yearFrom,
           yearTo: yearTo
       },
       function (data, status) {

     


           var yTotales = [];
           var yCostos = [];
           var yGanancias = [];
        
           var obj = JSON.parse(data);
           var len = obj.result.length;

          
           var data_plot = [];
           for (var i = 0; i < len; i++) {
               yTotales.push(obj.result[i].total);
               yCostos.push(obj.result[i].costo);
               yGanancias.push(obj.result[i].ganancia);

               data_plot.push({
                   x: obj.result[i].mes,
                   a: yTotales[i],
                   b: yCostos[i],
                   c: yGanancias[i]

               });


           }

           $("#canvasCont").html('');


           new Morris.Line({
               // ID of the element in which to draw the chart.
               resize: true,
               element: 'canvasCont',
               // Chart data records -- each entry in this array corresponds to a point on
               // the chart.
               data: data_plot,

               // The name of the data record attribute that contains x-values.
               xkey: 'x',
               // A list of names of data record attributes that contain y-values.
               ykeys: ['a','b','c'],
               // Labels for the ykeys -- will be displayed when you hover over the
               // chart.
               labels: ['Total','Costo','Ganancia']
           });

           var from_date = yearFrom + "-" + monthFrom + "-01";
           var to_date = yearTo + "-" + monthTo + "-29";
           obtenerEstadisticas(2, null, from_date, to_date);

           






          
       


       });



            }



        }
  

    }
    function toEstadisticas(e)
    {
        if (contentPage.length == 0) {
            contentPage = $("#contentPage");
        }
        if (nav_elements.length == 0) {
            nav_elements = $("#nav li");

        }
        nav_elements.removeClass("active-page");
        $(e).parent().addClass("active-page");
                  
        contentPage.load("htms/statistics.htm", function (data) {
            $("#selectPlatillo").hide();
            $("#txtPlat").hide();
          
        });

   


    }
   

    function toEditor(e)
    {
        if (contentPage.length == 0) {
            contentPage = $("#contentPage");
        }
        if (nav_elements.length == 0) {
            nav_elements = $("#nav li");

        }
        nav_elements.removeClass("active-page");
        $(e).parent().addClass("active-page");
 
        contentPage.load("htms/editor.htm", function (data) {
            // traer platillos
            $.post("PHP/fillTable.php",
            function (data, status) {
                $("#tablePlatillos").html(data);
            });

        });
   

    }
    function filterTable()
    {
        var query = $("#searchin").val();

        $.post("PHP/updateTable.php",
       {
           platillo: query
       },
       function (data, status) {
           $("#tablePlatillos").html(data);

       });

    }
    function toWelcome(e)
    {
        if (contentPage.length == 0) {
            contentPage = $("#contentPage");
        }
        if (nav_elements.length == 0) {
            nav_elements = $("#nav li");

        }
   
        contentPage.load("htms/home.htm", function () {
            nav_elements.removeClass("active-page");
            $(e).parent().addClass("active-page");
        });

                            
                               
    }
    function toCuentas(e)
    {
        if (contentPage.length == 0) {
            contentPage = $("#contentPage");
        }
        if (nav_elements.length == 0) {
            nav_elements = $("#nav li");

        }
        nav_elements.removeClass("active-page");
        $(e).parent().addClass("active-page");
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1; 
        var yyyy = today.getFullYear();
        var cont = "<h4 id='headerFecha'>Cuentas del dia de hoy " + dd + "-" + mm + "-" + yyyy + "</h4><br>";

        $.post("PHP/getTableCuentas.php",
       function (data, status) {
           cont += data;

           cont += "<br><h4>Filtrar por fecha</h4><br>";
           cont += "<label>Desde:<input type='date' id='dateFromTable' /></label>&nbsp ";
           cont += "<label>Hasta:<input type='date' id='dateToTable' /></label>&nbsp<br> ";
           cont += "<button type='button' onclick='filterSalesTable()'  class='btn btn-primary'>Filtrar</button>";


           contentPage.html(cont);

       });





    }
    function filterSalesTable()
    {

        var desde = $("#dateFromTable").val();
        var hasta = $("#dateToTable").val();
        if (desde.trim() == '' || hasta.trim() == '')
            alert("Selecciona las fechas primero");
        else {
            var fechaFrom = new Date(desde);
            var fechaTo = new Date(hasta);
            if (fechaFrom > fechaTo)
                alert("La fecha de partida tiene que ser menor de la fecha destino");
            else {
           

                $.post("PHP/filterSalesTableByDate.php",
                {
                    fechaFrom: desde,
                    fechaTo:hasta
                },
                function (data, status) {
                    $("#tableCuentas").html(data);
                    //$("#headerFecha").html("Cuentas de " + desde + " al " + hasta);
                });
            }
        }
    }
    function getTotalLabel(p1,p2,p3,platillo,opcion)
    {// opcion = 0; getTotalLabel( pointsCantidad,null,null,plat, 0); // mostar total cantidad 

        // opcion =1 ; por $ ;           getTotalLabel(y, y2, y3, plat,1); // mostar total por platillo  

        // opcion = 2;           getTotalLabel( y,y2,y3,null, 2); 

        var sum = 0;
        var sum2 = 0;
        var sum3 = 0;
        var output = '';
        //    alert(p1 + "\n" + p2 + "\n" + p3);
        switch(opcion)
        {

            case 0:
                for (var i = 0; i < p1.length; i++) {
                    if(p1[i].trim()!='')
                        sum += parseInt(p1[i]);

                }
                output+=platillo +" Vendido un total de "+sum;



                break;
            case 1:
                for (var i = 0; i < p1.length; i++)
                {
                    if (p1[i].trim() != '') {
                        sum += parseInt(p1[i]);
                        sum2 += parseInt(p2[i]);
                        sum3 += parseInt(p3[i]);
                    }
                }
                output += platillo + "<br> Total: <p class='quant'>$" + sum + "</p> Costo: <p class='quant'>$" + sum2 + "</p> Ganancia: <p class='quant'>$" + sum3+"</p>";
                break;

            case 2:

                for (var i = 0; i < p1.length; i++) {
                    if (p1[i].trim() != '') {
                        sum += parseInt(p1[i]);
                        sum2 += parseInt(p2[i]);
                        sum3 += parseInt(p3[i]);
                    }
                }
                output += "Total: <p class='quant'>$" + sum + "</p> Costo: <p class='quant'>$" + sum2 + "</p> Ganancia: <p class='quant'>$" + sum3 + "</p>";
                break;

               




        }
        $("#outputLabel").html(output);


    }
    function ShowPlatilloQuant()
    {


        var Search = $("#txtSearchPlati").val();
        //SearchPlatilloQuant.php
        $.post("PHP/SearchPlatilloQuant.php",
        {
            query: Search
       
        },
        function (data, status) {
            $("#TableResultByPlatillo").html(data);
        });


    }
    function toTopSale(e)
    {
        if (contentPage.length == 0) {
            contentPage = $("#contentPage");
        }
        if (nav_elements.length == 0) {
            nav_elements = $("#nav li");

        }
        nav_elements.removeClass("active-page");
        $(e).parent().addClass("active-page");
        contentPage.load("htms/topsale.htm", function (data) {

            $.post("PHP/Top5SalesTable.php",

           function (data, status) {
               var income = data.split("*");
               var table1 = income[0];
               var table2 = income[1];
               $("#top5SaleProducts").html(table1);
               $("#Less5SaleProducts").html(table2);


           });


        });

        // traer categorias de los platillos
        $.post("PHP/getCategories.php",

     function (data, status) {
         var nav = "<li class='active'><a style='cursor:pointer' onclick=\"updateTable('0')\">Todos los productos</a></li>" + data;
         $("#navCategory").html(nav);
         updateTable('0');

     });


    }
    function updateTable(categoria)
    {
  
        $.post("PHP/topCategoryTable.php",
    {
        category: categoria
    },
    function (data, status) {
        $("#TopCategoryTable").html(data);

    });
        $.post("PHP/refreshCategories.php",
    {
        category: categoria
    },
    function (data, status) {
        if(categoria=='0')
            var nav = "<li class='active'><a style='cursor:pointer' onclick=\"updateTable('0')\">Todos los productos</a></li>" + data;
        else
            var nav = "<li><a style='cursor:pointer' onclick=\"updateTable('0')\">Todos los productos</a></li>" + data;

        $("#navCategory").html(nav);

    });
        if (categoria == '0')
            $("#tableHeader").html("Todos los productos");
        else
            $("#tableHeader").html("Productos de categoria " + categoria);
    }

    function activePlatillo(id)
    {
        var checked = '';
        if ($("#check"+id).is(":checked"))
        {
            checked = '1';
        }
        else
        {
            checked = '0';

        }

        $.post("PHP/getPlatilloNameModal.php",
      {
          id: id

      },
      function (data, status) {
          var conf = false;
          if (checked == '1') {
        
              conf = confirm("Seguro que quieres activar el platillo" + data + "?\n Este podra ser vendido en el sistema");
          }
          if(checked=='0')
          {
              conf = confirm("Seguro que quieres desactivar el platillo " + data + "?\n Este ya no podra ser vendido en el sistema");

          }
          if(conf)
          {
              $.post("PHP/activePlatillos.php",
       {
           idplatillo: id,
           check: checked
       },
       function (data, status) {
           $("#tablePlatillos").html(data);


       });
          }
          else
          {
              if (checked == '1') {
                  $("#check" + id).prop('checked', false);
              }
              if(checked=='0')
              {
                  $("#check" + id).prop('checked', true);


              }

          }

      });

   


    }

    function cleanArray(actual) {
        var newArray = new Array();
        for (var i = 0; i < actual.length; i++) {
            if (actual[i]) {
                newArray.push(actual[i]);
            }
        }
        return newArray;
    }


    function htttpRequest(method, file, dataS)
    {
 
        if (method == "post" || method == "POST")
        {
            $.post(file,dataS,
        function (data, status) {
            localStorage.setItem("response", data);
            // $("#" + domElement).html(data);
        


        });


        }
        if (method == "get" || method == "GET")
        {

            $.get(file, data,
     function (data, status) {
         localStorage.setItem("response", data);
         $("#" + domElement).html(data);



     });

        }



    }
    function getJSONResponse()
    {

        var resp = localStorage.getItem("response");
        var obj = JSON.parse(resp);
        return obj;

    }
    function execQuery(sqlCommand)
    {

        $.post("PHP/dbRequest.php",
       {
           query: sqlCommand
       
       },
       function (data, status) {

           localStorage.setItem("response", data);
       });

    }
    function agregarPlatillo(idcuenta, idplatillo, comentario, mesa)
    {
        $.post("PHP/addPlatillo.php",
      {
          idcuenta: idcuenta,
          idplatillo: idplatillo,
          comentario: comentario,
          mesa: mesa

      },
      function (data, status) {

      });


    }
    function obtenerTotalCuenta(idcuenta)
    {
        $.post("PHP/getTotal.php",
    {
        idcuenta:idcuenta

    },
    function (data, status) {
        localStorage.setItem("totalCuenta", data);
    });



    }
    function returnTotal()
    {
        return localStorage.getItem("totalCuenta");

    }
    function eliminarPlatilloOrden(idpedido)
    {
  

        $.post("PHP/eliminarPlatillo.php",
      {
          idpedido: idpedido,


      },
      function (data, status) {

      });
    


    }
    function cargarPlatillosNoTg()

    {
        $.post("PHP/fillTable.php",
  
      function (data, status) {
          $("#tablePlatillos").html(data);

      });


    }

    function cargarPlatillos()
    {
        htttpRequest("POST", "PHP/fillTable.php", null);
        var result = localStorage.getItem("response");
        $("#tablePlatillos").html(result);

    }
    var idrem = 0;
    function defaultCallback()
    {

        execQuery("DELETE FROM tbplatillos WHERE idplatillo=" + idrem);
        setTimeout(function () { toEditor(); }, 500);
    }
    function eliminarPlatillo(id)
    {
        /*  if (confirm("Seguro de eliminar este platillo?")) {
      
              execQuery("DELETE FROM tbplatillos WHERE idplatillo=" + id);
              setTimeout(function () { toEditor(); }, 500);
          }*/
        $.post("PHP/getPlatilloNameModal.php",
       {
           id:id
      
       },
       function (data, status) {
           $("#msgConfirm").html("Se eliminara platillo "+data+" de los registros");
           idrem = id;
           $('#triggerConfirm').trigger("click");

       });
    
        // $('#triggerConfirm').confirmModal();


    

    }

    function editarPlatillo(idp)
    {
 
        $.post("PHP/getPlatilloById.php",
       {
           id: idp
  
       },
       function (data, status) {
       
           var obj = JSON.parse(data);
           var len = obj.platillos.length;

   
           for (var i = 0; i < len; i++) {
               var nombre = obj.platillos[i].nombre;




               var precio = obj.platillos[i].precio;
               var costo = obj.platillos[i].costo;
               var descripcion = obj.platillos[i].descripcion;
               var categoria = obj.platillos[i].categoria;
               var imgPlatillo = obj.platillos[i].imagepath;
               var hotkeys = obj.platillos[i].hotkeys;
               var activado = obj.platillos[i].activado;

               $("#fileToUpload").val("");
               $("#platilloNombre").val(nombre);
               $("#platilloPrecio").val(precio);
               $("#platilloCosto").val(costo);
               $("#platilloDesc").val(descripcion);
               $("#platilloCategoria").val(categoria);
               $("#imgPlatillo").attr("src", "../images/" + imgPlatillo);
               $("#platilloHotKeys").val(hotkeys);
               $("#idHidden").val(obj.platillos[i].idplatillo);
               $("#ModalTittle").text("Editar Producto");
               $("#imgPlatillo").show();
               $("#opHidden").val("0");
               $("#oldpath").val(imgPlatillo);
               if (activado == '1')
                   $("#activado").prop("checked", true);
               if(activado=='0')
                   $("#activado").prop("checked", false);

               $("#fileToUpload").prop("required", false);




           }
           $("#myModal").modal();

       });
    

    }
    function checkdata()  // check text boxes for numbers 
    {
        var precio = $("#platilloPrecio").val();
        var costo = $("#platilloCosto").val();
        if (isNaN(precio) || isNaN(costo)) {
            alert("Costo y precio deben de ser numeros");
            return false;

        }
        else {
            if (parseFloat(precio)>parseFloat(costo))
            {
            
           
                return true;
            }
            else
            {
                alert("Precio debe ser mayor que costo");
                return false;
            }
        }
    }
    function formsumb()
    {
        setTimeout(updateprev, 500);

    }
    function updateprev()
    {
        var im = $('#fileToUpload').val().replace(/C:\\fakepath\\/i, '');
  
        if (im!='')
            $("#imgPlatillo").attr("src", "../images/" + im);
        $("#imgPlatillo").show();
    }
    function platilloModal()
    {
        $("#platilloNombre").val('');
        $("#platilloPrecio").val('');
        $("#platilloCosto").val('');
        $("#platilloDesc").val('');
        $("#platilloCategoria").val('');
        $("#imgPlatillo").hide();
        //  $("#idHidden").val(obj.platillos[i].idplatillo);
        $("#ModalTittle").text("Agregar Producto");
        $("#opHidden").val("1");
        $("#platilloHotKeys").val("");
        $("#fileToUpload").val("");
        $("#fileToUpload").prop("required", true);
        $("#myModal").modal();
    }
    function getTotalByDate()
    {
        var d = domDate1Ventas.val();
        var d2 = domDate2Ventas.val();
            d = d.trim();
            d2 = d2.trim();
            if (d == '' || d2 == '') {
                toastr.warning("Debes seleccionar un rango de fechas");
            }
            else
            {
                var dateFrom = new Date(d);
                var dateTo = new Date(d2);
                if(dateFrom>dateTo)
                {
                    alert("La fecha destino debe de ser mayor que la de partida");
                }
                else  // todo salio bien jijij
                {

                    $.post("PHP/getTotalByDateRange.php",
        {
            dateFrom: d,
            dateTo: d2
        },
        function (data, status) {
            var result = JSON.parse(data);
            var vendido = result.total;
            var ganancias = result.ganancia;
            var costo = result.costo;
       

            if (vendido == null || ganancias == null || costo == null)
                result_ventas.html("No se encontraron resultados");
            else {

                var table = "<table class=\"table table-condensed\">";
                table += "<thead><tr>";
                table += "<th>Total Vendido</th><th>Gasto</th><th>Ganancias</th>";
                table += "</tr></thead><tbody>";
                table += "<tr><td>$" + vendido + "</td><td>$" + costo + "</td><td>$" + ganancias + "</td></tr>";
                table += "</tbody></table>";
                result_ventas.html(table);
            }
        });


                }



            }


        
     
    }
var once = true;
    function showPlatillosList()
    {
        //           $("#selectPlatillo").append('<option>'+nombre+'</option>');

        //$("#selectPlatillo").show();
        if (selectPlatillo.length == 0) {
            selectPlatillo = $("#selectPlatillo");
        }
        if (platillosSelect.length == 0) {
            platillosSelect = $("#platillosSelect");

        }
        if (once) {
            $.post("PHP/getNombrePlatillos.php", function (data, status) {
                var platillos = JSON.parse(data);
                var outSelect = ''; var len = platillos.length; var i = 0;
                for (; i < len; i++) {
                    var platillo = platillos[i];

                    outSelect += '<option value="' + platillo.id + '">' + platillo.nombre + '</option>';

                }
                selectPlatillo.html(outSelect);


            });
            once = false;
        }


        if ($("#chPlatillo").is(":checked"))
        {
            $("#selectPlatillo").show();
            $("#txtPlat").show();
            $("#txtCant").show();
            $("#cantChek").show();
            platillosSelect.show();
           $("#select_cont").show();



        }
        else
        {

            $("#selectPlatillo").hide();
            $("#txtPlat").hide();
            $("#txtCant").hide();
            $("#cantChek").hide();
            platillosSelect.hide();
            $("#select_cont").hide();
            

        }

    }

    function obtenerTop5 (dateFrom,dateTo){
        return $.ajax({
                url: "PHP/new/obtenerTop.php",
                type: "POST",
                data: { fecha_from: dateFrom, fecha_to:dateTo }}
            );

    }
    function obtenerTopMeses(dateFrom, dateTo) {
        return $.ajax({
            url: "PHP/new/obtenerTopMeses.php",
            type: "POST",
            data: { fecha_from: dateFrom, fecha_to: dateTo }
        }
          );
    }
var plot_data_export = []
    function exportarExcel(){
     if(plot_data_export.length==0){
         toastr.info("Debes de obtener resultados primero");
     }else{
         var header={
             fecha:"date",
             cantidad:"integer",
             total:"price",
             costo:"price",
             ganancia:"price",
             nombre:"string"
         };
         var d = new Date();
         var hoy = d.toLocaleDateString();
         hoy=hoy.split("/");
          hoy = hoy.join("_");
         var savePath = "/var/www/luxline.com.mx/phpsandbox/bbq/administrador/excellExport/export_graficas_"+hoy+".xlsx";
         var sheetName = "Exportacion luxrestaurant graficas "+hoy;   
         var postData = {

            header:header,
            rows:plot_data_export,
            savePath:savePath,
            sheetName:sheetName
         };  
        $.post("../../excelGen/gen/ventasExport.php",postData,function(data){
            console.log(data);
            window.open("excellExport/export_graficas_"+hoy+".xlsx");
        });
        }

    }
    function PlotResult() {

        var dateFrom = graficaDateFrom.val();
        var dateTo = graficaDateTo.val();
        if (dateFrom.length == 7) {
            dateFrom += "-01";
        }
        if (dateTo.length == 7) {
            dateTo += "-01";
        }
        
        if (dateFrom == '' || dateTo == '') {
            toastr.warning("Debes de especificar el rango de fechas","Fechas invalidas");
            return;
        }

        var d1 = new Date(dateFrom); var d2 = new Date(dateTo);
        if (d1 > d2) {
            toastr.warning("Fechas invalidas, segunda fecha tiene que ser mayor");
            return;
        }
        var porPlatillo = chckPlatillo.is(":checked");
        var platillo = selectPlatillo.val();
        $.ajax({
            url: "PHP/new/obtenerGraficas.php",
            type: "POST",
            data: { fecha_from: dateFrom, fecha_to: dateTo, por_platillo: (porPlatillo)?'1':'0', idPlatillo: platillo }
        }).done(function (data) {
            console.log(data);
            DOM_whole_data_thing.show();
            canvas.html("");
            var plot_data = [];
            var puntos = JSON.parse(data); var len = puntos.length; var i = 0;
            var total = 0; var costo = 0; var ganancia = 0;
            plot_data_export = puntos;
            for (; i < len; i++) {
                var punto = puntos[i];
                total +=punto.total;
                costo +=punto.costo;
                ganancia += punto.ganancia;
                plot_data.push({
                    x: punto.fecha,
                    a: punto.total,
                    b: punto.costo,
                    c: punto.ganancia


                });

            }
            var total_promedio = total / puntos.length;
            var costo_promedio = costo / puntos.length;
            var ganancia_promedio = ganancia / puntos.length;
            total = total.toFixed(3);
            costo = costo.toFixed(3);
            ganancia = ganancia.toFixed(3);
            new Morris.Line({
                // ID of the element in which to draw the chart.
                resize: true,
                element: 'canvasCont',
                // Chart data records -- each entry in this array corresponds to a point on
                // the chart.
                data: plot_data,

                // The name of the data record attribute that contains x-values.
                xkey: 'x',
                // A list of names of data record attributes that contain y-values.
                ykeys: ['a', 'b', 'c'],
                // Labels for the ykeys -- will be displayed when you hover over the
                // chart.
                labels: ['Total', 'Costo', 'Ganancia']
            });
            if (porPlatillo) {
                DOM_results_header.html(dateFrom + " a " + dateTo + " del platillo " + selectPlatillo.find("option:selected").html());

            } else {
                DOM_results_header.html(dateFrom + " a " + dateTo + " de todos los platillos");

            }var html = '<div class="table-responsive"><table class="table table-bordered">';
            html += "<thead><tr><th>Total</th><th>Costo</th><th>Ganancia</th></tr></thead><tbody>";
            html += "<tr><td>$" + total.toLocaleString() + "</td>";
            html += "<td>$" + costo.toLocaleString() + "</td>";
            html += "<td>$" + ganancia.toLocaleString() + "</td></tr>";
            html += "</tbody>";
            DOM_total_vendido.html(html);
            html = '<div class="table-responsive"><table class="table table-bordered">';
            html += "<thead><tr><th>Total</th><th>Costo</th><th>Ganancia</th></tr></thead><tbody>";
            total_promedio = total_promedio.toFixed(3);
            costo_promedio = costo_promedio.toFixed(3);
            ganancia_promedio = ganancia_promedio.toFixed(3);
            html += "<tr><td>$" + total_promedio.toLocaleString() + "</td>";
            html += "<td>$" + costo_promedio.toLocaleString() + "</td>";
            html += "<td>$" + ganancia_promedio.toLocaleString() + "</td></tr>";
            html += "</tbody></table></div></div>";
            domPromedioDia.html(html);
            if (!porPlatillo) {
                DOM_header2.html("Top Platillos por cantidad");
                obtenerTop5(dateFrom, dateTo).done(function (data) {
                    var plot_data2 = [];

                    var values = JSON.parse(data);
                     len = values.length;  i = 0;
                     for (; i < len; i++) {
                         var v = values[i];
                        plot_data2.push({
                            label:v.nombre,
                            value: v.cantidad 
                        });
                     }
                     DOM_donut2.html("");
                     Morris.Donut({
                         element: 'donut2',
                         data: plot_data2,
                         resize: true
                     });

                });
           
            }
            else {
                DOM_header2.html("Top Meses mejor vendidos");

                obtenerTopMeses(dateFrom, dateTo).done(function (data) {
                    var plot_data2 = [];
                    var meses = ['',"Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
                    var values = JSON.parse(data);
                    console.log(values);
                    len = values.length; i = 0;
                    for (; i < len; i++) {
                        var v = values[i];
                        plot_data2.push({
                            label: meses[v.mes]+" Top platillo "+v.nombre,
                            value: v.cantidad
                        });
                    }
                    DOM_donut2.html("");
                    Morris.Donut({
                        element: 'donut2',
                        data: plot_data2,
                        resize: true
                    });

                });
            }

        });



    }
    Date.prototype.addDays = function (days) {
        var dat = new Date(this.valueOf())
        dat.setDate(dat.getDate() + days);
        return dat;
    }

    function getDates(startDate, stopDate) {

        var dateArray = [];
        var currentDate = moment(startDate);
        while (currentDate <= stopDate) {
            dateArray.push(moment(currentDate).format('YYYY-MM-DD'))
            currentDate = moment(currentDate).add(1, 'days');
        }
        return dateArray;
    }



