
Date.prototype.addDays = function (days) {
    var dat = new Date(this.valueOf());
    dat.setDate(dat.getDate() + days);
    return dat;
}
function validate_ingredient_form(nombre,cantidad,unidad,costo,fecha)
{
    



    var message = '';
    var nombre = nombre.trim();
    var cantidad = cantidad.trim();
    cantidad = parseFloat(cantidad);
    var unidad = unidad.trim();


 
   costo = parseFloat(costo);
   
    if (nombre == '') {
        message += 'Campo nombre no puede estar vacio\n';
    }
    if(isNaN(cantidad))
    {
        message += 'cantidad tiene que ser numerica\n';

    }
    if (unidad == '')
        message += 'Campo unidad no puede estar vacio\n';
    if (isNaN(costo)) {

        message += 'Campo costo tiene que ser numerico\n';
    }





    if (fecha.trim() != '') {


        var today = new Date();
        var input_date = new Date(fecha);
        if (today.getTime() > input_date.getTime()) {
            message += 'La fecha es invalida, elije cualquier fecha posterior a hoy';
        }
    }






    if (message == '') {
        message = 'true';  // se valido correctamente el form;
    }



    return message;






}