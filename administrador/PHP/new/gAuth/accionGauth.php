<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(isset($_GET['accion'])){
    include_once("../functions.php");
    $sql = createMysqliConnection();
    switch($_GET['accion']){
        case "checkUsr":
       // session_start();
        
        $g_id = $_GET['g_id'];
  

        $query="SELECT iduser FROM tbusuarios WHERE g_id = ?";
        $usr = $sql->get_bind_results($query,array("s",$g_id));
        if(count($usr)>0){
            $respuesta['msg']="Usuario ya existe registrado en la base de datos";
            $respuesta['status'] =1;
        }else{
            $respuesta['msg']="Usuario nuevo";
            $respuesta['status'] =2;
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT));
        
  
        break;
        case "registrarUsuario":
        $usr = $_POST['usr'];
        $nombre = $usr['name']['givenName']." ".$usr['name']['familyName'];
        $userName = $usr['displayName'];
        $tipo =$usr['idTipo'];
        $g_id = $usr['id'];
        $img = $usr['image']['url'];
        $email= $usr['emails'][0]['value'];
        $query="INSERT INTO `tbusuarios`(email,img,`nombre`, `user`, 
        `idTipo`, `estatus`,`g_id`) 
        VALUES (?,?,?,?,?,?,?);";
        $sql->execQueryBinders($query,array("ssssiis",$email,$img,$nombre,$userName,
        $tipo,'1',$g_id));
        $errores = $sql->getErrorLog();
        $respuesta = array();
        if(count($errores)>0){
            $respuesta['exito'] = false;
            $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
        }else{
            $respuesta['exito'] = true;
            $respuesta['idUsr'] = $sql->getLastId();
        }
        print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


        break;

    }

}



?>