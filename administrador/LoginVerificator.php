<?php
session_start();
if(isset($_SESSION['usr'])){
    $token = $_SESSION['token'];
    include_once("PHP/new/functions.php");
    $sql = createMysqliConnection();
    $result = $sql->get_bind_results("SELECT * FROM tbusuarios WHERE iduser=? AND authtoken=?",array("is",$_SESSION['usr']['idusr'],$token));

    if(count($result)==0){

        header("Location: ../login/Session.html");
        exit();
    }
    $result = $result[0];
    if($result['idTipo']!=1){
        header("Location: ../login/Session.html");
        exit();
    }


}else{
    header("Location: ../login/Session.html");
    exit();
}


?>


