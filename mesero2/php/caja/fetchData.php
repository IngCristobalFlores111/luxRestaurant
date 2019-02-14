<?php
if(isset($_GET['accion'])){
    include("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "init":
        session_start();
        $idUsr = $_SESSION['usr']['idusr'];

        $query="SELECT * FROM `tbcaja` WHERE `idUsr` = ?";
        $result = $sql->get_bind_results($query,array("i",$idUsr));
        $respuesta = array();
        if(count($result)==0){
         $query="SELECT * FROM `tbcaja` WHERE `idUsr` is NULL AND `fecha_fin` is NULL;";
        $result = $sql->executeQuery($query);
        $respuesta['opcion'] = 0;
        }else{
        $respuesta['opcion'] = 1;
        $result = $result[0];
        }
        $respuesta['cajas'] = $result;
        $_SESSION['usr']['caja'] = $result;
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_NUMERIC_CHECK));
        
        
     
        break;

    }


}


?>