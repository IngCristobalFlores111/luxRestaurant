function WaitingAnimation(modal) {
    var Waiting_Img = document.getElementsByClassName("CWaiting_Img");
    document.getElementById("BreakWaiting").innerHTML = "false";

    for (var i = 0; i < Waiting_Img.length; i++) {
        if (Waiting_Img[i].alt == "modal" && modal == "true") {
            Waiting_Img[i].style.zIndex = 100;
            Waiting_Img[i].style.opacity = 1;
            Waiting_Img[i].style.position = "absolute";
            Waiting_Img[i].style.left = 350 + "px";
            Waiting_Img[i].style.top = 150 + "px";
        }
        else if (Waiting_Img[i].alt == "main" && modal == "false") {
            Waiting_Img[i].style.zIndex = 100;
            Waiting_Img[i].style.opacity = 1;
            Waiting_Img[i].style.position = "absolute";
            Waiting_Img[i].style.left = (parseInt(window.innerWidth) / 2 - parseInt(Waiting_Img[i].clientWidth) / 2) + "px";
            Waiting_Img[i].style.top = (parseInt(window.innerHeight) / 2 - parseInt(Waiting_Img[i].clientHeight) / 2) + "px";
        }

    }
    AnimateWaiting();
}

function AnimateWaiting() {
    var Buttons = document.getElementsByClassName("btn");
    var Forms = document.getElementsByClassName("form-control");
    var End = document.getElementById("BreakWaiting").innerHTML;
    var Waiting_Img = document.getElementsByClassName("CWaiting_Img");

    if (End == "true") {
        for (var a = 0; a < Waiting_Img.length; a++) {
            Waiting_Img[a].style.zIndex = -1;
            Waiting_Img[a].style.opacity = 0;
        }
        for (var i = 0; i < Buttons.length; i++) {
            Buttons[i].disabled = false;
        }
        for (var h = 0; h < Forms.length; h++) {
            Forms[h].disabled = false;
        }
        return;
    }

    for (var j = 0; j < Buttons.length; j++) {
        Buttons[j].disabled = true;
    }
    for (var k = 0; k < Forms.length; k++) {
        Forms[k].disabled = true;
    }
    setTimeout(AnimateWaiting, 100);
}

function EndWaitingAnimation() {
    document.getElementById("BreakWaiting").innerHTML = "true";
}

function dispatchedOrder(idPedido)
{
    $.post("PHP/pedidoDispatched.php", {

        id:idPedido


    },function(data,status)
    
    {
        WaitingAnimation("false");//Input params  bool := true[when function is being triggered by modal] || bool := false [when function is being triggered in main window] 
        $("#content").html(data);
        EndWaitingAnimation();
    }
    );



}
function updateFechaHeader()
{ 
    var meses = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    var diasSemana = new Array("Domingo", "Lunes", "Martes", "Mi&oacutercoles", "Jueves", "Viernes", "S&aacutebado");
    var f = new Date();
    var hours = f.getHours(); var minutes = f.getMinutes(); var seconds = f.getSeconds();
    if (hours < 10) hours="0"+hours; if (minutes < 10) minutes="0"+minutes; if (seconds < 10) seconds="0"+seconds;

    var fecha = diasSemana[f.getDay()] + " " + f.getDate() + " de " + meses[f.getMonth()] + " de " + f.getFullYear() + " " + hours+ ":" + minutes + ":" + seconds;
    $("#headerDate").html("<label class='glyphicon glyphicon-list'> Pedidos " + fecha + "</label>");

}
function LoadPedidos()
{
    $.post("PHP/getPlatillos.php",
  function (data, status) {
      $("#content").html(data);


  });

}
function UpdatePedidos()
{
    setInterval(LoadPedidos, 1000);
    setInterval(updateFechaHeader, 1000);

}
function showModal(id)
{
   // $("#modalHeadertxt").html("<span class=\"glyphicon glyphicon-cutlery\"></span> Comentario de " + platillo);
    //$("#comenttxt").html(comentario);
    //
    //getcomment.php
    $.post("PHP/getcomment.php",
   {
       idplatillo: id
   },
   function (data, status) {
       var d = data.split(":");
       var nombre = d[0];
       var comentario = d[1];
       $("#modalHeadertxt").html("<span class=\"glyphicon glyphicon-cutlery\"></span> Comentario de " + nombre);
       $("#comenttxt").html(comentario);
       $("#myModal").modal()


   });


}
function ShowImageModal(id)
{
    //       $("#myModal2").modal()


    $.post("PHP/getImageModal.php",
    {
        id:id
    },
    function (data, status) {

        var result = data.split(":");
        var nombre = result[0];
        var image = result[1];
        
        $("#modalImageHeader").html("<span class='glyphicon glyphicon-picture'></span> Imagen del platillo " + nombre);
        $("#imgModal").attr("src", "../images/" + image);
        $("#myModal2").modal()
    });
}