
(function () {


    var usr = null;
    var pass = null;
    var btnLogin = null;
    $(document).ready(function () {
        usr = $("#user");
        pass = $("#pass");
        btnLogin = $("#btnLogin");
        btnLogin.click(login);
    });
    function login() {
        $.ajax({
            url: "php/Login.php",
            data: { usr: usr.val(), pass: pass.val() },
            type: "POST"

        }).done(function (data) {
            var resp = JSON.parse(data);
           
            if (resp.exito) {
                switch (resp.usr.tipo) {
                    case 1:
                        location.href = "../administrador/index-ng.html";
                        break;
                    case 2:
                        location.href = "../chef2/index.html";
                        break;
                    case 3:

                        location.href = "../mesero2/index.html";
                        break;

                }
            }
            else {
                toastr.info(resp.msg, "Mensaje del sistema");
            }
        });


    }


})()








