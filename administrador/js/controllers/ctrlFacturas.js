function ctrlFacturas($scope, $http) {
    $scope.facturas = [];

    $http.get("PHP/new/facturacion/fetchData.php", { params: { accion: "facturas" } }).then(function (resp) {
        $scope.facturas = resp.data;
    });


    $scope.verFactura = function (f) {
        window.open("index-ng.html#!/factura?id=" + f.id);
    }
    $scope.abrirPDF = function (f) {
        var folio = f.folio;
        var tmp = folio.split("-");
        var file = 'cfdi_' + tmp[0] + tmp[1] + ".pdf";
        window.open("Facturacion/timbrados/" + file);
    }

}