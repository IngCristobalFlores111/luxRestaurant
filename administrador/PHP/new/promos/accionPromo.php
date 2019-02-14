<?php
if(isset($_GET['accion'])){
    include "../functions.php";
   $sql  = createMysqliConnection();
   switch($_GET['accion']){
       case "altaPromo":
       $postdata = file_get_contents("php://input");
       $request = json_decode($postdata);
       $descuento = (float)$request->descuento;
       $descuento = $descuento/100;
       $descuento = round($descuento,2);
       $s = strtotime($request->fecha_inicio);
       
       $fecha_inicio = date("Y-m-d",$s);

       $s = strtotime($request->fecha_fin);
       
       $fecha_fin = date("Y-m-d",$s);
       
      $query="INSERT INTO `tbpromociones`(`idPlatillo`, 
      `descripcion`, `cantidad`, 
      `descuento`, `fecha_inicio`, 
      `fecha_fin`, `idFrecuencia`, `activo`,cantidad_promocion) 
      VALUES (?,?,?,?,?,?,?,?,?);";
       $sql->execQueryBinders($query,array("isidssiii",
    $request->platillo->id,$request->descripcion,
    $request->cantidad,
    $descuento,$fecha_inicio,
    $fecha_fin,$request->frecuencia,
    $request->activo,$request->cantidad_promocion
     ));
     $errores = $sql->getErrorLog();
     $respuesta = array();
     if(count($errores)>0){
         $respuesta['exito'] = false;
         $respuesta['errores'] = json_encode($errores,JSON_UNESCAPED_UNICODE);
     }else{
         $respuesta['exito'] = true;
         $respuesta['idPromo'] = $sql->getLastId();
     }
     print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));


       break;
       case "modificarPromo":
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
         $query="UPDATE `tbpromociones` SET descripcion = ?,
         cantidad = ?,descuento = ?,fecha_inicio = ?,
         fecha_fin = ?,idFrecuencia = ?,activo = ?,cantidad_promocion= ? 
         WHERE idPromocion =?";
         $sql->execQueryBinders($query,array("sidssiiii",
          $request->descripcion,$request->cantidad,
          $request->descuento,$request->fecha_inicio,
          $request->fecha_fin,$request->frecuencia,
          $request->activo,$request->cantidad_promocion,$request->id
        ));
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
         case "eliminarPromo":
         $postdata = file_get_contents("php://input");
         $request = json_decode($postdata);
         $query="DELETE FROM `tbpromociones` WHERE idPromocion = ?";
         $sql->execQueryBinders($query,array("i",$request->id));
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
