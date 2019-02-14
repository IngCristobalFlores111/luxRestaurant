<?php
include ('functions.php');
$opcion = $_POST['opcion'];
$fechaDesde = $_POST['fechaDesde'];
$fechaHasta = $_POST['fechaHasta'];
$platillo = null;
	if(isset($_POST['platillo'])){
		$platillo = $_POST['platillo'];
}

switch($opcion)
{
	
	case 0:   // platillo especifico por dia por cantidad 
		
		$query = "SELECT (SELECT SUM(cantidad) AS cantidad FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta')/datediff('$fechaHasta','$fechaDesde') AS promedio_dia";
		$obj1 = getJSONFromSql($query);   // promedio_vendido por dia 
		$query = "SELECT SUM(cantidad) AS total FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
		$obj2 = getJSONFromSql($query);  // total 
		$query = "SELECT fecha,cantidad FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY cantidad ORDER BY CANTIDAD DESC LIMIT 3";
		$obj3 = getJSONFromSql($query);  // top 3 dias mejor vendidos cuantos y en que fecha 
		$query = "SELECT (SELECT SUM(cantidad) AS cantidad FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta')/TIMESTAMPDIFF(MONTH, '$fechaDesde', '$fechaHasta') AS promedio_mes";
		$obj4 = getJSONFromSql($query);
		$query ="SELECT SUM(cantidad) as cantidad, CONCAT(YEAR(fecha),'-',MONTH(fecha)) AS mes FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY MONTH(fecha),YEAR(fecha) ORDER BY cantidad DESC LIMIT 3";
		$obj5 = getJSONFromSql($query);  // top 4 meses mejor vendidos 
		
		echo  '{"promedio_dia":'.$obj1.',"total":'.$obj2.',"datos":'.$obj3.',"promedio_mes":'.$obj4.',"datos2":'.$obj5.'}';
		break;
		
		case 1:  // por dia por platillo costo, ganancia total 
		
	
		
		$query = "SELECT ROUND(SUM(total),3) AS total, ROUND(SUM(costo),3) AS costo, ROUND(SUM(ganancia),3) AS ganancia FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre ='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
		
		$obj1 = getJSONFromSql($query);  // suma de costos, ganancias y totales 
		$query = "SELECT ROUND(AVG(total),4) AS total, ROUND(AVG(costo),4) AS costo, ROUND(AVG(ganancia),4) AS ganancia FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre ='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
		$obj2 = getJSONFromSql($query);  // promedios de costos, ganancias y totales
		$query = "SELECT SUM(total) as total,SUM(costo) as costo,SUM(ganancia) AS ganancia, CONCAT(YEAR(fecha),'-',MONTH(fecha)) AS mes FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY MONTH(fecha),YEAR(fecha) ORDER BY cantidad DESC LIMIT 3";
		$obj3 = getJSONFromSql($query);  // top 3 meses mejor vendidos 
		$query = "SELECT SUM(total) as total,SUM(costo) as costo,SUM(ganancia) AS ganancia,fecha FROM tbventaplatillos WHERE idplatillo = (SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo') AND fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY fecha ORDER BY cantidad DESC LIMIT 3";
		$obj4 = getJSONFromSql($query);  // top 4 dias mejor vendidos 
		echo  '{"suma_todo":'.$obj1.',"promedios_todo":'.$obj2.',"top_mes":'.$obj3.',"top_dia":'.$obj4.'}';

	
		
		
		break;
		case 2:
		
		$query = "SELECT ROUND(SUM(total),3) AS total, ROUND(SUM(costo),3) AS costo, ROUND(SUM(ganancia),3) AS ganancia FROM tbventaplatillos WHERE fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
		
		$obj1 = getJSONFromSql($query);  // suma de costos, ganancias y totales 
		$query = "SELECT ROUND(AVG(total),4) AS total, ROUND(AVG(costo),4) AS costo, ROUND(AVG(ganancia),4) AS ganancia FROM tbventaplatillos WHERE fecha BETWEEN '$fechaDesde' AND '$fechaHasta'";
		$obj2 = getJSONFromSql($query);  // promedios de costos, ganancias y totales
		$query = "SELECT SUM(total) as total,SUM(costo) as costo,SUM(ganancia) AS ganancia, CONCAT(YEAR(fecha),'-',MONTH(fecha)) AS mes FROM tbventaplatillos WHERE fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY MONTH(fecha),YEAR(fecha) ORDER BY cantidad DESC LIMIT 3";
		$obj3 = getJSONFromSql($query);  // top 3 meses mejor vendidos 
		$query = "SELECT SUM(total) as total,SUM(costo) as costo,SUM(ganancia) AS ganancia,fecha FROM tbventaplatillos WHERE fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY fecha ORDER BY cantidad DESC LIMIT 3";
		$obj4 = getJSONFromSql($query);  // top 4 dias mejor vendidos 
		$query ="SELECT p.nombre, SUM(v.cantidad) AS cantidad FROM `tbventaplatillos` v INNER JOIN tbplatillos p ON v.idplatillo = p.idplatillo WHERE v.fecha BETWEEN '$fechaDesde' AND '$fechaHasta' GROUP BY v.idplatillo ORDER BY cantidad DESC LIMIT 3";
		
		$obj5 = getJSONFromSql($query); // obtener top de platillos vendidos 
		echo  '{"suma_todo":'.$obj1.',"promedios_todo":'.$obj2.',"top_mes":'.$obj3.',"top_dia":'.$obj4.',"top_platillos":'.$obj5.'}';

		
		
		
		break;
		 
		
	
	
	
	
	
	
}



?>