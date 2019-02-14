<?php
if(isset($_GET['accion'])){
    include ("../../../administrador/PHP/new/functions.php");
    $sql= createMysqliConnection();
    switch($_GET['accion']){
        case "ingresarRating":
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
         $query ="INSERT INTO `tb_ticket_rating`
         (`estrellas`, `comentario`, `fecha`) 
         VALUES(?,?,NOW());";
         $sql->execQueryBinders($query,array("is",$request->estrellas,$request->comentario));
         $idRating= $sql->getLastId();
         $query="INSERT INTO `tb_platillo_ticket_rating`
         (`idPlatillo`, `idCuenta`, `idRating`) 
         VALUES(?,?,?)";
         $sql->execQueryBinders($query,array("iii",$request->idPlatillo,$request->idTicket,$idRating));
         $errores = $sql->getErrorLog();
         $respuesta = array();
         if(count($errores)>0){
             $respuesta['exito'] = false;
             $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
         }else{
             $respuesta['exito'] = true;
         }
         print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


        break;

    }

}