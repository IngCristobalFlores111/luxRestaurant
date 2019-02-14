<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "ventasFecha":
        $fechaInicio = $_GET['fechaInicio'];
        $fechaFin = $_GET['fechaFin'];
        $query="SELECT SUM(tbventaplatillos.cantidad) AS cantidad, 
        tbventaplatillos.fecha,SUM(tbventaplatillos.total) AS total,
        SUM(tbventaplatillos.costo) AS gastos,
        SUM(tbventaplatillos.ganancia) AS ganancia,
        tbplatillos.nombre AS platillo FROM `tbventaplatillos` 
        INNER JOIN tbplatillos 
        ON tbplatillos.idplatillo= tbventaplatillos.idplatillo 
        WHERE tbventaplatillos.fecha BETWEEN ? AND ? 
        GROUP BY tbventaplatillos.idplatillo,tbventaplatillos.fecha";
       $datos = $sql->get_bind_results($query,array("ss",$fechaInicio,$fechaFin));
       $query="SELECT SUM(cantidad) as cantidad,SUM(total) AS total,SUM(costo) AS gastos,SUM(ganancia) AS ganancia FROM `tbventaplatillos`
        WHERE fecha BETWEEN ? AND ? ";
        $totales = $sql->get_bind_results($query,array("ss",$fechaInicio,$fechaFin));
        $totales = $totales[0];
       $query="SELECT tbplatillos.nombre AS label,SUM(tbventaplatillos.total) AS value  FROM `tbventaplatillos` INNER JOIN tbplatillos ON tbplatillos.idplatillo= tbventaplatillos.idplatillo 
       WHERE tbventaplatillos.fecha BETWEEN ? AND ? 
       GROUP BY tbventaplatillos.idplatillo";
      $top = $sql->get_bind_results($query,array("ss",$fechaInicio,$fechaFin));
               
        $output = array("totales"=>$totales,"datos"=>$datos,"top"=>$top);
       print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
       
        break;
        case "platillos":
       $query="SELECT idplatillo AS id,nombre FROM `tbplatillos`";
       $results = $sql->executeQuery($query);
       print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
       
        break;
        case "graficas":

        $fecha_from = $_GET['fecha_from'];
        $fecha_to = $_GET['fecha_to'];
        $por_platillo = $_GET['por_platillo'];
        $platillo = $_GET['idPlatillo'];
        $fecha_from = $sql->filter_input($fecha_from);
        $fecha_to = $sql->filter_input($fecha_to);
        $platillo = $sql->filter_input($platillo);
        $extraWhere= '';
        $extraWhere2 ='';
        $extraWhere3 ='';
        if($por_platillo=='1'){
            $extraWhere = "AND tbventaplatillos.idplatillo=".$platillo;
            $extraWhere2 =" AND idplatillo = '$platillo' ";
            
        }
        $query ="SELECT fecha AS y,SUM(`cantidad`) AS a,SUM(total) AS b,SUM(tbventaplatillos.costo) AS c,SUM(ganancia) AS d FROM `tbventaplatillos` WHERE fecha BETWEEN '$fecha_from' AND ' $fecha_to' $extraWhere GROUP BY fecha ORDER BY fecha ASC";
        $grafica = $sql->executeQuery($query);

        $query="SELECT SUM(total) AS total,SUM(costo) AS gastos,SUM(ganancia) AS ganancias FROM tbventaplatillos
        WHERE fecha BETWEEN '$fecha_from' AND '$fecha_to' $extraWhere2";
         $totales = $sql->executeQuery($query);
         $totales = $totales[0];
        
         $query="SELECT SUM(total) /(SELECT COUNT(*) FROM (SELECT DISTINCT fecha FROM `tbventaplatillos` WHERE fecha BETWEEN '$fecha_from' and '$fecha_to' $extraWhere2 ) T) as promedio_total, SUM(costo) /(SELECT COUNT(*) FROM (SELECT DISTINCT fecha FROM `tbventaplatillos` WHERE fecha BETWEEN '$fecha_from' and '$fecha_to' $extraWhere2 ) T) as promedio_gastos FROM tbventaplatillos WHERE fecha BETWEEN '$fecha_from' and '$fecha_to' $extraWhere2";
         $promedios = $sql->executeQuery($query);
         $promedios = $promedios[0];

         if($por_platillo=='0'){
         $query="SELECT SUM(cantidad) AS value,tbplatillos.nombre AS label 
         FROM `tbventaplatillos` 
         INNER JOIN tbplatillos 
         ON tbplatillos.idplatillo= tbventaplatillos.idplatillo WHERE 
         tbventaplatillos.fecha BETWEEN '$fecha_from' AND '$fecha_to'
         $extraWhere GROUP BY tbventaplatillos.idplatillo 
         ORDER BY value DESC";
         }else{
             $query="SELECT CONCAT(MONTHNAME(fecha),' ',YEAR(fecha)) AS label,SUM(total) AS value FROM tbventaplatillos WHERE 
             tbventaplatillos.fecha BETWEEN '$fecha_from' AND '$fecha_to'
             $extraWhere";

         }
                  $top = $sql->executeQuery($query);
                  
            
         $output = array("top"=>$top,"grafica"=>$grafica,"totales"=>$totales,"promedios"=>$promedios);
         
        print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
        

        break;

    }

}

?>