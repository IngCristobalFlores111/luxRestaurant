// JavaScript source code

//OpcionesGenerada.html
//Modificar


var gd_enviarCorreosConfirmacion = null;
var gd_modalEnvioCorreo = null;
var gd_botonAceptar = null;
var gd_botonCancelar = null;
var gd_botonTerminar = null;
var gd_enviarCorreosExito = null;
var gd_enviarCorreosFallido = null;
var gd_tableCorreosDefault = null;
var ga_mensajeExtra = null;
var gd_modalCambiarMensaje = null;
var gd_botonPlantillaMail = null;
var gd_inputCorreoMsg = null;
var gd_inputCorreoAsunto = null;
var gd_contentModalEnviarCorreo = null
var gd_cubitoWaiting = null;
var gd_botonModalEnviar = null;
var gd_botonModalCancelar = null;
var gd_botonModalTerminar = null;



function modalDeConfirmacion() {
    $("#i_msjSuccessContainer").hide();
    $("#i_msjWarningContainer").hide();
    $('#i_modalTimbrar').modal('show');
}

function timbrar(Id, Folio) {
    $.post("Facturacion/TimbrarFactura.php", { ID: Id, FOLIO: Folio, EXPEDICION: "1" }, function (data, status) {
        var msg = data.split(";");

        if (msg[0] === 1) {
            $("#i_msjSuccess").empty();
            $("#i_msjSuccess").append(msg[1]);
            $("#i_msjSuccessContainer").show();
            $("#i_msjWarningContainer").hide();
            $("#i_botonAceptar").hide();
            $("#i_botonCancelar").empty();
            $("#i_botonCancelar").append("Salir");
        }
        else {
            enviar_correo
            $("#i_msjWarning").empty();
            $("#i_msjWarning").append(msg[1]);
            $("#i_msjWarningContainer").show();
            $("#i_msjSuccessContainer").hide();
            $("#i_botonAceptar").hide();
            $("#i_botonCancelar").empty();
            $("#i_botonCancelar").append("Salir");
        }
    });


}

function agregar_correo() {
    var ld_Tabla = $("#i_tableCorreosDefault");
    var ls_correoNuevo = $("#i_inputCorreoNuevo").val();
    if (parseInt(ls_correoNuevo.length) < parseInt(1)) {
        alert("El espacio esta vacio");
        return;
    }
    else if (ls_correoNuevo.indexOf("@") < 0 && ls_correoNuevo.indexOf(".") < 0) {
        alert("Correo mal ingresado");
        return;
    }

    if (parseInt(ld_Tabla.children().size()) === 0) {
        ld_Tabla.prepend('<tr><td><input disabled placeholder="' + ls_correoNuevo + '"  type="text" id="i_inputCorreo_0" class="form-control c_input" /></td><td> <button class="btn btn-lg" id="i_btnAgregarMensajeCorreo_0" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" aria-hidden="true"></i></button> <button class="btn btn-lg" id="i_btnEliminarCorreo_0" onclick="eliminar_correo(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
        return;
    }
    var ld_ultimoBoton = $("#i_tableCorreosDefault tr:first").find("button:last");
    var ln_nuevoId = parseInt(ld_ultimoBoton.attr("id")[ld_ultimoBoton.attr("id").length - 1]) + 1;
    ld_Tabla.prepend('<tr><td><input disabled placeholder="' + ls_correoNuevo + '" type="text" id="i_inputCorreo_' + ln_nuevoId + '" class="form-control c_input" /></td><td><button class="btn btn-lg" id="i_btnAgregarMensajeCorreo_' + ln_nuevoId + '" onclick="agregar_mensaje(this)"><i class="fa fa-envelope" aria-hidden="true"></i></button>  <button class="btn btn-lg" id="i_btnEliminarCorreo_' + ln_nuevoId + '" onclick="eliminar_correo(this)"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');
}

function eliminar_correo(me) {
    var ls_id = me.id;
    $("#" + ls_id).parent().parent().remove();

    var la_id = ls_id.split("_");
    var ln_id = la_id[la_id.length - 1];
    delete ga_mensajeExtra[ln_id]
}

function enviar_correos() {
    gd_botonModalEnviar.show();
    gd_botonModalCancelar.show();
    gd_botonModalTerminar.hide();

    gd_cubitoWaiting.hide();
    if (gd_tableCorreosDefault.children().size() === 0) {
        alert("No hay seleccionados");
        return;
    }

    var correos = [];
    var children = gd_tableCorreosDefault.children();
    children.each(function () {
        correos.push($(this).find("input").attr("placeholder"));
    });

    var i = 0;
    var size = correos.length;

    var html = [];
    for (i; i < size; i++)
        html.push('<li><span><strong style="display:inline; margin-right:10px;">' + correos[i] + '</strong></span><span><i class="fa fa-question-circle" style="display:inline; color:dimgrey;" aria-hidden="true"></i></span></li>');

    html = html.join("");

    gd_enviarCorreosConfirmacion.empty();
    gd_enviarCorreosConfirmacion.append("<ul>" + html + "</ul>");
    gd_modalEnvioCorreo.modal("show");
    gd_botonAceptar.show();
    gd_botonCancelar.show();
    gd_botonTerminar.hide();
    gd_enviarCorreosExito.hide();
    gd_enviarCorreosFallido.hide();
    gd_enviarCorreosConfirmacion.show();

}

function enviar() {
    var correos = [];
    var children = gd_tableCorreosDefault.children();
    children.each(function () {
        var correo_id = [];
        correo_id.push($(this).find("input").attr("placeholder"));
        correo_id.push($(this).find("p").text());

        var la_id = $(this).find("input").attr("id").split("_");
        var ln_id = la_id[parseInt(la_id.length) - 1];

        if (ga_mensajeExtra[ln_id] === undefined) {
            correo_id.push("");
            correo_id.push("");
        }
        else {
            correo_id.push(ga_mensajeExtra[ln_id][0]);
            correo_id.push(ga_mensajeExtra[ln_id][1]);
        }
        correos.push(correo_id);
    });

    var folio = $("#i_divFolio").text();
    var id = $("#i_divId").text(); //0: Pendiente 1: Timbrada 2: Cancelada                             

    gd_contentModalEnviarCorreo.hide(500);
    gd_cubitoWaiting.show(500);
    $.post("php/enviarCorreosJAGS.php", { CORREOS: '{"Correos":' + JSON.stringify(correos) + '}', FOLIO: folio, ID: id, UITYPE: 0 }, function (data, status) {
        var ls_dataarr = data.split("????");
        var la_respuestaCorreos = JSON.parse(ls_dataarr[parseInt(ls_dataarr.length) - parseInt(1)]);
        var i = 0;
        var children = $("#i_enviarCorreosConfirmacion").children().children();
        children.each(function () {
            var ld_symbol = $(this).find("i");
            ld_symbol.removeClass("fa fa-question-circle");

            if (parseInt(la_respuestaCorreos.Correos[i][1]) < parseInt(0)) {
                ld_symbol.addClass("fa fa-times-circle");
                ld_symbol.css("color", "red");
            }

            else {
                ld_symbol.addClass("fa fa-check-circle");
                ld_symbol.css("color", "blue");
            }
            i++;
        });
        gd_contentModalEnviarCorreo.show(500);
        gd_cubitoWaiting.hide(500);

        gd_botonModalEnviar.hide();
        gd_botonModalCancelar.hide();
        gd_botonModalTerminar.show();
    });
}

function cargarCorreosDefault(idcliente) {
    $("#i_tableCorreosDefault").load("php/cargarCorreosDefault.php", { ID: idcliente });
}

function agregar_mensaje(message) {
    gd_inputCorreoAsunto.val("");
    gd_inputCorreoMsg.val("");
    var la_agregarMsjId = $(message).attr("id").split("_");
    var ln_id = parseInt(la_agregarMsjId[parseInt(la_agregarMsjId.length) - 1]);

    gd_botonPlantillaMail.attr("value", ln_id);
    gd_modalCambiarMensaje.modal("show");

}
function mensaje() {
    var la_asuntoMensaje = [];
    var ln_correoMsgId = parseInt(gd_botonPlantillaMail.attr("value"));

    la_asuntoMensaje.push(gd_inputCorreoAsunto.val());
    la_asuntoMensaje.push(gd_inputCorreoMsg.val());

    ga_mensajeExtra[ln_correoMsgId.toString()] = la_asuntoMensaje;

    gd_modalCambiarMensaje.modal("hide");
}


//Modificar
//OpcionesGenerada.html
