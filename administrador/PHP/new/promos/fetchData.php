<?php
if(isset($_GET['accion'])){
    include "../functions.php";
   $sql  = createMysqliConnection();
   switch($_GET['accion']){
       case "buscarPlatillo":
       $q =$_GET['q'];
       $q = $sql->filter_input($q);
       $query="SELECT nombre,idplatillo AS id,descripcion,precio FROM `tbplatillos` WHERE MATCH(nombre,descripcion) AGAINST ('*".$q."*' IN BOOLEAN MODE)
       LIMIT 20;
       ";
       $results  = $sql->executeQuery($query); 
       print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
       
     break;
     case "frecuencias":
       $query="SELECT * FROM `tbfrecuencias`";
       $results  = $sql->executeQuery($query); 
       print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
       
     break;
     case "promos":
$query="SELECT tbpromociones.cantidad_promocion,tbpromociones.cantidad,tbpromociones.descuento,tbpromociones.activo,tbplatillos.nombre AS nombre_platillo,tbplatillos.precio AS precio_platillo,tbpromociones.idPromocion AS id,tbpromociones.descripcion,tbpromociones.fecha_inicio,tbpromociones.fecha_fin,tbfrecuencias.nombre AS frecuencia, tbpromociones.idFrecuencia FROM `tbpromociones` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbpromociones.idPlatillo INNER JOIN tbfrecuencias ON tbfrecuencias.idFrecuencia = tbpromociones.idFrecuencia";
     $results  = $sql->executeQuery($query); 
      print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
      
     break;
   }

}


?>