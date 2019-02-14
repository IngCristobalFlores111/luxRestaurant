<?php
// check timestamps..
date_default_timezone_set("America/Mexico_City");

$respuesta = array();
include_once("../../administrador/PHP/new/functions.php");
$sql = createMysqliConnection();

if(isset($_COOKIE['login-blocked'])){
    if($_COOKIE['login-blocked']){
        $now = time();
        $time_dif = $now- $_COOKIE['time-stamp-failed'];
        if($time_dif>7200){

             setcookie('login-blocked',false, time() + (86400 * 30));
             $idUsr =  $_COOKIE['idUsr'];
             $query ="UPDATE `tbusuarios` SET estatus =1 WHERE iduser = ?";
             $sql->execQueryBinders($query,array("i",$idUsr));

        }else{
            $minutos = round((7200-$time_dif)/60,1);
            $respuesta['exito']= false;
            $respuesta['msg'] ="Demasiados intentos fallidos, intentalo en ".$minutos." minutos.";
            print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


            exit();
        }

     }

    }




$usr = $_POST['usr'];
$pass = $_POST['pass'];

$query ="SELECT * FROM `tbusuarios` WHERE user=? LIMIT 1";
$result =$sql->get_bind_results($query,array("s",$usr));
if(count($result)==0){
    $respuesta['exito']= false;
    $respuesta['msg'] ="Usurio no existe";
    if(isset($_COOKIE['failed-login'])){
        $n = $_COOKIE['failed-login'];
        $n++;
        setcookie('failed-login',$n, time() + (86400 * 30));
        if($_COOKIE['failed-login']>10){
            setcookie('time-stamp-failed',time(),time() + (86400 * 30));
            setcookie('login-blocked',true,time() + (86400 * 30));
            $respuesta['msg'] ="el chor";

        }


    }else{
        setcookie('failed-login',1, time() + (86400 * 30));

    }
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
    exit();
}
$result = $result[0];

if($result['pass']!=$pass){
    $respuesta['exito']= false;
    $respuesta['msg'] ="Constrase�a Incorrecta";
    if(isset($_COOKIE['failed-login'])){
       $n =  $_COOKIE['failed-login'];
       setcookie('failed-login',$n++, time() + (86400 * 30));

        if($_COOKIE['failed-login']>10){
            setcookie('time-stamp-failed',time(),time() + (86400 * 30));
            setcookie('login-blocked',true,time() + (86400 * 30));
            setcookie('idUsr',$result['pass'],time() + (86400 * 30));
            $query ="UPDATE `tbusuarios` SET estatus =0 WHERE iduser=?";
            $sql->execQueryBinders($query,array("i",$result['iduser']));
        }

    }else{
        setcookie('failed-login',1, time() + (86400 * 30));
    }
    print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
    exit();
}

session_start();
$_SESSION['usr'] = array("timestamp"=>date("Y-m-d H:i:s"),"idusr"=>$result['iduser'],"usr"=>$result['user'],"nombre"=>$result['nombre'],"tipo"=>$result['idTipo'],"estatus"=>$result['estatus']);

setcookie('failed-login',0,time() + (86400 * 30));
setcookie('login-blocked',false,time() + (86400 * 30));


$token = md5(uniqid(mt_rand(), true));
$_SESSION['token'] = $token;
$query = "UPDATE `tbusuarios` SET authtoken = ?,timestamp=NOW(),estatus=1 WHERE iduser = ?";
$sql->execQueryBinders($query,array("si",$token,$result['iduser']));
$respuesta['exito']= true;
$respuesta['msg'] ="Usuario logeado exitosamente";
$respuesta['usr'] = array("timestamp"=>date("Y-m-d h:i:s"),"usr"=>$result['user'],"nombre"=>$result['nombre'],"tipo"=>$result['idTipo']);
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));
$query="DELETE FROM `tbusuarios_sesiones` WHERE `fecha_fin` IS NULL AND `idUsr` = ?";
$sql->execQueryBinders($query,array("i",$result['iduser']));

$query="INSERT INTO `tbusuarios_sesiones`(`idUsr`, `fecha_inicio`, `fecha_fin`) VALUES (?,NOW(),NULL);";
$sql->execQueryBinders($query,array("i",$result['iduser']));





?>