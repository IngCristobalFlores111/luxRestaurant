function ctrlTopSale($scope, $http) {
    $scope.categorias = [];
    $scope.platillos = [];
    $scope.topMas = [];
    $scope.topMenos = [];
    $scope.qPlatillo ="";
    $scope.ventasQplatillo = [];
    $scope.buscarPlatillo = function(){
        var q = $scope.qPlatillo; 
        if(q!=""){
            $http.get("PHP/new/topsale/fetchData.php",{params:{accion:"buscarProducto",q:q}}).then(function(resp){
                $scope.ventasQplatillo = resp.data;                
            });
        }else{
            toastr.info("Tu buesqueda no puede ser vacia");
        }
    }
    $http.get("PHP/new/topsale/fetchData.php", { params: { accion: "categorias" } }).then(function (resp) {
        $scope.categorias = resp.data;
    });
    $http.get("PHP/new/topsale/fetchData.php", { params: { accion: "topProductosInit" } }).then(function (resp) {
        var data = resp.data;
        $scope.platillos = data.topCategorias;
        $scope.topMas = data.topMas;
        $scope.topMenos = data.topMenos;        
    });
    var anterior = {};
    $scope.filterCategoria = undefined;
    $scope.categoria = "Todos los productos";
    $scope.setCategoria = function (categoria) {
        categoria.active = true;
        anterior.active = false;
        anterior = categoria;
        $scope.filterCategoria = categoria.id;
        $scope.categoria = categoria.nombre;

    }


}