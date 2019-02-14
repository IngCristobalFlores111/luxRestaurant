<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "initRatings":
       $query="SELECT tbplatillos.nombre AS platillo,tb_ticket_rating.estrellas AS stars,tb_ticket_rating.comentario,tb_ticket_rating.fecha FROM `tb_platillo_ticket_rating` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tb_platillo_ticket_rating.idPlatillo INNER JOIN tb_ticket_rating ON tb_ticket_rating.idRating =tb_platillo_ticket_rating.idRating GROUP BY tbplatillos.idplatillo,tb_platillo_ticket_rating.idCuenta ORDER BY tb_ticket_rating.fecha DESC LIMIT 50";
       $ratings =   $sql->executeQuery($query);
       $query="SELECT idplatillo AS id,nombre FROM `tbplatillos`";
        $platillos =  $sql->executeQuery($query);
        $query="SELECT tbplatillos.nombre AS platillo,tb_ticket_rating.estrellas AS stars,tb_ticket_rating.comentario FROM `tb_platillo_ticket_rating` INNER JOIN tb_ticket_rating ON tb_ticket_rating.idRating = tb_platillo_ticket_rating.idRating INNER JOIN tbplatillos ON tbplatillos.idplatillo = tb_platillo_ticket_rating.idPlatillo GROUP BY tbplatillos.idplatillo ORDER BY tb_ticket_rating.estrellas DESC LIMIT 5";
        $topMas =  $sql->executeQuery($query);
        $query="SELECT tbplatillos.nombre AS platillo,tb_ticket_rating.estrellas AS stars,tb_ticket_rating.comentario FROM `tb_platillo_ticket_rating` INNER JOIN tb_ticket_rating ON tb_ticket_rating.idRating = tb_platillo_ticket_rating.idRating INNER JOIN tbplatillos ON tbplatillos.idplatillo = tb_platillo_ticket_rating.idPlatillo GROUP BY tbplatillos.idplatillo ORDER BY tb_ticket_rating.estrellas ASC LIMIT 5";
        $topMenos =  $sql->executeQuery($query);
        
        
        $output = array("topMenos"=>$topMenos,"topMas"=>$topMas,"platillos"=>$platillos,"ratings"=>$ratings);
       print_r(json_encode($output,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
        break;
        case "buscarRatings":
        $fechaInicio = $sql->filter_input($_GET['fechaInicio']);
        $fechaFin = $sql->filter_input($_GET['fechaFin']);
        $stars = $sql->filter_input($_GET['rating']);
        $platillo =json_decode($_GET['platillo'],true);
        $porRating = $sql->filter_input($_GET['porRating']);
        $extraWhere ="";
        if($porRating=="true"){
            $extraWhere.=" AND tb_ticket_rating.estrellas=$stars ";
        }
        if($platillo!="0"){
            $extraWhere.=" AND tbplatillos.idplatillo= ".$platillo['id']." ";            
        }
        
        $query="SELECT tbplatillos.nombre AS platillo,tb_ticket_rating.estrellas AS stars,tb_ticket_rating.comentario,tb_ticket_rating.fecha FROM `tb_platillo_ticket_rating` INNER JOIN tbplatillos ON tbplatillos.idplatillo = tb_platillo_ticket_rating.idPlatillo INNER JOIN tb_ticket_rating ON tb_ticket_rating.idRating =tb_platillo_ticket_rating.idRating 
        WHERE tb_ticket_rating.fecha BETWEEN ' $fechaInicio' 
        AND '$fechaFin' $extraWhere
        GROUP BY tbplatillos.idplatillo,tb_platillo_ticket_rating.idCuenta ORDER BY tb_ticket_rating.fecha DESC";
        $platillos =  $sql->executeQuery($query);
        print_r(json_encode($platillos,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
        break;
        
    
    }

}

?>