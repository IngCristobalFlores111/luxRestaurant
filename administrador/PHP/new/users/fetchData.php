<?php

if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "usuarios":
            $query ="SELECT tbusuarios.idTipo,tbusuarios.iduser AS id,tbusuarios.nombre,tbusuarios.user,tbusuarios.pass,tbtipo_usuario.nombre AS rol FROM `tbusuarios` INNER JOIN tbtipo_usuario ON tbtipo_usuario.idTipo = tbusuarios.idTipo";
           $results =  $sql->executeQuery($query);
           print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
            break;
        case "roles":
            $query = "SELECT `idTipo` AS id,nombre FROM `tbtipo_usuario`";
            $results =  $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));

            break;
            case "usuarioSesiones":
            $query="SELECT tbusuarios.`iduser` AS id,tbusuarios.`nombre`,tbusuarios.`user` AS usuario,tbtipo_usuario.nombre AS rol, tbusuarios_sesiones.fecha_inicio AS inicio,tbusuarios_sesiones.fecha_fin AS fin, CONCAT( FLOOR(HOUR(TIMEDIFF(tbusuarios_sesiones.fecha_fin, tbusuarios_sesiones.fecha_inicio)) / 24), ' dias ', MOD(HOUR(TIMEDIFF(tbusuarios_sesiones.fecha_fin, tbusuarios_sesiones.fecha_inicio)), 24), ' horas ', MINUTE(TIMEDIFF(tbusuarios_sesiones.fecha_fin, tbusuarios_sesiones.fecha_inicio)), ' minutos') AS duracion FROM `tbusuarios_sesiones` INNER JOIN tbusuarios ON tbusuarios.iduser = tbusuarios_sesiones.idUsr INNER JOIN tbtipo_usuario ON tbtipo_usuario.idTipo = tbusuarios.idTipo";
            $results =  $sql->executeQuery($query);
            print_r(json_encode($results,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
            break;
            case "usuarioProductividad":
           $id = $_GET['id'];
           $query="SELECT SUM(total) AS totalUsr FROM tbcuenta WHERE idUsuario=?";
           $result =$sql->get_bind_results($query,array("i",$id));
           $total =0;
          if(count($result)==0 || is_null($result[0]['totalUsr'])){
            $total =0;
          }else{
            $total = $result[0]['totalUsr'];
            
          }
$query="SELECT SUM(TIME_TO_SEC(timediff(`fecha_fin`, `fecha_inicio`))) AS trabajado FROM `tbusuarios_sesiones` WHERE `idUsr`= ?";
           $result2 =$sql->get_bind_results($query,array("i",$id));
           $respuesta['total'] =   $total;
           $respuesta['trabajado'] = $result2[0]['trabajado'];
           print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
            break;
            case "filtrarSesiones":
$query="SELECT tbusuarios.`iduser` AS id,tbusuarios.`nombre`,tbusuarios.`user` AS usuario,tbtipo_usuario.nombre AS rol, tbusuarios_sesiones.fecha_inicio AS inicio,tbusuarios_sesiones.fecha_fin AS fin, CONCAT( FLOOR(HOUR(TIMEDIFF(tbusuarios_sesiones.fecha_fin, tbusuarios_sesiones.fecha_inicio)) / 24), ' dias ', MOD(HOUR(TIMEDIFF(tbusuarios_sesiones.fecha_fin, tbusuarios_sesiones.fecha_inicio)), 24), ' horas ', MINUTE(TIMEDIFF(tbusuarios_sesiones.fecha_fin, tbusuarios_sesiones.fecha_inicio)), ' minutos') AS duracion FROM `tbusuarios_sesiones` INNER JOIN tbusuarios ON tbusuarios.iduser = tbusuarios_sesiones.idUsr INNER JOIN tbtipo_usuario ON tbtipo_usuario.idTipo = tbusuarios.idTipo
WHERE tbusuarios.iduser = ? 
AND (tbusuarios_sesiones.fecha_inicio BETWEEN ? AND ? 
OR tbusuarios_sesiones.fecha_fin BETWEEN ? AND ? )";
           $result =$sql->get_bind_results($query,array("issss",$_GET['idUsr'],$_GET['fechaInicio'],$_GET['fechaFin'],$_GET['fechaInicio'],$_GET['fechaFin']));
           print_r(json_encode($result,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK));
           
           

            break;

    }

}


?>