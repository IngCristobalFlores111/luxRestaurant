function gastosService($http){
this.getCategories = function(){

return $http.get("PHP/new/gastos/fetchData.php",{params:{accion:"categorias"}});

}
this.getExpenses = function(){

    return $http.get("PHP/new/gastos/fetchData.php",{params:{accion:"gastos"}});
    
}
this.addExpense = function(expense){
if(expense.nombre==""||expense.descripcion==""||expense.idCategoriaGasto==""||expense.total<0){
return 0;
}else{
return $http.post("PHP/new/gastos/accionGastos.php?accion=altaGasto",expense);
}

}
this.getEarnings = function(rango){
    return $http.get("PHP/new/gastos/fetchData.php",{params:{accion:"ganancias",rango:rango}});
}
this.updateExpense = function(gasto){

return $http.post("PHP/new/gastos/accionGastos.php?accion=actualizarGasto",gasto);

}
this.deleteExpense = function(idExpense){

    return $http.post("PHP/new/gastos/accionGastos.php?accion=eliminarGasto",{id:idExpense});
    
}


}