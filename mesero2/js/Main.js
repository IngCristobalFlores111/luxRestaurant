// Javascript source code
var Drag = false;
var Id = -1;
var edit = false;

var MobileDrag = false;
var MobileId = -1;

var FilterDrinks = false;

var g_Body = null;
var g_Left = null;
var g_Right = null;
var g_Toggle = null;
var g_ToggleMobile = null;
var g_ToggleState = 0;

var g_ToggleCarta = null;
var g_ContainerCarta = null;
var g_ToggleStateCarta = 0;
var g_PlatillosCarta = [];
var g_PlatillosNombreCarta = [];
var g_CartaPedidos = null;

var g_MesaBody = null;

var g_ToggleEnCocina = null;
var g_ToggleEnCocinaState = 0;
var g_TogglePorServir = null;
var g_TogglePorServirState = 0;
var g_EnCocinaContainer = null;
var g_PorServirContainer = null;

$(document).ready(function () {
    g_Body = document.getElementById("IndexBody");
    g_Left = document.getElementById("MesasContainer");
    g_Right = document.getElementById("PendingContainer");
    g_Toggle = document.getElementById("Toggle");
    g_ToggleMobile = document.getElementById("ToggleMobile");

    g_ToggleCarta = document.getElementById("ToggleCarta");
    g_ContainerCarta = document.getElementById("ListadoPlatillos");
    g_CartaPedidos = document.getElementById("CartaPedidos");


    g_MesaBody = document.getElementById("MesaBody");
    
    g_ToggleEnCocina = document.getElementById("ToggleEnCocina");//Pendiente
    g_TogglePorServir = document.getElementById("TogglePorServir");//Pendiente

    g_EnCocinaContainer = document.getElementById("Pendings_InProcess");
    g_PorServirContainer = document.getElementById("Pending_Ready");
})



document.ontouchmove = function (event) {
    if(MobileDrag || edit)
        event.preventDefault();
}

document.onmousemove = function (e) {
    if (Drag && Id > -1 && edit) {
        var img = document.getElementById("img" + Id);
        var leyend = document.getElementById("leyend" + Id);
        var leyendPed = document.getElementById("leyendPed" + Id);
        var imgPed = document.getElementById("imgPed" + Id);
        var Container = document.getElementById("MesasContainer");

        if (parseInt(e.pageY) - 70 > parseInt(Container.offsetTop) && parseInt(Container.clientWidth) - 120 > parseInt(e.pageX)
            && parseInt(Container.offsetTop) + parseInt(Container.clientHeight) - 120 > parseInt(e.pageY)) {
            img.style.position = "absolute";
            img.style.left = e.pageX + "px";
            img.style.top = e.pageY + "px";

            leyend.style.position = "absolute";

            if (Id > 9)
                leyend.style.left = (e.pageX + 39) + "px";

            else
                leyend.style.left = (e.pageX + 45) + "px";

            leyend.style.top = (e.pageY + 35) + "px";
            leyend.style.zIndex = img.style.zIndex + 1;

            imgPed.style.top = (e.pageY) + "px";
            imgPed.style.left = (e.pageX + 49) + "px";

            leyendPed.style.top = (e.pageY + 14) + "px";
            leyendPed.style.left = (e.pageX + 78) + "px";
        }
    }
}

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
            Waiting_Img[i].style.left = (parseInt(window.innerWidth) / 2 - parseInt(Waiting_Img[i].clientWidth)  / 2)  + "px";
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

function EndWaitingAnimation(){
    document.getElementById("BreakWaiting").innerHTML = "true";
}


function RefreshTime()//Se encarga de actualizar la hora
{
    var date = document.getElementById("Pending_Date");
    var fecha = Date();
    
    date.innerHTML = fecha.slice(0, fecha.indexOf("GMT"));

    var PendingContainer = document.getElementById("PendingContainer");
    var MesasContainer = document.getElementById("MesasContainer");



    setTimeout(RefreshTime, 4000);
}


function TrabarDestrabarMesas()
{
    var edit_button = document.getElementById("Edit_Button");
    if (!edit) {
        edit = true;
        edit_button.style.backgroundColor = "#558ed5";
    }
    else {
        edit = false;
        edit_button.style.backgroundColor = "#000000";
    }
}

function MoveMesa(id)
{
    if (Drag && edit) {
        Drag = false;
        var Mesa = document.getElementById("img"+id);
        
        $.post("php/SaveTablePosition.php", { Posx: Mesa.offsetLeft, Posy: Mesa.offsetTop, Id:id }, function (data, status) {
        });
        Id = -1;
    }
    else if (edit)
    {
        Drag = true;
        Id = id;
    }
}

function MoveMesaMobile(idmesa) { 
    var MesasContainer = document.getElementById("MesasContainer");

    $('#img' + idmesa).on('touchmove', function (event) {
        var touch = event.originalEvent.touches[0];

        if (parseInt(touch.pageX) + 120 < parseInt(MesasContainer.clientWidth)
            && parseInt(touch.pageY) - 60 > parseInt(MesasContainer.offsetTop)
            && (parseInt(MesasContainer.offsetTop) + parseInt(MesasContainer.clientHeight) - 120) > parseInt(touch.pageY)
            && edit) {//TESTING!!!!!!!!!!!!!!!!!

            $('#img' + idmesa).css({
                left: touch.pageX,
                top: touch.pageY
            });

            var temp_left = 0;
            var temp_top = parseInt(touch.pageY) + 35;
            if (idmesa > 9)
                temp_left = parseInt(touch.pageX) + 39;
            else
                temp_left = parseInt(touch.pageX) + 45;

            $('#leyend' + idmesa).css({
                left: temp_left,
                top: temp_top
            });

            temp_top =  touch.pageY + 14;
            temp_left = touch.pageX + 78;
            $('#leyendPed' + idmesa).css({
                left: temp_left,
                top: temp_top
            });
            
            temp_left = touch.pageX + 49;
            $('#imgPed' + idmesa).css({
                left: temp_left,
                top: touch.pageY
            });


        }
            return false;
        });
    
//    MobileId = idmesa;
//    var img = document.getElementById("img" + idmesa);
//    img.addEventListener('ontouchmove', MobileMove);
   // MobileDrag = true;
   
}

function CargarMesas()
{
    $.post("php/CargarMesas.php", {}, function (data, status) {
        obj = JSON.parse(data);

        var MesasQty = parseInt(obj.Mesas.length);
        var rows = Math.ceil(MesasQty/3.0);
           
        var str = "";
        for(var i = 0; i < rows; i++)
        {

            str = str + "<div class='container-fluid'><div class='row'>";
            for(var j = 0; j < 3; j++)
            {
                if(((i * 3) + j) >= MesasQty)
                {
                    str = str + "<div class='col-xs-4' style='margin-bottom:10px;'></div>";    
                    continue;
                }
                        
                var mesa_state = "desactivada"; //Desocupado
                var manita_state = "fa-thumbs-o-up";
                if(parseInt(obj.Mesas[i*3 + j]["ocupado"]))
                    mesa_state = "activada";  //Ocupado
              
                if(!obj.Mesas[i*3 + j]["cuenta"])
                    obj.Mesas[i*3 + j]["cuenta"] = 0;
                        
                if(obj.Mesas[i*3 + j]["cuenta"] > 0)
                    manita_state = "fa-hand-o-left";
       
                str = str + "<div onclick='ShowMesaInfo("+ obj.Mesas[i*3 + j]["idmesa"]+")' class='col-xs-4' style='margin-bottom:10px;'>"+
                '<div class="filler '+ mesa_state + ' mesa_style" id="mesa_' + obj.Mesas[i*3 + j]["idmesa"] + '">'+
                '<div style="float:right;">'+
                '<i class="fa fa-users" id="Comensales_' + obj.Mesas[i*3 + j]["idmesa"] + '">' + obj.Mesas[i*3 + j]["comensales"] + '</i>'+
                '</div>'+
                '<div class="filler" style="padding-bottom:10px;">'+
                '<p class="Bfont" style="float:left;">Mesa ' + obj.Mesas[i*3 + j]["idmesa"] +'</p>'+
                '</div>'+
                '<div class="filler" style="overflow:auto;">'+
                '<div class="Bfont mesa_left_container"><i class="fa fa-cutlery"></i> <span id="Pedidos_' + obj.Mesas[i*3 + j]["idmesa"] + '">' + obj.Mesas[i*3 + j]["cuenta"] + '</span></div>'+
                '<div class="Bfont mesa_right_container"><i class="fa ' + manita_state + ' fa-2x" id="Manita_' + obj.Mesas[i*3 + j]["idmesa"] + '"></i></div>'+
                '</div>'+
                '</div>'+
                "</div>";          
            }
            str = str + "</div></div>";
        }
        g_Left.innerHTML = "";
        g_Left.insertAdjacentHTML("beforeend", str);
    });
}

//AGREGAR MESA FUNCTIONALITY
function AgregarMesa()
{
    var id = document.getElementById("MesaId").value;
    if (id == "")
    {
        alert("No ingreso nada en el espacio " + "'Numero de Mesa'");
        return;
    }
    WaitingAnimation("false");
    $.post("php/AgregarMesa.php", { ID: id }, function (data, status) {
       
        if (data == "1")
        {
            g_Left.innerHTML = "";
            CargarMesas();
            CleanAgregarMesaModal();
            
        }
        else
            alert(data);

        EndWaitingAnimation();
    });
}

function AgregarMesaModal()
{
    $("#ModalAgregarMesa").modal('show');
}

function CleanAgregarMesaModal()
{
    $("#ModalAgregarMesa").modal('hide');
}
//AGREGAR MESA FUNCTIONALITY

//QUITAR MESA FUNCTIONALITY
function QuitarMesas()
{
    WaitingAnimation("true");
    $.post("php/CargarMesas.php", {}, function (data, status) {
        var obj = JSON.parse(data);
        
        var Checkedid=[];
        
        for(var i = 0; i < obj.Mesas.length; i++)
        {
            if (obj.Mesas[i]["ocupado"] > 0)
                continue;

            var CheckBox = document.getElementById("QuitarMesa_" + obj.Mesas[i]["idmesa"]);
            if (CheckBox.checked)
                Checkedid.push(CheckBox.value);
        }
       
        alert(Checkedid);
        document.getElementById("MesasContainer").innerHTML = "";

        //var filter = false;
        //for (var j = 0; j < obj.Mesas.length; j++)
        //{
        //    if (obj.Mesas[j]["ocupado"] > 0) {
        //        CargarMesasporId(obj.Mesas[j]["idmesa"], obj.Mesas[j]["posx"], obj.Mesas[j]["posy"], obj.Mesas[j]["ocupado"]);
        //        continue;
        //    }

        //    filter = false;
        //    for (k = 0; k < CheckedArr.length; k++)
        //    {
        //        if (parseInt(CheckedArr[k]) == parseInt(obj.Mesas[j]["idmesa"]))
        //            filter = true;
        //    }

        //    if (!filter) {
        //        CargarMesasporId(obj.Mesas[j]["idmesa"], obj.Mesas[j]["posx"], obj.Mesas[j]["posy"],obj.Mesas[j]["ocupado"]);
        //    }
        //}
        
        $.post("php/EliminarMesas.php", { Arr: Checkedid }, function (data, status) {

            CargarMesas();

            CleanQuitarMesaModal();
            $("#ModalQuitarMesa").modal("hide");
            EndWaitingAnimation();
        });
        
    });
}

function QuitarMesaModal()
{
    $("#ModalQuitarMesa").modal('show');
    
    var Modal_Body = document.getElementById("QuitarMesa_Body");
    
    $.post("php/CargarMesas.php", {}, function (data, status) {
        obj = JSON.parse(data);

        for (var i = 0; i < obj.Mesas.length; i++) {

            if (parseInt(obj.Mesas[i]["ocupado"]) > 0)
                continue;

            Modal_Body.insertAdjacentHTML('beforeend', "<div class='well well-sm'><div class='row'>\
                        <div class='col-xs-2'>\
                            <span class='badge'>Mesa "+ obj.Mesas[i]["idmesa"] + "</span>\
                        </div>\
                    <div id='QuitarMesa_CheckBoxes' class='col-xs-1'>\
                            <input type='checkbox' id='QuitarMesa_" + obj.Mesas[i]["idmesa"] + "' value='" + obj.Mesas[i]["idmesa"] + "'>\
                    </div>\
                       <div class='col-xs-10'>\
                       </div>\
            </div></div>");
        }
        Modal_Body.insertAdjacentHTML('beforeend', "<div class='row'><div class='col-xs-12'><button onclick='QuitarMesas()' class='btn btn-success'>Eliminar</button></div></div>");
    });
}

function CleanQuitarMesaModal()
{
    var Modal_Body = document.getElementById("QuitarMesa_Body");
    Modal_Body.innerHTML = "";

    $("#ModalQuitarMesa").modal('hide');
}
//QUITAR MESA FUNCTIONALITY

function RearrangeTables(){
    $.post("php/GetTables.php", {}, function (data, status) {
        var obj = JSON.parse(data);
        var MesasContainer = document.getElementById("MesasContainer");

        var RightLimit = MesasContainer.clientWidth;
        //var TopLimit = MesasContainer.offsetTop;
        var BottomLimit = parseInt(MesasContainer.offsetTop) + parseInt(MesasContainer.clientHeight);
        
        for (var i = 0; i < obj.Mesas.length; i++) {

            var Mesa = document.getElementById("img" + obj.Mesas[i]["idmesa"]);
            var Leyend = document.getElementById("leyend" + obj.Mesas[i]["idmesa"]);
            var LeyendPed = document.getElementById("leyendPed" + obj.Mesas[i]["idmesa"]);
            var ImgPed = document.getElementById("imgPed" + obj.Mesas[i]["idmesa"]);

            if ((parseInt(Mesa.offsetLeft) + 120) > parseInt(RightLimit)) {
                Mesa.style.left = (RightLimit - 120) + "px";
                LeyendPed.style.left = (RightLimit - 47) + "px";
                ImgPed.style.left = (RightLimit - 70) + "px";

                if(parseInt(obj.Mesas[i]["idmesa"]) > 9)
                    Leyend.style.left = (RightLimit - 81) + "px";
                
                else 
                    Leyend.style.left = (RightLimit - 75) + "px";
        
            }

            //if ((parseInt(Mesa.offsetTop) + 40) < parseInt(TopLimit)) {
              //  Mesa.style.top = (parseInt(TopLimit) + 40) + "px";
               // Leyend.style.top = (parseInt(TopLimit) + 75) + "px";
            //}

            if (parseInt(Mesa.offsetTop + 70) > parseInt(BottomLimit)){
                Mesa.style.top = (parseInt(BottomLimit) - 40) + "px";
                Leyend.style.top = (parseInt(BottomLimit) - 15) + "px";
                LeyendPed.style.top = (BottomLimit - 37) + "px";
                ImgPed.style.top = (BottomLimit - 40) + "px";

            }

           // $.post("php/SaveTablePosition.php", { Posx: Mesa.offsetLeft, Posy: Mesa.offsetTop, Id: obj.Mesas[i]["idmesa"] }, function (data, status) {
               
           // });

        }
    });
}

function ToggleEnCocina() {
    // 39%
    if (!parseInt(g_ToggleEnCocinaState)) {
        g_EnCocinaContainer.style.visibility = "hidden";
        g_EnCocinaContainer.style.height = "0%";
        g_PorServirContainer.style.height = "78%";
        g_ToggleEnCocina.className = "fa fa-angle-double-down fa-2x";
        g_ToggleEnCocinaState = 1;
    }
    else {
        if (parseInt(g_TogglePorServirState)) {
            g_EnCocinaContainer.style.visibility = "visible";
            g_PorServirContainer.style.height = "0%";
            g_EnCocinaContainer.style.height = "78%";
            g_ToggleEnCocina.className = "fa fa-angle-double-up fa-2x";
            g_ToggleEnCocinaState = 0;
        }
        else {
            g_EnCocinaContainer.style.visibility = "visible";
            g_EnCocinaContainer.style.height = "39%";
            g_PorServirContainer.style.height = "39%";
            g_ToggleEnCocina.className = "fa fa-angle-double-up fa-2x";
            g_ToggleEnCocinaState = 0;
        }
    }
}

function TogglePorServir() {
    // 39%
    if (!parseInt(g_TogglePorServirState)) {
        if (!parseInt(g_ToggleEnCocinaState)) {
            g_PorServirContainer.style.visibility = "hidden";
            g_PorServirContainer.style.height = "0%";
            g_EnCocinaContainer.style.height = "39%";
            g_TogglePorServir.className = "fa fa-angle-double-down fa-2x";
            g_TogglePorServirState = 1;
        }
        else {
            g_PorServirContainer.style.visibility = "hidden";
            g_PorServirContainer.style.height = "0%";
            g_EnCocinaContainer.style.height = "0%";
            g_TogglePorServir.className = "fa fa-angle-double-down fa-2x";
            g_TogglePorServirState = 1;
        }
        
    }
    else {
        if (parseInt(g_ToggleEnCocinaState)) {
            g_PorServirContainer.style.visibility = "visible";
            g_PorServirContainer.style.height = "78%";
            g_EnCocinaContainer.style.height = "0%";
            g_TogglePorServir.className = "fa fa-angle-double-up fa-2x";
            g_TogglePorServirState = 0;
        }
        else {
            g_PorServirContainer.style.visibility = "visible";
            g_PorServirContainer.style.height = "39%";
            g_EnCocinaContainer.style.height = "39%";
            g_TogglePorServir.className = "fa fa-angle-double-up fa-2x";
            g_TogglePorServirState = 0;
        }
    }
}

function Toogle() {
    if (!parseInt(g_ToggleState)) {
        g_Left.style.visibility = "hidden";
        g_Left.style.width = "0%";
        g_Right.style.width = "100%";
        g_Toggle.className = "fa fa-angle-double-right fa-2x visible-lg";
        g_ToggleState = 1;
    }
    else{
        g_Left.style.visibility = "visible";
        g_Left.style.width = "50%";
        g_Right.style.width = "50%";
        g_Toggle.className = "fa fa-angle-double-left fa-2x visible-lg";
        g_ToggleState = 0;
    }
}

function ToggleMobile() {
    if (!parseInt(g_ToggleState)) {
        g_Left.style.visibility = "hidden";
        g_Left.style.height = "0%";
        g_ToggleMobile.className = "fa fa-angle-double-down fa-2x hidden-lg";
        g_ToggleState = 1;
    }
    else {
        g_Left.style.visibility = "visible";
        g_Left.style.height = "auto";
        g_ToggleMobile.className = "fa fa-angle-double-up fa-2x hidden-lg";
        g_ToggleState = 0;
    }
}

function ToggleCarta() {
    
    if (!parseInt(g_ToggleStateCarta)) {
        g_ContainerCarta.style.visibility = "hidden";
        g_ContainerCarta.style.height = "0px";
        g_ToggleCarta.className = "fa fa-angle-double-down";
        g_ToggleStateCarta = 1;
    }
    else {
        g_ContainerCarta.style.visibility = "visible";
        g_ContainerCarta.style.height = "auto";
        g_ToggleCarta.className = "fa fa-angle-double-up";
        g_ToggleStateCarta = 0;
    }
}

function ResizeofInlineContainers() { //Función para cambiar contenedores principales de Desktop a Mobile o viceversa!!!!!!!!!!!!!!!!!!!!!CHANGE!!!!!
    if(parseInt(g_Body.clientWidth) < 1199){
        //Mobile
        g_Body.style.overflowY = "scroll";
        g_Left.style.cssFloat = "none";
        g_Left.style.width = "100%";
        g_Left.style.height = "auto";
        g_Left.style.overflowY = "hidden";
        g_Left.style.marginBottom = "30px";
        g_Right.style.cssFloat = "none";
        g_Right.style.height = "400px";
        g_Right.style.width = "100%";
    }
    else{
        //Desktop
        g_Body.style.overflowY = "hidden";
        g_Left.style.cssFloat = "left";
        g_Left.style.width = "50%";
        g_Left.style.height = "85%";
        g_Left.style.overflowY = "scroll";
        g_Left.style.marginBottom = "0px";
        g_Right.style.cssFloat = "right";
        g_Right.style.height = "85%";
        g_Right.style.width = "50%";
        
    }

    //var nav = document.getElementById("Mesas_NavBar");
    //var mesas_container = document.getElementById("MesasContainer");
    //var pending_container = document.getElementById("PendingContainer");

    // mesas_container.style.height = (parseInt(window.innerHeight) - parseInt(nav.clientHeight) - 40) + "px";
    // pending_container.style.height = (parseInt(window.innerHeight) - parseInt(nav.clientHeight) - 40) + "px";;
}

function CheckPedidosPendientes() {
    $.post("php/CheckPedidosPendientes.php", {}, function (data, status) {
        
        data = data.split("/;/");

        var obj2 = JSON.parse(data[1]);
        var obj = JSON.parse(data[0]);

        if (obj2.Todas.length == 0 ) 
            return;
        
        for (var j = 0; j < obj2.Todas.length; j++) {    
                $("#imgPed" + obj2.Todas[j]["idmesa"]).hide();
                $("#leyendPed" + obj2.Todas[j]["idmesa"]).hide();

                if (obj2.Todas[j]["ocupado"].trim() == '0')
                    $("#img" + obj2.Todas[j]["idmesa"]).attr("src", "../images/inactivo.png");
                if (obj2.Todas[j]["ocupado"].trim() == '1')
                    $("#img" + obj2.Todas[j]["idmesa"]).attr("src", "../images/mesa.png");

            }
        
            if (obj.Mesas.length == 0)
                return;

            for (var i = 0; i < obj.Mesas.length; i++) {
            document.getElementById("leyendPed" + obj.Mesas[i]["idmesa"]).innerHTML = obj.Mesas[i]["accum"];
            $("#imgPed" + obj.Mesas[i]["idmesa"]).show();
            $("#leyendPed" + obj.Mesas[i]["idmesa"]).show();
            }
    });
}

function RefreshPendings() { //Bomba de mensajes que se encarga de actualizar todo lo referente a mesas y pedidos pendientes
    
    if (!FilterDrinks)
        LoadPendings();
    else
        FiltroBebidas();

    // CheckPedidosPendientes(); !!!CHANGE!!!
    
    CargarMesas();
    setTimeout(RefreshPendings, 4000);
}

function ActivarDesactivarFiltroBedidas() {
    if (!FilterDrinks) {
        document.getElementById("Boton_FiltroBebidas").className = "btn btn-danger";
        FiltroBebidas();
        FilterDrinks = true;
    }
    else {
        document.getElementById("Boton_FiltroBebidas").className = "btn btn-primary";
        LoadPendings();
        FilterDrinks = false;
    }
}

function FiltroBebidas() {
    $.post("php/FilterDrinks.php", {}, function (data, status) {

        document.getElementById("Pendings_InProcess").innerHTML = "";
        var Container = document.getElementById("Pending_Ready");
        Container.innerHTML = "";

        var obj = JSON.parse(data);
        for (var i = 0; i < obj.Bebidas.length; i++) {
            var Difference = parseInt((new Date).getTime() / 1000) - parseInt(obj.Bebidas[i]["horallegada"]);

            var Horas = parseInt(Difference / 3600);
            var Minutos = parseInt(((Difference / 3600) - Horas) * 60);
            var Segundos = parseInt(((((Difference / 3600) - Horas) * 60) - Minutos) * 60);

            Container.insertAdjacentHTML('beforeend', '\
                    <div class="well Boton_Parthenon">\
                             <div class="row">\
                                    <div class="col-xs-12">\
                                        <span class="badge">Mesa '+ obj.Bebidas[i]["idmesa"] + '</span>\
                                    </div>\
                             </div>\
                            <div class="row">\
                                <div class="col-xs-5">\
                                    <p>' + obj.Bebidas[i]["nomplatillo"] + '</p>\
                                </div>\
                                <div class="col-xs-7">\
                                    <p>' + obj.Bebidas[i]["comentario"] + '</p>\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-xs-7">\
                                    <button onclick="ServidoFnc(' + obj.Bebidas[i]["idpedido"] + ')" class="btn btn-block Boton_Parthenon">Servido</button>\
                                </div>\
                                <div id="Pending_1" class="col-xs-5">\
                                   ' + Horas + ' :  ' + Minutos + ' : ' + Segundos + '\
                                </div>\
                            </div>\
                        </div>\
                ');
        }
    });
}


function FiltrarPedidosPendientes()
{
    //Si esta activo, descactivar el Filtro por Bebidas
    if (FilterDrinks) {
        document.getElementById("Boton_FiltroBebidas").className = "btn btn-block btn-primary";
        FilterDrinks = false;
    }

    var Input = document.getElementById("NumeroMesa_PedidosPendientes");

    var b_all = false;
    if (isNaN(parseInt(Input.value))) 
        b_all = true;

    var NumMesa = Input.value;
    if (parseInt(NumMesa) < 0)
        Input.value = 0;
    if (parseInt(NumMesa) > 999)
        Input.value = 999;

    $.post("php/FiltrarPedidosPendientes.php", { NumMesa: parseInt(Input.value), Despachado: 0, All: b_all }, function (data, status) {

        var obj = JSON.parse(data);
        
        var PendingsInProcess = document.getElementById("Pendings_InProcess");
        PendingsInProcess.innerHTML = "";
        for (var i = 0; i < obj.Pedidos.length; i++) {
            var Difference = parseInt((new Date).getTime() / 1000) - parseInt(obj.Pedidos[i]["horallegada"]);

            var Horas = parseInt(Difference / 3600);
            var Minutos = parseInt(((Difference / 3600) - Horas) * 60);
            var Segundos = parseInt(((((Difference / 3600) - Horas) * 60) - Minutos) * 60);

            PendingsInProcess.insertAdjacentHTML('beforeend', '\
                        <div class="well  Boton_Parthenon">\
                            <div class="row">\
                                    <div class="col-xs-12 Align_Center">\
                                        <span class="badge">Mesa '+ obj.Pedidos[i]["idmesa"] + '</span>\
                                    </div>\
                            </div>\
                            <div class="row Light_Padding">\
                                <div class="col-xs-5">\
                                    <p>'+ obj.Pedidos[i]["nomplatillo"] + '</p>\
                                </div>\
                                <div class="col-xs-7">\
                                    <p>'+ obj.Pedidos[i]["comentario"] + '</p>\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-xs-12 Align_Center">\
                                    '+ Horas + ' :  ' + Minutos + ' : ' + Segundos + '\
                                </div>\
                            </div>\
                        </div>\
                ');
        }
    });

    $.post("php/FiltrarPedidosPendientes.php", { NumMesa: parseInt(Input.value), Despachado: 1, All:b_all  }, function (data, status) {
        document.getElementById("")
        var obj2 = JSON.parse(data);

        var ReadyContainer = document.getElementById("Pending_Ready");
        ReadyContainer.innerHTML = "";
        for (var j = 0; j < obj2.Pedidos.length; j++) {
            var Difference = parseInt((new Date).getTime() / 1000) - parseInt(obj2.Pedidos[j]["horallegada"]);

            var Horas = parseInt(Difference / 3600);
            var Minutos = parseInt(((Difference / 3600) - Horas) * 60);
            var Segundos = parseInt(((((Difference / 3600) - Horas) * 60) - Minutos) * 60);

            ReadyContainer.insertAdjacentHTML('beforeend', '\
                    <div class="well Boton_Parthenon">\
                             <div class="row">\
                                    <div class="col-xs-12">\
                                        <span class="badge">Mesa '+ obj2.Pedidos[j]["idmesa"] + '</span>\
                                    </div>\
                             </div>\
                            <div class="row">\
                                <div class="col-xs-5">\
                                    <p>'+ obj2.Pedidos[j]["nomplatillo"] + '</p>\
                                </div>\
                                <div class="col-xs-7">\
                                    <p>'+ obj2.Pedidos[j]["comentario"] + '</p>\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-xs-7">\
                                    <button onclick="ServidoFnc(' + obj2.Pedidos[j]["idpedido"] + ')" class="btn btn-block Boton_Parthenon">Servido</button>\
                                </div>\
                                <div id="Pending_1" class="col-xs-5">\
                                   ' + Horas + ' :  ' + Minutos + ' : ' + Segundos + '\
                                </div>\
                            </div>\
                        </div>\
                ');
        }
        
    });

}

function LoadPendings() {
    //Cargar Pedidos en cocina y listos para servir en sus respectivos contenedores y tambien hay que calcular 
    //el timpo que llevan existiendo desde el momento en que fueron generados
    var Input = document.getElementById("NumeroMesa_PedidosPendientes");
    var b_all = false;
    if (isNaN(parseInt(Input.value)))
        b_all = true;

    $.post("php/LoadPendings.php", { NumMesa: parseInt(Input.value), All: b_all }, function (data, status) {
        data = data.split("/;/");

        var obj = JSON.parse(data[0]);
        var PendingsInProcess = document.getElementById("Pendings_InProcess");
        PendingsInProcess.innerHTML = "";
        for(var i = 0; i < obj.Pedidos.length; i++)
        {
            var Difference = parseInt((new Date).getTime() / 1000) - parseInt(obj.Pedidos[i]["horallegada"]);

            var Horas = parseInt(Difference / 3600);
            var Minutos = parseInt(((Difference / 3600) - Horas) * 60);
            var Segundos = parseInt(((((Difference / 3600) - Horas) * 60) - Minutos) * 60);

            PendingsInProcess.insertAdjacentHTML('beforeend', '\
                        <div class="well  Boton_Parthenon">\
                            <div class="row">\
                                    <div class="col-xs-12 Align_Center">\
                                        <span class="badge">Mesa '+ obj.Pedidos[i]["idmesa"] +'</span>\
                                    </div>\
                            </div>\
                            <div class="row Light_Padding">\
                                <div class="col-xs-5">\
                                    <p>'+ obj.Pedidos[i]["nomplatillo"] +'</p>\
                                </div>\
                                <div class="col-xs-7">\
                                    <p>'+ obj.Pedidos[i]["comentario"] +'</p>\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-xs-12 Align_Center">\
                                    '+ Horas +' :  '+ Minutos +' : '+ Segundos +'\
                                </div>\
                            </div>\
                        </div>\
                ');
        }

        var obj2 = JSON.parse(data[1]);
        var ReadyContainer = document.getElementById("Pending_Ready");
        ReadyContainer.innerHTML = "";
        for(var j=0; j < obj2.Pedidos.length; j++)
        {
            var Difference = parseInt((new Date).getTime() / 1000) - parseInt(obj2.Pedidos[j]["horallegada"]);

            var Horas = parseInt(Difference / 3600);
            var Minutos = parseInt(((Difference / 3600) - Horas) * 60);
            var Segundos = parseInt(((((Difference / 3600) - Horas) * 60) - Minutos) * 60);

            ReadyContainer.insertAdjacentHTML('beforeend','\
                    <div class="well Boton_Parthenon">\
                             <div class="row">\
                                    <div class="col-xs-12">\
                                        <span class="badge">Mesa '+ obj2.Pedidos[j]["idmesa"] +'</span>\
                                    </div>\
                             </div>\
                            <div class="row">\
                                <div class="col-xs-5">\
                                    <p>'+ obj2.Pedidos[j]["nomplatillo"] +'</p>\
                                </div>\
                                <div class="col-xs-7">\
                                    <p>'+ obj2.Pedidos[j]["comentario"] +'</p>\
                                </div>\
                            </div>\
                            <div class="row">\
                                <div class="col-xs-7">\
                                    <button onclick="ServidoFnc(' + obj2.Pedidos[j]["idpedido"] + ')" class="btn btn-block Boton_Parthenon">Servido</button>\
                                </div>\
                                <div id="Pending_1" class="col-xs-5">\
                                   ' + Horas + ' :  ' + Minutos + ' : ' + Segundos + '\
                                </div>\
                            </div>\
                        </div>\
                ');
        }
    });

}

function ServidoFnc(idpedido) { //Pendinte
    WaitingAnimation("false");
    $.post("php/ServidoFnc.php", { IdPedido: idpedido }, function (data, status) {
        RefreshPendings();
        EndWaitingAnimation();
    });
}

function CloseCajaModal() {
    $("#Modal_Caja").modal('hide');
}

function FromVinculoPedido2Caja(idmesa) {
    $.post("php/CleanEmptyCuentas.php", { IdMesa: idmesa }, function (data, status) {
        $("#Modal_VinculoPedidoCuenta").modal('hide');
        setTimeout(OpenCajaModal, 1000, idmesa);
    });
}

function OpenCajaModal(idmesa) {
    $("#Modal_Caja").modal('show');
    $.post("php/GetPedidosByCuenta.php", { IdMesa: idmesa }, function (data, status) {
        var obj = JSON.parse(data);
        var Tabs        =   document.getElementById("CajaModal_Tabs");
        var TabsContent =   document.getElementById("CajaModal_TabsContent");
        Tabs.innerHTML = "";
        TabsContent.innerHTML = "";

        var idcuenta = "";
        var total = 0;

        for(var i = 0; i < obj.Pedidos.length; i++)
        {
            if (parseInt(idcuenta) != parseInt(obj.Pedidos[i]["idcuenta"]))
            {
                if (i > 0)
                {
                    document.getElementById("ModalCaja_Total" + idcuenta).innerHTML = "Total: $" + total.toFixed(2);
                    document.getElementById("ModalCaja_IVA" + idcuenta).innerHTML = "IVA: $" + (total - (total/1.16)).toFixed(2);
                    document.getElementById("ModalCaja_Sub" + idcuenta).innerHTML = "Subtotal: $" + (total/0.16).toFixed(2);
                }

                idcuenta = obj.Pedidos[i]["idcuenta"];
                total = parseFloat(obj.Pedidos[i]["precio"]);

                var tabstate = "";
                var tabcontentstate = "";
                if(parseInt(i) == 0)
                {
                    tabstate = "active Boton_Parthenon";
                    tabcontentstate = 'tab-pane fade in active Boton_Parthenon';
                }

                else
                {
                    tabstate = 'Boton_Parthenon';
                    tabcontentstate = 'tab-pane Boton_Parthenon';
                }

                Tabs.insertAdjacentHTML('beforeend', '\
                            <li class="' + tabstate + '">\
                                <a data-toggle="tab" href="#cuenta'+ obj.Pedidos[i]["idcuenta"] + '">Cuenta' + obj.Pedidos[i]["idcuenta"] + '</a>\
                            </li>\
                                           ');
                
                    TabsContent.insertAdjacentHTML('beforeend', '\
                            <div id="cuenta'+ obj.Pedidos[i]["idcuenta"] +'" class="' + tabcontentstate + '">\
                                <div class="row">\
                                    <div class="col-xs-12 Align_Center">\
                                        <h4>Pedidos</h4>\
                                    </div>\
                                </div>\
                                    \
                                <div id="CajaModal_PedidosContainer' + obj.Pedidos[i]["idcuenta"] + '" class="well Boton_Parthenon">\
                                    <div class="row">\
                                        <div class="col-sm-4">\
                                            '+ obj.Pedidos[i]["nomplatillo"] +'\
                                        </div>\
                                        <div class="col-sm-5">\
                                            '+ obj.Pedidos[i]["comentario"] +'\
                                        </div>\
                                        <div class="col-sm-1">\
                                            $'+ obj.Pedidos[i]["precio"] +'\
                                        </div>\
                                    </div><hr />\
                                </div>\
                                \
                                <div class="row">\
                                    <div class="col-xs-12 Align_Center">\
                                        <h4>Datos de Pago</h4>\
                                    </div>\
                                </div>\
                                <div class="well Boton_Parthenon"> \
                                    <div class="row">\
                                        <div class="col-sm-4">\
                                            <p id="ModalCaja_Sub' + obj.Pedidos[i]["idcuenta"] + '" style="color:yellowgreen;">Subtotal: $</p>\
                                        </div>\
                                   \
                                        <div class="col-sm-4">\
                                            <p id="ModalCaja_IVA' + obj.Pedidos[i]["idcuenta"] + '" style="color:yellowgreen;">IVA: $</p>\
                                        </div>\
                                    \
                                        <div class="col-sm-4">\
                                            <p id="ModalCaja_Total' + obj.Pedidos[i]["idcuenta"] + '" style="color:yellowgreen;">Total: $</p>\
                                        </div>\
                                    </div>\
                                    \
                                    <div class="row TopPadding">\
                                        <div class="col-xs-12 Align_Center">\
                                            <label class="badge Boton_Parthenon">Metodo de Pago</label>\
                                        </div>\
                                    </div>\
                                    <div class="row">\
                                        <div class="col-xs-12">\
                                            <select class="form-control">\
                                                <option>En efectivo</option>\
                                                <option>Tarjeta de credito</option>\
                                            </select>\
                                        </div>\
                                    </div>\
                                        \
                                    <div class="row TopPadding">\
                                        <div class="col-xs-12 Align_Center ">\
                                            <label class="badge Boton_Parthenon">Su pago</label>\
                                        </div>\
                                    </div>\
                                    <div class="row">\
                                        <div class="col-xs-12">\
                                            <input onkeyup="CalcularCambio('+ obj.Pedidos[i]["idcuenta"] +')" id="ModalCaja_SuPago'+ obj.Pedidos[i]["idcuenta"] +'" class="form-control" type="number" value="0"/>\
                                        </div>\
                                    </div>\
                                          \
                                    <div class="row">\
                                        <div class="col-xs-12 Align_Center TopPadding">\
                                            <label class="badge Boton_Parthenon">Descuento</label>\
                                        </div>\
                                    </div>\
                                    <div class="row">\
                                     <div class="col-xs-12">\
                                        <select onchange="CalcularCambio('+ obj.Pedidos[i]["idcuenta"] +')" id="ModalCaja_Descuento'+ obj.Pedidos[i]["idcuenta"] +'" class="form-control">\
                                            <option value="0">0%</option>\
                                            <option value="1">1%</option>\
                                            <option value="2">2%</option>\
                                            <option value="3">3%</option>\
                                            <option value="4">4%</option>\
                                            <option value="5">5%</option>\
                                        </select>\
                                      </div>\
                                    </div>\
                                    <div class="row TopPadding">\
                                        <div class="col-xs-12">\
                                           <label id="ModalCaja_SuCambio' + obj.Pedidos[i]["idcuenta"] + '" class="badge Boton_Parthenon">Su cambio $</label>\
                                        </div>\
                                    </div>\
                                          \
                                <div class="row TopPadding" id="BotonCobrar_'+ obj.Pedidos[i]["idcuenta"] +'" hidden>\
                                    <div class="col-xs-12">\
                                        <button onclick="Imprimir('+ obj.Pedidos[i]["idcuenta"] + ',' + idmesa + ')" class="btn btn-block Boton_Parthenon">Imprimir Nota de Venta</button>\
                                    </div>\
                                </div>\
                                      \
                                </div>\
                    ');

                    if (parseInt(i) == (parseInt(obj.Pedidos.length) - 1)) {
                        document.getElementById("ModalCaja_Total" + idcuenta).innerHTML = "Total: $" + total.toFixed(2);
                        document.getElementById("ModalCaja_IVA" + idcuenta).innerHTML = "IVA: $" + (total - (total/1.16)).toFixed(2);
                        document.getElementById("ModalCaja_Sub" + idcuenta).innerHTML = "Subtotal: $" + (total/1.16).toFixed(2);
                    }
            }
            else
            {
                total = parseFloat(total) + parseFloat(obj.Pedidos[i]["precio"]);
                var PedidosContainer = document.getElementById("CajaModal_PedidosContainer" + obj.Pedidos[i]["idcuenta"]);
                PedidosContainer.insertAdjacentHTML('beforeend', '\
                                    <div class="row">\
                                        <div class="col-sm-4">\
                                            '+ obj.Pedidos[i]["nomplatillo"] +'\
                                        </div>\
                                        <div class="col-sm-5">\
                                            '+ obj.Pedidos[i]["comentario"] +'\
                                        </div>\
                                        <div class="col-sm-1">\
                                            $'+ obj.Pedidos[i]["precio"] +'\
                                        </div>\
                                    </div><hr />\
                    ');
                if (parseInt(i) == (parseInt(obj.Pedidos.length) - 1)) {
                    document.getElementById("ModalCaja_Total" + idcuenta).innerHTML = "Total: $" + total.toFixed(2);
                    document.getElementById("ModalCaja_IVA" + idcuenta).innerHTML = "IVA: $" + (total - (total/1.16)).toFixed(2);
                    document.getElementById("ModalCaja_Sub" + idcuenta).innerHTML = "Subtotal: $" + (total/1.16).toFixed(2);
                }
            }
        }
    });
}

function Imprimir(idcuenta, idmesa) {
    var descuento = parseFloat(document.getElementById("ModalCaja_Descuento"+idcuenta).value);

    if (isNaN(descuento))
        descuento = parseFloat(0.0);
    else
        descuento = descuento / 100;
    
    WaitingAnimation("true");
    $.post("php/InsertVentaPlatillo.php", { IdCuenta: idcuenta, Descuento: descuento, IdMesa: idmesa }, function (data, status) {
        var Tabs = document.getElementById("CajaModal_Tabs");
        var TabsContent = document.getElementById("CajaModal_TabsContent");
        
  //      obj.Nota[0]["Total"];
        //        obj.Nota[0]["Id"];

        var obj = JSON.parse(data);


        if (parseInt(obj.Nota[0]["Cuenta"]) > 0) {
            LoadPedidos(idmesa);
            var Tabs = document.getElementById("CajaModal_Tabs");
            var TabsContent = document.getElementById("CajaModal_TabsContent");
            Tabs.innerHTML = "";
            TabsContent = "";
            EndWaitingAnimation();
            OpenCajaModal(idmesa);
        }
        else {
            Tabs.innerHTML = "";
            TabsContent = "";

            document.getElementById("PedidosContainer").innerHTML = "";
            LoadPedidos(idmesa);
            Mesa_Hide(idmesa);
            EndWaitingAnimation();
            $("#Modal_Caja").modal('hide');
        }
        
        if(confirm("¿Desea facturar esta cuenta?"))
            window.open("../administrador/facturar.html?total=" + obj.Nota[0]["Total"] + "&nota_id=" + obj.Nota[0]["Id"]);

    });
}

function CalcularCambio(idcuenta) {
    $.post("php/GetCuentaById.php", { IdCuenta: idcuenta }, function (data, status) {
        var obj = JSON.parse(data);
        var Total = parseFloat(obj.Cuenta[0]["total"]);
        var SuPago = parseFloat(document.getElementById("ModalCaja_SuPago" + idcuenta).value);
        var Descuento = (parseFloat(document.getElementById("ModalCaja_Descuento" + idcuenta).value) / 100);
        if (isNaN(SuPago))
            SuPago = 0;

        var Cambio = 0;
      
            var SubTotal = parseFloat(Total) / 1.16;

            var Descuento_Pesos = SubTotal * parseFloat(Descuento);
            var Subtotal_Descuento = SubTotal - parseFloat(Descuento_Pesos);
            var NewIVA = parseFloat(Subtotal_Descuento) * 0.16;
            var NewTotal = Subtotal_Descuento + parseFloat(NewIVA);

            document.getElementById("ModalCaja_Sub" + idcuenta).innerHTML = "Subtotal: $" + Subtotal_Descuento.toFixed(2);
            document.getElementById("ModalCaja_IVA" + idcuenta).innerHTML = "IVA: $" + NewIVA.toFixed(2);
            document.getElementById("ModalCaja_Total" + idcuenta).innerHTML = "Total: $" + NewTotal.toFixed(2);

            Total = NewTotal;
        

        Cambio = (SuPago - Total).toFixed(2);
        if (Cambio < 0) {
            Cambio = 0;
            $("#BotonCobrar_" + idcuenta).hide(500);
        }
        else
            $("#BotonCobrar_" + idcuenta).show(500);


        document.getElementById("ModalCaja_SuCambio" + idcuenta).innerHTML = "Cambio: $" + Cambio;
    });
}

function Cobrar(idmesa) {
    $.post("php/Cobrar.php", { IdMesa: idmesa }, function (data, status) {

        switch(parseInt(data))
        {
            case 0:
                $("#Modal_Cobrar").show(700);
                break;
            case 1:
                $("#Modal_Cobrar").hide(700);
                break;
            case 2:
                $("#Modal_Cobrar").hide(700);
                break;
            default:
                break;
        }
        
    });
}

//MESA HTML FUNCTIONALITY
function ShowMesaInfo(id) {   
        location.replace("Mesa.html?idmesa=" + id);
}

function GetMesaState(id) {
    $.post("php/GetMesaState.php", { idmesa: id }, function (data, status) {
        if (parseInt(data) == 1) {
            $(".HideShow").show();
            document.getElementById("HideShowButton_Container").innerHTML = '<button onclick="Mesa_Hide(' + id + ')" class="btn btn-block  Boton_Parthenon">Desactivar Mesa</button>';
            document.getElementById("PorMontoButton_Container").innerHTML = '<button onclick="PagarPorMonto(' + id + ')" class="btn btn-block    Boton_Parthenon">Por Monto</button>';
            document.getElementById("DividirCuentasButton_Container").innerHTML = '<button onclick="DividirCuentas(' + id + ')" class="btn btn-block  Boton_Parthenon">Dividir Cuentas</button>';
            document.getElementById("AgregarPlatilloButton_Container").innerHTML = '<button onclick="AgregarPlatillo(' + id + ')" class="btn btn-block  Boton_Parthenon"> + </button>';
         
        }

        else {
            document.getElementById("HideShowButton_Container").innerHTML = '<button onclick="Mesa_Show(' + id + ')" class="btn btn-block  Boton_Parthenon">Activar Mesa</button>';
            $(".HideShow").hide();
        }
    });
}

function AgregarCuenta(idmesa) {
    $.post("php/AgregarCuenta.php", {Mesa:idmesa}, function (data, status) {
        ShowCuentasContainer(idmesa);
    });
}

function DividirCuentas(id) {  //Pendiente...
    $("#Modal_VinculoPedidoCuenta").modal('show');
    ShowCuentasContainer(id);
    Cobrar(id);
}

function EliminarCuenta(idmesa, idcuenta)
{
    WaitingAnimation("true");
    //Para eliminar correctamente una cuenta, hay que eliminar todos los vículos entre esa cuenta y pedidos.
    $.post("php/EliminarCuenta.php", { Cuenta: idcuenta, Mesa: idmesa }, function (data, status) {
        ShowCuentasContainer(idmesa);
       
        if(parseInt(data) == 0)
            $("#Modal_Cobrar").hide(700);
        EndWaitingAnimation();
    });
}

function ShowCuentasContainer(idmesa) {
    $("#Pedidos_MainContainer").hide(700);
    $("#Modal_ReturnButton").hide(700);
    $("#Modal_AgregarCuentaButton").show(700);
    var CuentasContainer = document.getElementById("Cuentas_MainContainer");
    CuentasContainer.style.width = "100%";
    
    $.post("php/ShowCuentas.php", {Mesa:idmesa}, function (data, status) {
        var obj = JSON.parse(data);
        CuentasContainer.innerHTML = "";

        //Formato de cuenta compacto
        for (var i = 0; i < obj.Cuentas.length; i++) {
            CuentasContainer.insertAdjacentHTML('beforeend', '<div id="Moda_Cuenta_' + obj.Cuentas[i]["idcuenta"] + '" class="well Boton_Parthenon"> \
                                <div class="row"> \
                                    <div class="col-xs-2"> \
                                        <span class="badge Boton_Parthenon">' + obj.Cuentas[i]["idcuenta"] + '</span> \
                                    </div> \
                                    <div class="col-xs-2"> \
                                        Total: $'+ obj.Cuentas[i]["total"] +'\
                                    </div> \
                                    <div class="col-xs-8 Align_Right"> \
                                        <div class="btn-group"> \
                                            <button onclick="ShowCuentasAndPedidosContainer('+ idmesa +','+ obj.Cuentas[i]["idcuenta"] +')" class="btn btn-warning">Seleccionar</button> \
                                            <button onclick="EliminarCuenta('+idmesa +','+ obj.Cuentas[i]["idcuenta"] +')" class="btn btn-danger">Eliminar</button> \
                                        </div> \
                                    </div>\
                                </div>\
                            </div>');    
        }
    });
}

function ShowCuentasAndPedidosContainer(idmesa, idcuenta) {
    $("#Modal_Cobrar").hide(700);
    $("#Pedidos_MainContainer").show(700);
    $("#Modal_ReturnButton").show(700);
    $("#Modal_AgregarCuentaButton").hide(700);

    var CuentasContainer = document.getElementById("Cuentas_MainContainer");

    CuentasContainer.style.width = "50%";
    CuentasContainer.innerHTML = "";

    $.post("php/ShowCuentaEspecifica.php", { Mesa: idmesa, Cuenta: idcuenta }, function (data, status) {
        var Datasplit = data.split("/;/");

        var obj  = JSON.parse(Datasplit[0]);
        var obj2 = JSON.parse(Datasplit[1]);
        
        var Pedidos = "";
        
        for (var i = 0; i < obj2.Pedidos.length; i++)
        {
            Pedidos = Pedidos + '\
                                      <div class="well Boton_Parthenon">\
                                        <div class="row">\
                                            <div class="col-sm-2 Align_Center">\
                                                <span class="badge">' + obj2.Pedidos[i]['idpedido'] + '</span>\
                                            </div>\
                                            <div class="col-sm-4 Align_Center">\
                                                '+ obj2.Pedidos[i]['nomplatillo'] +'\
                                            </div>\
                                            <div class="col-sm-6 Align_Center">\
                                                '+ obj2.Pedidos[i]['comentario'] +'\
                                            </div>\
                                        </div>\
                                        <div class="row">\
                                            <div class="col-sm-6">\
                                                $'+ obj2.Pedidos[i]['precio'] +'\
                                            </div>\
                                            <div class="col-sm-6">\
                                                <button onclick="DesvincularCuentaPedido('+ idmesa +', '+ idcuenta +','+ obj2.Pedidos[i]['idpedido'] +')" class="btn btn-danger">X</button>\
                                            </div>\
                                        </div>\
                                    </div>';
        }

            var Injection = '\
                            <!--Cuenta extendida-->\
                            <div id="Modal_Cuenta_' + obj.Cuentas[0]["idcuenta"] + '" class="well Boton_Parthenon">\
                                <div class="row">\
                                    <div class="col-xs-12 Align_Center">\
                                        <h4> Cuenta '+ obj.Cuentas[0]["idcuenta"] +' </h4>\
                                    </div>\
                                </div>\
                                \
                                <div class="well Boton_Parthenon">\
                                    <div class="row">\
                                        <div class="col-xs-12 Align_Center Boton_Parthenon">\
                                            <h4> Pedidos Vinculados </h4>\
                                        </div>\
                                    </div>\
                                    \
                                    '+ Pedidos +'\
                                    \
                                </div>\
                            </div> \
                ';
            CuentasContainer.insertAdjacentHTML('beforeend', Injection);
    });

    $.post("php/LoadNonLinkedPedidos.php", {IdMesa:idmesa}, function (data, status) {
        var obj = JSON.parse(data);
        var PedidosContainer = document.getElementById("Modal_PedidosContainer");
        PedidosContainer.innerHTML = "";
        for(var i = 0; i < obj.Pedidos.length; i++)
        {
            PedidosContainer.insertAdjacentHTML('beforeend', ' \
                              <div class="well Boton_Parthenon">\
                                <div class="row"> \
                                    <div class="col-sm-2">\
                                        <span class="badge Boton_Parthenon"> ' + obj.Pedidos[i]["idpedido"] + ' </span>\
                                    </div>\
                                    <div class="col-sm-4">\
                                        '+ obj.Pedidos[i]["nomplatillo"] +'\
                                    </div>\
                                    <div class="col-sm-6" >\
                                        '+ obj.Pedidos[i]["comentario"] +'\
                                    </div>\
                                </div>\
                                        \
                                <div class="row">\
                                    <div class="col-xs-12" style="text-align:center;">\
                                        <button onclick="AgregarPedidoCuenta(' + idcuenta + ',' + obj.Pedidos[i]["idpedido"] + ',' + idmesa + ')" class="btn btn-block Boton_Parthenon"><i class="fa fa-plus"></i></button>\
                                    </div>\
                                </div>\
                            </div>');
        }
    });
}

function DesvincularCuentaPedido(idmesa,idcuenta,idpedido)
{
    WaitingAnimation("true");
    $.post("php/DesvincularCuentaPedido.php", { Pedido: idpedido }, function (data, status) {
        ShowCuentasAndPedidosContainer(idmesa, idcuenta);
        EndWaitingAnimation();
    });
}

function AgregarPedidoCuenta(idcuenta, idpedido, idmesa) {
    WaitingAnimation("true");
    $.post("php/AgregarPedidoCuenta.php", { Cuenta: idcuenta, Pedido: idpedido }, function (data, status) {
        ShowCuentasAndPedidosContainer(idmesa, idcuenta);
        EndWaitingAnimation();
    });
}

function ModalVinculo_Resize(){
    if (parseInt(g_MesaBody.clientWidth) < 1199) {
        //Mobile
        g_MesaBody.style.overflowY = "scroll";
    }
    else {
        //Desktop
        g_MesaBody.style.overflowY = "scroll";
    }

}

function CloseVinculoPedidoCuenta_Modal(){
    $("#Modal_VinculoPedidoCuenta").modal('hide');
}

function AgregarPlatillo(id) { //Pendiente
    location.replace("Carta.html?idmesa="+id);
}

function RegresarAMesa(id){
    location.replace("Mesa.html?idmesa=" + id);
}

function CloseAgregarPlatillo_Modal() {
    document.getElementById("Modal_AgregarPlatillo_PlatilloContainer").innerHTML = "";
    $("#Modal_AgregarPlatillo").modal("hide");
}

function RefreshTableTimes(table) { //Bomba de mensajes que recarga los tiempos en las mesas
    $.post("php/RefreshTableTimes.php", { Mesa: table }, function (data, status) {
        var obj = JSON.parse(data);
        for (var i = 0; i < obj.Tiempos.length; i++) {
            var Hora = document.getElementById("Pedidos_HoraLlegada_" + obj.Tiempos[i]["idpedido"]);

            if (Hora == "undefined")
                return;

            if (isNaN(Horas) || isNaN(Minutos) || isNaN(Segundos))
                return;

            var Difference = parseInt((new Date).getTime() / 1000) - parseInt(obj.Tiempos[i]["horallegada"]);

            var Horas = parseInt(Difference / 3600);
            var Minutos = parseInt(((Difference / 3600) - Horas) * 60);
            var Segundos = parseInt(((((Difference / 3600) - Horas) * 60) - Minutos) * 60);

             Hora.innerHTML = "Horas: " + Horas + " Minutos: " + Minutos + " Segundos: "+ Segundos;
        }
    });
    setTimeout(RefreshTableTimes, 4000,table);
}

function EditarPedido(idpedido, idmesa)
{
    $("#Modal_EditarPedido").modal("show");
    document.getElementById("ModalHidden_PedidoId").innerHTML = idpedido;
    document.getElementById("Modal_NuevoComentario").value = document.getElementById("Mesa_Comentarios_" + idpedido).value;
    
    $.post("php/GetPedidosById.php", { IdPedido: idpedido }, function (data, status) {
        var obj = JSON.parse(data);
        document.getElementById("Modal_NuevoCostoExtra").value = parseFloat(obj.Pedido[0]["preciopedido"]) - parseFloat(obj.Pedido[0]["precioplatillo"]);
    
        LoadPedidos(idmesa);
    });
    
}

function ModificarExtras(idmesa)
{
    var idpedido = document.getElementById("ModalHidden_PedidoId").innerHTML;
    var nuevocomentario = document.getElementById("Modal_NuevoComentario").value;
    var nuevoextra = document.getElementById("Modal_NuevoCostoExtra").value;

    if (nuevoextra == "")
        nuevoextra = 0;
    
    WaitingAnimation("true");
    $.post("php/ModificarExtras.php", { Pedido: idpedido, Comentario: nuevocomentario, Extra: nuevoextra }, function (data, status) {
        LoadPedidos(idmesa);
        EndWaitingAnimation();
    });
}

function QuitarExtras(idmesa)
{
    var idpedido = document.getElementById("ModalHidden_PedidoId").innerHTML;
    WaitingAnimation("true");
    $.post("php/QuitarExtras.php", { Pedido: idpedido }, function (data, status) {
        LoadPedidos(idmesa);
        EndWaitingAnimation();
    });
}

function EditarPedidoFromModal(id,idmesa)
{
    $.post("php/EditarPedido.php", { IdPedido: id, IdMesa: idmesa }, function (data, status) {
        alert(data);
    });
}

function EliminarPedido(idpedido, idmesa)
{
    WaitingAnimation("false");
    $.post("php/EliminarPedido.php", { IdPedido: idpedido, IdMesa: idmesa }, function (data, status) {
        switch(parseInt(data))
        {
            case 0:
                LoadPedidos(idmesa); //Platillos con más de 5 minutos
                break;

            case -1:
                alert("El platillo ya ha sido terminado en cocina");
                break;
            case -2:
                LoadPedidos(idmesa); //Bebidas
                break;

            default:
                LoadPedidos(idmesa); //Platillos con menos de 5 minutos
                break;
        }
        EndWaitingAnimation();
    });
}

function LoadPedidos(idmesa)
{
    $.post("php/LoadPedidos.php", { IdMesa: idmesa }, function (data, status) {
        var obj = JSON.parse(data);
        var PedidosContainer = document.getElementById("PedidosContainer");
        PedidosContainer.innerHTML = '';

        for (var i = 0; i < obj.Pedidos.length; i++) {
            PedidosContainer.insertAdjacentHTML('beforeend', '\
            <div class="well Boton_Parthenon">\
                    <div class="row">\
                        <div class="col-sm-1" style="text-align:center;">\
                            <span class="badge Boton_Parthenon">' + obj.Pedidos[i]["idpedido"] + '</span>\
                        </div>\
                        <div id="Mesa_NombrePedido_'+ obj.Pedidos[i]["idpedido"] + '" class="col-sm-2" style="text-align:center;">\
                         '+ obj.Pedidos[i]["nomplatillo"] + '\
                        </div>\
                        <div class="col-sm-5">\
                            <input readonly id="Mesa_Comentarios_' + obj.Pedidos[i]["idpedido"] + '" type="text" class="form-control" value="' + obj.Pedidos[i]["comentario"] + '"/>\
                        </div>\
                        <div class="col-sm-2">\
                            <button  onclick="EditarPedido(' + obj.Pedidos[i]["idpedido"] + ',' + idmesa + ')" class="btn btn-block Align_Center Boton_Parthenon"> Editar </button>\
                        </div>\
                        <div class="col-sm-2">\
                            <button onclick="EliminarPedido(' + obj.Pedidos[i]["idpedido"] + ',' + idmesa + ')" class="btn btn-block Align_Center Boton_Parthenon"> Eliminar </button>\
                        </div>\
                    </div>\
                        <div class="row">\
                            <div id="Pedidos_HoraLlegada_'+ obj.Pedidos[i]["idpedido"] +'" class="col-xs-12 Align_Center">\
                                \
                            </div>\
                        </div>\
                </div>\
            ');
        }
        if (obj.Pedidos.length == 0) {
            $("#DividirCuentasButton_Container").hide(700);
            $("#PorMontoButton_Container").hide(700);
        }
        
    });
    RefreshTableTimes(idmesa);
}


function CheckPedidosState(idmesa) { // Bomba de mensajes que checa si los pedidos fueron servidos y la mesa esta lista para pagar
    $.post("php/CheckPedidosState.php", { IdMesa: idmesa }, function (data, status) {
        if (parseInt(data) > -1) {
            $("#DividirCuentasButton_Container").show(700);
            $("#PorMontoButton_Container").show(700);
        }
        else {
            $("#DividirCuentasButton_Container").hide(700);
            $("#PorMontoButton_Container").hide(700);
        }
    });
    setTimeout(CheckPedidosState, 2000, idmesa);
}

function CrearPedido(idplatillo, idmesa) {
    var comentarios = document.getElementById("CargarPlatillos_Comentarios_" + idplatillo).value;
    var nombre = document.getElementById("MoreInfo_Nombre_" + idplatillo).innerText;
    var precio = document.getElementById("MoreInfo_Precio_" + idplatillo).innerText.trim();
    var precio_extra = parseFloat(document.getElementById("CargarPlatillos_CostoExtra_" + idplatillo).value);
    var cantidad_platillos = parseInt(document.getElementById("CargarPlatillos_Cantidad_" + idplatillo).value);

    if (isNaN(precio_extra))
        precio = parseFloat(precio.slice(precio.indexOf("$") + 1, precio.length));
    else
        precio = parseFloat(precio.slice(precio.indexOf("$") + 1, precio.length)) + precio_extra;

    WaitingAnimation("false");
    $.post("php/CrearPedido.php", { IdPlatillo: idplatillo, IdMesa: idmesa, Comentarios: comentarios, Nombre: nombre, Precio: precio, Cantidad_Platillos: cantidad_platillos }, function (data, status) {

        var index = g_PlatillosCarta[nombre];

        if (index == undefined) {
            g_PlatillosNombreCarta.push(nombre);
            g_PlatillosCarta[nombre] = cantidad_platillos;
        }
        else {
            g_PlatillosCarta[nombre] = parseInt(g_PlatillosCarta[nombre]) + cantidad_platillos;
        }

        g_CartaPedidos.innerHTML = "";
        for (var i = 0; i < g_PlatillosNombreCarta.length; i++) 
            g_CartaPedidos.insertAdjacentHTML('beforeend', "<li>" + g_PlatillosNombreCarta[i] + " X " + g_PlatillosCarta[g_PlatillosNombreCarta[i]] + "</li>")
            
        EndWaitingAnimation();
    });
}


function ShowMoreInfoPlatillo(id)
{
    if (!$("#MoreInfoPlatillo_" + id).is(":visible")) {
       
        if (document.getElementById("MoreInfoImg_" + id) == null) {
            var imgcont = document.getElementById("MoreInfo_ImageContainer_" + id);
            imgcont.innerHTML = '<img id="MoreInfoImg_' + id + '" class="img-responsive" alt="err" src="' + imgcont.innerHTML + '" style="margin-left:auto; margin-right:auto;"/>';
            $("#MoreInfoPlatillo_" + id).show(500);
        }
        else {
            $("#MoreInfoPlatillo_" + id).show(500);
        }
    }
    else {
        $("#MoreInfoPlatillo_" + id).hide(500);
    }
}

//Cargar Platillos en Modal Agregar Platillo
function CargarPlatillosPorCantidad(mesa) {
    var PlatillosContainer = document.getElementById("Modal_AgregarPlatillo_PlatilloContainer");
    $.post("php/CargarPlatillosPorCantidad.php", {}, function (data, status) {
        var obj = JSON.parse(data);
        for (var i = 0; i < obj.Platillos.length; i++) {
            PlatillosContainer.insertAdjacentHTML('beforeend', '\
                            <div class="well Boton_Parthenon"> \
                                    <div class="row Align_Center">\
                                        <div class="col-xs-12">\
                                             <span class="Boton_Parthenon badge" id="MoreInfo_Nombre_' + obj.Platillos[i]["idplatillo"] + '" class="badge Boton_Parthenon">' + obj.Platillos[i]["nombre"] + '</span>\
                                        </div>\
                                    </div>\
                                    \
                                    <div class="row Light_Padding">\
                                        <div class="col-xs-4">\
                                            <label>Cantidad</label><input class="form-control" min="1" max="10" onkeyup = "LimitQty(' + obj.Platillos[i]["idplatillo"] + ')" type="number" id="CargarPlatillos_Cantidad_' + obj.Platillos[i]["idplatillo"] + '" value="1" />\
                                        </div>\
                                        <div class="col-xs-4">\
                                            <label></label><button onclick="CrearPedido(' + obj.Platillos[i]["idplatillo"] + ',' + mesa + ')" class="btn btn-block Align_Center Boton_AgregarPedido"> + </button>\
                                        </div>\
                                        <div class="col-xs-4">\
                                            <label></label><button onclick="ShowMoreInfoPlatillo(' + obj.Platillos[i]["idplatillo"] + ')" class="btn btn-block Align_Center Boton_AgregarPedido"> ? </button>\
                                        </div>\
                                    </div>\
                                    <div class="row Light_Padding">\
                                        <div class="col-xs-4">\
                                            <label>Costo Extra</label><input class="form-control" type="number" onkeyup = "LimitPrice('+ obj.Platillos[i]["idplatillo"] +')" id="CargarPlatillos_CostoExtra_' + obj.Platillos[i]["idplatillo"] + '" />\
                                        </div>\
                                        <div class="col-xs-8">\
                                            <label>Comentarios</label><input class="form-control" id="CargarPlatillos_Comentarios_' + obj.Platillos[i]["idplatillo"] + '" type="text" class="form-control" />\
                                        </div>\
                                     </div>\
                                </div>\
                                <div class="well Light_Padding" id="MoreInfoPlatillo_'+ obj.Platillos[i]["idplatillo"] + '" hidden>\
                                    <div class="row">\
                                        <div id="MoreInfo_ImageContainer_' + obj.Platillos[i]["idplatillo"] + '" class="col-xs-12">\
                                           ../images/'+obj.Platillos[i]["imagepath"]+'\
                                        </div>\
                                    </div>\
                                    <div class="row">\
                                        <div id="MoreInfo_Description_' + obj.Platillos[i]["idplatillo"] + '" class="row-xs-12 Align_Center Black_Font">\
                                                '+ obj.Platillos[i]["descripcion"] + '\
                                       </div>\
                                    </div>\
                                    <div class="row">\
                                        <div id="MoreInfo_Precio_' + obj.Platillos[i]["idplatillo"] + '" class="row-xs-12 Align_Center Black_Font">\
                                                Precio: $'+ obj.Platillos[i]["precio"] + '\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>');
        }
    });
}

function Modal_LimitPrice() {
    var idpedido = document.getElementById("ModalHidden_PedidoId").innerHTML;
    
    var CostoContainer = document.getElementById("Modal_NuevoCostoExtra");

    if (isNaN(CostoContainer.value))
        CostoContainer.value = "1";

    else if (parseInt(CostoContainer.value) < 1)
        CostoContainer.value = "1";

    else if (parseInt(CostoContainer.value) > 1000)
        CostoContainer.value = "999";
}

function LimitPrice(idpedido) {
    
    var CostoContainer = document.getElementById("CargarPlatillos_CostoExtra_" + idpedido);

    if (isNaN(CostoContainer.value))
        CostoContainer.value = "1";

    else if (parseInt(CostoContainer.value) < 1)
        CostoContainer.value = "1";

    else if (parseInt(CostoContainer.value) > 1000)
        CostoContainer.value = "999";
}

function LimitQty(idpedido) {
    
    var CantidadContainer = document.getElementById("CargarPlatillos_Cantidad_" + idpedido);
    
    CantidadContainer.value = parseInt(CantidadContainer.value);

    if (isNaN(CantidadContainer.value)) 
        CantidadContainer.value="1";

    else if (parseInt(CantidadContainer.value) < 1) 
        CantidadContainer.value="1";
    

    else if (parseInt(CantidadContainer.value) > 10) 
        CantidadContainer.value = "10";
    
}

function CargarPlatillosFiltrados(mesa)
{
    var PlatillosContainer = document.getElementById("Modal_AgregarPlatillo_PlatilloContainer");
    PlatillosContainer.innerHTML = "";

    var search = document.getElementById("AgregarPlatillo_Busqueda");
    $.post("php/CargarPlatillosFiltrados.php", {wildcard:search.value}, function (data, status) {
        var obj = JSON.parse(data);
        for(var i = 0; i < obj.Platillos.length; i++)
        {
            PlatillosContainer.insertAdjacentHTML('beforeend', '\
                                       <div class="well Boton_Parthenon"> \
                                    <div class="row Align_Center">\
                                        <div class="col-xs-12">\
                                             <span id="MoreInfo_Nombre_' + obj.Platillos[i]["idplatillo"] + '" class="badge Boton_Parthenon">' + obj.Platillos[i]["nombre"] + '</span>\
                                        </div>\
                                    </div>\
                                    \
                                    <div class="row Light_Padding">\
                                        <div class="col-sm-4">\
                                            <label>Cantidad</label><input class="form-control" min="1" max="10" onkeyup = "LimitQty('+ obj.Platillos[i]["idplatillo"] + ')" type="number" id="CargarPlatillos_Cantidad_' + obj.Platillos[i]["idplatillo"] + '" value="1"/>\
                                        </div>\
                                        <div class="col-sm-4">\
                                            <label></label><button onclick="CrearPedido(' + obj.Platillos[i]["idplatillo"] + ',' + mesa + ')" class="btn btn-block Align_Center Boton_AgregarPedido"> + </button>\
                                        </div>\
                                        <div class="col-sm-4">\
                                            <label></label><button onclick="ShowMoreInfoPlatillo(' + obj.Platillos[i]["idplatillo"] + ')" class="btn btn-block Align_Center Boton_AgregarPedido"> ? </button>\
                                        </div>\
                                    </div>\
                                    <div class="row Light_Padding">\
                                        <div class="col-sm-4">\
                                            <label>Costo Extra</label><input class="form-control" type="number" onkeyup = "LimitPrice(' + obj.Platillos[i]["idplatillo"] + ')" id="CargarPlatillos_CostoExtra_' + obj.Platillos[i]["idplatillo"] + '" />\
                                        </div>\
                                        <div class="col-sm-8">\
                                            <label>Comentarios</label><input class="form-control" id="CargarPlatillos_Comentarios_' + obj.Platillos[i]["idplatillo"] + '" type="text" class="form-control" />\
                                        </div>\
                                     </div>\
                                </div>\
                                <div class="well Light_Padding" id="MoreInfoPlatillo_' + obj.Platillos[i]["idplatillo"] + '" hidden>\
                                    <div class="row">\
                                        <div id="MoreInfo_ImageContainer_' + obj.Platillos[i]["idplatillo"] + '" class="col-xs-12">\
                                            ../images/'+ obj.Platillos[i]["imagepath"] + '"\
                                        </div>\
                                    </div>\
                                    <div class="row">\
                                        <div id="MoreInfo_Description_' + obj.Platillos[i]["idplatillo"] + '" class="row-xs-12 Align_Center Black_Font">\
                                                '+ obj.Platillos[i]["descripcion"] + '\
                                       </div>\
                                    </div>\
                                    <div class="row">\
                                        <div id="MoreInfo_Precio_' + obj.Platillos[i]["idplatillo"] + '" class="row-xs-12 Align_Center Black_Font">\
                                                Precio: $'+ obj.Platillos[i]["precio"] + '\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>');
        }
    });
   
}

function CargarPlatillosPorCategoria(categoria,mesa) {
    var PlatillosContainer = document.getElementById("Modal_AgregarPlatillo_PlatilloContainer");
    PlatillosContainer.innerHTML = "";

    $.post("php/CargarPlatillosPorCategoria.php", { Categoria: categoria }, function (data, status) {
        
        var obj = JSON.parse(data);
        for(var i = 0; i < obj.Platillos.length; i++)
        {
            PlatillosContainer.insertAdjacentHTML('beforeend', '\
                                 <div class="well Boton_Parthenon"> \
                                    <div class="row Align_Center">\
                                        <div class="col-xs-12">\
                                             <span id="MoreInfo_Nombre_' + obj.Platillos[i]["idplatillo"] + '" class="badge Boton_Parthenon">' + obj.Platillos[i]["nombre"] + '</span>\
                                        </div>\
                                    </div>\
                                    \
                                    <div class="row Light_Padding">\
                                        <div class="col-sm-4">\
                                            <label>Cantidad</label><input class="form-control" min="1" max="10" onkeyup = "LimitQty(' + obj.Platillos[i]["idplatillo"] + ')" type="number" id="CargarPlatillos_Cantidad_' + obj.Platillos[i]["idplatillo"] + '" value="1"/>\
                                        </div>\
                                        <div class="col-sm-4">\
                                            <label></label><button onclick="CrearPedido(' + obj.Platillos[i]["idplatillo"] + ',' + mesa + ')" class="btn btn-block Align_Center Boton_AgregarPedido"> + </button>\
                                        </div>\
                                        <div class="col-sm-4">\
                                            <label></label><button onclick="ShowMoreInfoPlatillo(' + obj.Platillos[i]["idplatillo"] + ')" class="btn btn-block Align_Center Boton_AgregarPedido"> ? </button>\
                                        </div>\
                                    </div>\
                                    <div class="row Light_Padding">\
                                        <div class="col-sm-4">\
                                            <label>Costo Extra</label><input class="form-control" type="number" onkeyup = "LimitPrice(' + obj.Platillos[i]["idplatillo"] + ')" id="CargarPlatillos_CostoExtra_' + obj.Platillos[i]["idplatillo"] + '" />\
                                        </div>\
                                        <div class="col-sm-8">\
                                            <label>Comentarios</label><input class="form-control" id="CargarPlatillos_Comentarios_' + obj.Platillos[i]["idplatillo"] + '" type="text" class="form-control" />\
                                        </div>\
                                     </div>\
                                </div>\
                                <div class="well Light_Padding" id="MoreInfoPlatillo_'+ obj.Platillos[i]["idplatillo"] + '" hidden>\
                                    <div class="row">\
                                        <div id="MoreInfo_ImageContainer_' + obj.Platillos[i]["idplatillo"] + '" class="col-xs-12">\
                                            ../images/'+obj.Platillos[i]["imagepath"]+'\
                                        </div>\
                                    </div>\
                                    <div class="row">\
                                        <div id="MoreInfo_Description_' + obj.Platillos[i]["idplatillo"] + '" class="row-xs-12 Align_Center Black_Font">\
                                                '+ obj.Platillos[i]["descripcion"] + '\
                                       </div>\
                                    </div>\
                                    <div class="row">\
                                        <div id="MoreInfo_Precio_' + obj.Platillos[i]["idplatillo"] + '" class="row-xs-12 Align_Center Black_Font">\
                                                Precio: $'+ obj.Platillos[i]["precio"] + '\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>');
        }
    });
}
//Cargar Platillos en Modal Agregar Platillo

function PagarPorMonto(idmesa) {
    $.post("php/GetAllinfoPedidosByMesa.php", { IdMesa: idmesa }, function (data, status) {
        var obj = JSON.parse(data);
        
        var ContainerPedidos = document.getElementById("PorMonto_ContainerPedidos");
        ContainerPedidos.innerHTML = "";
        var Total= 0.0;
        for(var i = 0; i < obj.Pedido.length; i++)
        {
            Total = parseFloat(Total) + parseFloat(obj.Pedido[i]["precio"]);
            ContainerPedidos.insertAdjacentHTML('beforeend','<div class="row">\
                                                            <div class="col-sm-4">\
                                                                '+ obj.Pedido[i]["nomplatillo"] +'\
                                                            </div>\
                                                            <div class="col-sm-5">\
                                                                '+obj.Pedido[i]["comentario"]+'\
                                                            </div>\
                                                            <div class="col-sm-1">\
                                                                $'+obj.Pedido[i]["precio"]+'\
                                                            </div>\
                                                        </div><hr />');
        }
        var Subtotal = (parseFloat(Total) / 1.16);
        var IVA = parseFloat(Total) - parseFloat(Subtotal);
        
        document.getElementById("ModalMonto_Total").innerHTML = "Total: $" + Total.toFixed(2);
        document.getElementById("ModalMonto_Subtotal").innerHTML = "Subtotal: $" + Subtotal.toFixed(2);
        document.getElementById("ModalMonto_IVA").innerHTML = "IVA: $" + IVA.toFixed(2);
        document.getElementById("ModalMonto_DineroRestante").innerHTML = "Dinero Restante: $" + Total.toFixed(2);
    });
    $("#Modal_CajaPorMonto").modal("show");
}

function CloseCajaModalPorMonto(){
    $("#Modal_CajaPorMonto").modal("hide");
}

function InputMonto(idmonto)
{
    var Cambio = document.getElementById("ModalMonto_Cambio" + idmonto);
    var Pago = document.getElementById("ModalMonto_Pago" + idmonto);
    var Monto = document.getElementById("ModalMonto_Monto" + idmonto);

    var MontoContainer = document.getElementById("PorMonto_ContainerMontos");
    var Array = MontoContainer.innerHTML.split('<p hidden="">Separator</p>');
    var Index = parseInt(Array.length)-1;

    var TotalMonto = parseFloat(0.0);
    for(var i = 0; i < Index; i++)
    {
        var val = parseFloat(document.getElementById("ModalMonto_Monto" + i).value);
        if (isNaN(val))
            val = parseFloat(0.0);
        
        TotalMonto = parseFloat(TotalMonto) + parseFloat(val);
    }
    var TotalCuenta = parseFloat(document.getElementById("ModalMonto_Total").innerHTML.split("$")[1]);

    if (TotalCuenta - parseFloat(TotalMonto) < 0){
        var MontoActual = document.getElementById("ModalMonto_Monto" + idmonto);
        var NegDifference = parseFloat(TotalCuenta - parseFloat(TotalMonto));
        MontoActual.value = (parseFloat(MontoActual.value) + NegDifference).toFixed(2);
        document.getElementById("ModalMonto_DineroRestante").innerHTML = "Dinero Restante: $0";
        TotalMonto = parseFloat(TotalCuenta); //Total cuenta y total monto son cantidades iguales en este paso del algoritmo      
    }
    else
        document.getElementById("ModalMonto_DineroRestante").innerHTML = "Dinero Restante: $" + (TotalCuenta - parseFloat(TotalMonto)).toFixed(2);


    if (parseFloat(Pago.value) > parseFloat(Monto.value))
        Cambio.value = (parseFloat(Pago.value) - parseFloat(Monto.value)).toFixed(2);

    else
        Cambio.value = parseFloat(0.0);

    var Lock = true;
    for(var j = 0; j < Index; j++)
    {
        var PagoActual = parseFloat(document.getElementById("ModalMonto_Pago" + j).value);
        var MontoActual = parseFloat(document.getElementById("ModalMonto_Monto" + j).value);

        if (isNaN(PagoActual))
            PagoActual = parseFloat(0.0);
        if (isNaN(MontoActual))
            MontoActual = parseFloat(0.0);

        if (parseFloat(PagoActual) < parseFloat(MontoActual)) {
            $("#ModalMonto_BotonContainer").hide(700);
            Lock = false;
            break;
        }
    }
    if(Lock){
        if (parseFloat(TotalMonto) == TotalCuenta)
            $("#ModalMonto_BotonContainer").show(700);
        else
            $("#ModalMonto_BotonContainer").hide(700);
    }
}

function InputPago(idmonto) {
    var Pago = document.getElementById("ModalMonto_Pago" + idmonto);
    var Monto = document.getElementById("ModalMonto_Monto" + idmonto);
    var Cambio = document.getElementById("ModalMonto_Cambio" + idmonto);
    var MontoContainer = document.getElementById("PorMonto_ContainerMontos");

    if (parseFloat(Pago.value) > parseFloat(Monto.value)) {
        Cambio.value = (parseFloat(Pago.value) - parseFloat(Monto.value)).toFixed(2);
    }
    else
        Cambio.value = parseFloat(0.0);

    //CHEQUEO DE BOTON COBRAR
    var Array = MontoContainer.innerHTML.split('<p hidden="">Separator</p>');
    var Index = parseInt(Array.length) - 1;

    var TotalMonto = parseFloat(0.0);
    for (var i = 0; i < Index; i++) {
        var val = parseFloat(document.getElementById("ModalMonto_Monto" + i).value);
        if (isNaN(val))
            val = parseFloat(0.0);

        TotalMonto = parseFloat(TotalMonto) + parseFloat(val);
    }
    var TotalCuenta = parseFloat(document.getElementById("ModalMonto_Total").innerHTML.split("$")[1]);


    var Lock = true;
    for (var j = 0; j < Index; j++) {
        var PagoActual = parseFloat(document.getElementById("ModalMonto_Pago" + j).value);
        var MontoActual = parseFloat(document.getElementById("ModalMonto_Monto" + j).value);

        if (isNaN(PagoActual))
            PagoActual = parseFloat(0.0);
        if (isNaN(MontoActual))
            MontoActual = parseFloat(0.0);

        if (parseFloat(PagoActual) < parseFloat(MontoActual)) {
            $("#ModalMonto_BotonContainer").hide(700);
            Lock = false;
            break;
        }
    }
    if (Lock) {
        if (parseFloat(TotalMonto) == parseFloat(TotalCuenta))
            $("#ModalMonto_BotonContainer").show(700);
        else
            $("#ModalMonto_BotonContainer").hide(700);
    }
    //CHEQUEO DE BOTON COBRAR
}

function AddMonto(){
    var Container = document.getElementById("PorMonto_ContainerMontos");
    var InnerMontos = Container.innerHTML.split('<p hidden="">Separator</p>');

    var Array = {};
    for (var i = 0; i < InnerMontos.length - 1; i++)
    {
        var temp;
        var tempMonto =  isNaN(document.getElementById("ModalMonto_Monto" + i).value)  ? 0 : document.getElementById("ModalMonto_Monto" + i).value;
        var tempPago = isNaN(document.getElementById("ModalMonto_Pago" + i).value) ? 0 : document.getElementById("ModalMonto_Pago" + i).value;
        var tempCambio = isNaN(document.getElementById("ModalMonto_Cambio" + i).value) ? 0 : document.getElementById("ModalMonto_Cambio" + i).value;
        temp = { Monto: tempMonto, Pago: tempPago, Cambio: tempCambio };
        Array[i] = temp;
    }
    
    //var x = 10;
    //alert(x > 4 ? "si" : "no");
    var Index = InnerMontos.length;
    var NewRow = '<div class="row topmargin">\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon" >Monto</label><input onkeyup="InputMonto('+ (parseInt(Index)-1) +')" id="ModalMonto_Monto' + (parseInt(Index) - 1) + '" class="form-control" type="number" />\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon" >Pago</label><input onkeyup="InputPago('+ (parseInt(Index)-1) +')" id="ModalMonto_Pago' + (parseInt(Index) - 1) + '" class="form-control" type="number" />\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon">Metodo</label>\
                                        <select class="form-control xsmall" id="ModalMonto_MontoSel' + (parseInt(Index) - 1) + '">\
                                            <option>Efectivo</option>\
                                            <option>Tarjeta</option>\
                                        </select>\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon">Cambio</label><input id="ModalMonto_Cambio' + (parseInt(Index) - 1) + '" class="form-control" type="number" />\
                                    </div>\
                                </div>';

    var Output = "";
    for (var k = 0 ; k < InnerMontos.length - 1; k++) {
        Output = Output + '<div class="row topmargin">\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon" >Monto</label><input onkeyup="InputMonto('+ k + ')" id="ModalMonto_Monto' + k + '" class="form-control" type="number"/>\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon" >Pago</label><input onkeyup="InputPago('+ k + ')" id="ModalMonto_Pago' + k + '" class="form-control" type="number"/>\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon">Metodo</label>\
                                        <select class="form-control xsmall" id="ModalMonto_MontoSel' + k + '">\
                                            <option>Efectivo</option>\
                                            <option>Tarjeta</option>\
                                        </select>\
                                    </div>\
                                    <div class="col-sm-3">\
                                        <label class="badge Boton_Parthenon">Cambio</label><input id="ModalMonto_Cambio' + k + '" class="form-control" type="number"/>\
                                    </div>\
                              </div>' + '<p hidden="">Separator</p><hr />';
    }
    Output = Output + NewRow + '<p hidden="">Separator</p>';
    Container.innerHTML = Output;

    for (var l = 0; l < InnerMontos.length - 1; l++){
        document.getElementById("ModalMonto_Monto" + l).value = parseFloat(Array[l]["Monto"]).toFixed(2);
        document.getElementById("ModalMonto_Pago" + l).value = parseFloat(Array[l]["Pago"]).toFixed(2);
        document.getElementById("ModalMonto_Cambio" + l).value = parseFloat(Array[l]["Cambio"]).toFixed(2);
    }

    //CHEQUEO DE BOTON COBRAR
    var MontoContainer = document.getElementById("PorMonto_ContainerMontos");
    var Array = MontoContainer.innerHTML.split('<p hidden="">Separator</p>');
    var Index = parseInt(Array.length) - 1;

    var TotalMonto = parseFloat(0.0);
    for (var i = 0; i < Index; i++) {
        var val = parseFloat(document.getElementById("ModalMonto_Monto" + i).value);
        if (isNaN(val))
            val = parseFloat(0.0);

        TotalMonto = parseFloat(TotalMonto) + parseFloat(val);
    }
    var TotalCuenta = parseFloat(document.getElementById("ModalMonto_Total").innerHTML.split("$")[1]);

    var DineroRestante = parseFloat(TotalCuenta) - parseFloat(TotalMonto);
    document.getElementById("ModalMonto_DineroRestante").innerHTML = parseFloat(DineroRestante) > 0 ? "Dinero Restante: $" + DineroRestante : "$Dinero Restante: $" + 0;

    var Lock = true;
    for (var j = 0; j < Index; j++) {
        var PagoActual = parseFloat(document.getElementById("ModalMonto_Pago" + j).value);
        var MontoActual = parseFloat(document.getElementById("ModalMonto_Monto" + j).value);

        if (isNaN(PagoActual))
            PagoActual = parseFloat(0.0);
        if (isNaN(MontoActual))
            MontoActual = parseFloat(0.0);

        if (parseFloat(PagoActual) < parseFloat(MontoActual)) {
            $("#ModalMonto_BotonContainer").hide(700);
            Lock = false;
            break;
        }
    }
    if (Lock) {
        if (parseFloat(TotalMonto) == parseFloat(TotalCuenta))
            $("#ModalMonto_BotonContainer").show(700);
        else
            $("#ModalMonto_BotonContainer").hide(700);
    }
    //CHEQUEO DE BOTON COBRAR
}

function ModalMonto_SetDescuento(idmesa) {
    $.post("php/GetAllinfoPedidosByMesa.php", { IdMesa: idmesa }, function (data, status) {
        var obj = JSON.parse(data);

        var C_Total = document.getElementById("ModalMonto_Total");
        var C_IVA = document.getElementById("ModalMonto_IVA");
        var C_Sub = document.getElementById("ModalMonto_Subtotal");
        var Descuento = document.getElementById("ModalMonto_Descuento").value;    

        var Total = parseFloat(0.0);
        for (var i = 0; i < obj.Pedido.length; i++)
            Total = parseFloat(Total) + parseFloat(obj.Pedido[i]["precio"]);
        
        var SubTotal = parseFloat(Total) / 1.16;
        var NewSubtotal = SubTotal - (parseFloat(SubTotal) * parseFloat(Descuento));
        var NewTotal = NewSubtotal * 1.16;
        var NewIVA = (parseFloat(NewTotal) - parseFloat(NewSubtotal));
        
        C_Sub.innerHTML = "Subtotal: $" + NewSubtotal.toFixed(2);
        C_IVA.innerHTML = "IVA: $" + NewIVA.toFixed(2);
        C_Total.innerHTML = "Total: $" + NewTotal.toFixed(2);

        //Comparar Montos contra el nuevo total
        var MontosVal = parseFloat(0.0);
        var AllMontos = document.getElementById("PorMonto_ContainerMontos").innerHTML.split('<p hidden="">Separator</p>');
        for(var j = 0; j < AllMontos.length - 1; j++){
            var Monto = parseFloat(document.getElementById("ModalMonto_Monto" + j).value);
            if (isNaN(Monto))
                Monto = parseFloat(0.0);

            MontosVal = parseFloat(MontosVal) + parseFloat(Monto);
        }

        var NewLastMonto = parseFloat(0.0);
        var LastMonto = parseFloat(document.getElementById("ModalMonto_Monto" + (parseInt(AllMontos.length) - 2)).value);
        if (parseFloat(MontosVal) > parseFloat(NewTotal) && !isNaN(LastMonto)) //Si los Montos son mayores y si el ultimo Monto es un número
            if (LastMonto > 0) //Si este número es mayor a cero
                NewLastMonto = LastMonto - (parseFloat(MontosVal) - parseFloat(NewTotal));
            else
            {
                for (var j = 0; j < AllMontos.length - 1; j++)
                    document.getElementById("ModalMonto_Monto" + j).value = parseFloat(0.0).toFixed(2);
                    
            }
        else
        {
            for (var j = 0; j < AllMontos.length - 1; j++)
                document.getElementById("ModalMonto_Monto" + j).value = parseFloat(0.0).toFixed(2);
        }

        document.getElementById("ModalMonto_Monto" + (parseInt(AllMontos.length) - 2)).value = NewLastMonto.toFixed(2);
    });
    
}

function RemoveMonto(){
    var PorMontoContainer = document.getElementById("PorMonto_ContainerMontos");
    var InnerMontos = PorMontoContainer.innerHTML.split('<p hidden="">Separator</p>');
    var Output = "";

    
    var InnerMontos = PorMontoContainer.innerHTML.split('<p hidden="">Separator</p>');
    var Array = {};
    for (var i = 0; i < InnerMontos.length - 1; i++) {
        var temp;
        var tempMonto = isNaN(document.getElementById("ModalMonto_Monto" + i).value) ? 0 : document.getElementById("ModalMonto_Monto" + i).value;
        var tempPago = isNaN(document.getElementById("ModalMonto_Pago" + i).value) ? 0 : document.getElementById("ModalMonto_Pago" + i).value;
        var tempCambio = isNaN(document.getElementById("ModalMonto_Cambio" + i).value) ? 0 : document.getElementById("ModalMonto_Cambio" + i).value;
        temp = { Monto: tempMonto, Pago: tempPago, Cambio: tempCambio };
        Array[i] = temp;
    }

    for (var k = 0; k < InnerMontos.length - 2; k++) 
        Output = Output + InnerMontos[k] + '<p hidden="">Separator</p>';
   
    PorMontoContainer.innerHTML = Output;

    for (var l = 0; l < InnerMontos.length - 2; l++) {
        document.getElementById("ModalMonto_Monto" + l).value  = isNaN(parseFloat(Array[l]["Monto"])) ? 0 : parseFloat(Array[l]["Monto"]).toFixed(2);
        document.getElementById("ModalMonto_Pago" + l).value   = isNaN(parseFloat(Array[l]["Pago"])) ? 0 : parseFloat(Array[l]["Pago"]).toFixed(2);
        document.getElementById("ModalMonto_Cambio" + l).value = isNaN(parseFloat(Array[l]["Cambio"])) ? 0 : parseFloat(Array[l]["Cambio"]).toFixed(2);
    }
    
    //CHEQUEO DE BOTON COBRAR
    var MontoContainer = document.getElementById("PorMonto_ContainerMontos");
    var Array = MontoContainer.innerHTML.split('<p hidden="">Separator</p>');
    var Index = parseInt(Array.length) - 1;

    var TotalMonto = parseFloat(0.0);
    for (var i = 0; i < Index; i++) {
        var val = parseFloat(document.getElementById("ModalMonto_Monto" + i).value);
        if (isNaN(val))
            val = parseFloat(0.0);

        TotalMonto = parseFloat(TotalMonto) + parseFloat(val);
    }
    var TotalCuenta = parseFloat(document.getElementById("ModalMonto_Total").innerHTML.split("$")[1]);

    var DineroRestante = parseFloat(TotalCuenta) - parseFloat(TotalMonto);
    document.getElementById("ModalMonto_DineroRestante").innerHTML = parseFloat(DineroRestante) > 0 ? "Dinero Restante: $" + DineroRestante : "$Dinero Restante: $" + 0;

    var Lock = true;
    for (var j = 0; j < Index; j++) {
        var PagoActual = parseFloat(document.getElementById("ModalMonto_Pago" + j).value);
        var MontoActual = parseFloat(document.getElementById("ModalMonto_Monto" + j).value);

        if (isNaN(PagoActual))
            PagoActual = parseFloat(0.0);
        if (isNaN(MontoActual))
            MontoActual = parseFloat(0.0);

        if (parseFloat(PagoActual) < parseFloat(MontoActual)) {
            $("#ModalMonto_BotonContainer").hide(700);
            Lock = false;
            break;
        }
    }
    if (Lock) {
        if (parseFloat(TotalMonto) == parseFloat(TotalCuenta))
            $("#ModalMonto_BotonContainer").show(700);
        else
            $("#ModalMonto_BotonContainer").hide(700);
    }
    //CHEQUEO DE BOTON COBRAR
}

function CobrarImprimirNotaVenta(idmesa) {
    WaitingAnimation("true");
    var descuento = document.getElementById("ModalMonto_Descuento").value;

    $.post("php/ConcretarVentaPorMonto.php", { IdMesa: idmesa, Descuento:descuento }, function (data, status) {
        $("#Modal_CajaPorMonto").modal("hide");
        LoadPedidos(idmesa);
        Mesa_Hide(idmesa);
       
        EndWaitingAnimation();
        var obj = JSON.parse(data);
        if (confirm("¿Desea Facturar esta cuenta?")) 
            window.open("../administrador/facturar.html?total=" + obj.Nota[0]["Total"] + "&nota_id=" + obj.Nota[0]["Id"]);

        //Este Apartado necesita más trabajo ya que seguramente cada monto necesitara
        //su propia factura, sin embargo con la funcionalidad actual solo se hace una factura por el total de la nota
        
        
    });

}

function ActivarMesa(id) {
    $.post("php/ActivarMesa.php", { idmesa: id }, function (data, status) {
        document.getElementById("HideShowButton_Container").innerHTML = '<button onclick="Mesa_Hide(' + id + ')" class="btn btn-block Boton_Parthenon">Desactivar Mesa</button>';
        document.getElementById("PorMontoButton_Container").innerHTML = '<button onclick="PagarPorMonto(' + id + ')" class="btn btn-block    Boton_Parthenon">Por Monto</button>';
        document.getElementById("DividirCuentasButton_Container").innerHTML = '<button onclick="DividirCuentas(' + id + ')" class="btn btn-block    Boton_Parthenon">Por cuentas</button>';
        document.getElementById("AgregarPlatilloButton_Container").innerHTML = '<button onclick="AgregarPlatillo(' + id + ')" class="btn btn-block  Boton_Parthenon"> + </button>';
    });
}

function DesactivarMesa(id){
    $.post("php/DesactivarMesa.php", { idmesa: id }, function (data, status) {
        document.getElementById("HideShowButton_Container").innerHTML = '<button onclick="Mesa_Show(' + id + ')" class="btn btn-block  Boton_Parthenon">Activar Mesa</button>';
        document.getElementById("PorMontoButton_Container").innerHTML = "";
        document.getElementById("DividirCuentasButton_Container").innerHTML = "";
        document.getElementById("AgregarPlatilloButton_Container").innerHTML = "";
    });
}

function Mesa_Show(id) {
    $(".HideShow").show(1000, ActivarMesa(id));
}

function Mesa_Hide(id) {
    $.post("php/GetPedidosByMesa.php", { IdMesa: id }, function (data, status) {
        var obj = JSON.parse(data);
        if (parseInt(obj.Pedidos[0]["cantidad"]) > 0)
            return;

          $(".HideShow").hide(1000, DesactivarMesa(id));
    });
    
}

function ReturnIndex() {
    location.replace("index.html");
}
//MESA HTML FUNCTIONALITY