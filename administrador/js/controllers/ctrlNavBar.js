function ctrlNavBar($scope,$route){
$scope.currentTab = "";
$scope.currentCategory = -1;
    $scope.getCurrentTab = function(tab){
    $scope.currentTab = tab;

     }
     
     $scope.setCategory = function(category){
         $scope.currentCategory = category;

     }

 
}