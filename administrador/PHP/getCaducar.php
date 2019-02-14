<?php
include ("functions.php");


// obtener datos de ingredientes que esten a punto de caducar 
$query = "SELECT nombre,cantidad,fecha_caducidad,DATEDIFF(now(),fecha_caducidad) dias_caducados FROM `ingrediente` ORDER BY DATEDIFF(now(),fecha_caducidad) DESC LIMIT 5";
$obj1 = getJSONFromSql($query);
$query ="SELECT nombre,costo,cantidad,fecha_caducidad,DATEDIFF(now(),fecha_caducidad) dias_caducados,IF(`en_almacen`=1,'Si','No') AS enAlmacen FROM `ingrediente` ORDER BY DATEDIFF(now(),fecha_caducidad) DESC LIMIT 25";

$obj2 = getJSONFromSql($query);

// ingredientes que tengan de menos de 15 dias para caducar
$query ="SELECT nombre,`fecha_caducidad`,DATEDIFF(fecha_caducidad,now()) AS dias_para_caducar,costo,cantidad,IF(`en_almacen`=1,'Si','No') AS enAlmacen FROM `ingrediente` WHERE DATEDIFF(fecha_caducidad,now())>0 AND DATEDIFF(fecha_caducidad,now())<=15 ORDER BY DATEDIFF(fecha_caducidad,now()) DESC";
 
$obj3 = getJSONFromSql($query);





echo  '{"plots":'.$obj1.',"table":'.$obj2.',"table_a_caducar":'.$obj3.'}';

?>