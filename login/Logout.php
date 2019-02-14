<?php
    

session_start();
    
    
    if(isset($_POST["logout"]))
    {
    
    $logout=$_POST["logout"];
    
    if($logout =="true")
    {
        $_SESSION["entrada"] = false;
        $_SESSION["rol"] = "";
        $_SESSION["nombre"] = "";

        header("Location: Session.html");
        
    }
    
    }
    else
    {
      echo"no ex¡ste logout";
    }
    
    
    
    
    
?>