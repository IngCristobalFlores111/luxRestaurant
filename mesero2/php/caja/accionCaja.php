<?php
if(isset($_GET['accion'])){
    include("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "initCaja":
        session_start();
        $idUsr = $_SESSION['usr']['idusr'];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $query="UPDATE `tbcaja` SET `idUsr` = ?,
        `total_inicio` = ?,`fecha_inicio` = NOW(),total_fin=? WHERE `idCaja` = ?";
        $sql->execQueryBinders($query ,array("iddi",$idUsr,$request->total,$request->total,$request->idCaja));
        $respuesta =array();
        $errores = $sql->getErrorLog();
        if(count($errores)>0){
            $respuesta['exito'] = false;
            $respuesta['errores'] = $errores;
        }else{
            $respuesta['exito'] = true;   
            $_SESSION['usr']['caja'] =array("idCaja"=>$request->idCaja,
            "nombre"=>"caja nueva camiada","fecha_inicio"=>date("Y-m-d"),fecha_fin=>"",
            "total_inicio"=>$request->total,"total_fin"=>$request->total,
            "idUsr"=>$idUsr
        );
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

     
        break;
        case "actualizarTotalActual":
        session_start();
        $idUsr = $_SESSION['usr']['idusr'];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $query="UPDATE `tbcaja` SET `total_fin` = ? WHERE `idCaja` = ?";
        $sql->execQueryBinders($query ,array("di",$request->total,$request->idCaja));
        $respuesta =array();
        $errores = $sql->getErrorLog();
        if(count($errores)>0){
            $respuesta['exito'] = false;
            $respuesta['errores'] = $errores;
        }else{
            $respuesta['exito'] = true;   
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
 

        break;
        case "corteCaja":
        session_start();
        $idUsr = $_SESSION['usr']['idusr'];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
   
        $query="INSERT INTO `tbcaja_historial`(`idCaja`, `fecha_inicio`, 
        `fecha_fin`, `idUsr`, `total_inicio`, `total_fin`) VALUES (?,?,NOW(),?,?,?);";
        $binders =array("isidd",$request->idCaja,
        $request->fecha_inicio,
        $idUsr,$request->total_inicio,
        $request->total_fin);
        $sql->execQueryBinders($query,$binders);
        $respuesta =array();
        $errores = $sql->getErrorLog();
        if(count($errores)>0){
            $respuesta['exito'] = false;
            $respuesta['errores'] = $errores;
        }else{
            $respuesta['exito'] = true;   
            $query="UPDATE `tbcaja` SET `idUsr` =NULL ,`fecha_fin` =NULL,total_fin=0 WHERE `idCaja` = ?";
            $sql->execQueryBinders($query,array("i",$request->idCaja));
            $query="SELECT * FROM `tbcaja` WHERE `idUsr` is NULL AND `fecha_fin` is NULL;";
            $result = $sql->executeQuery($query);
            $respuesta['cajas'] = $result;
          
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


        break;

    }


}


?>