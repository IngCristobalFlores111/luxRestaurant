<?php
    
function Verificar($rol)
    {
	if($_SESSION["rol"]=="administrador" && $_SESSION["entrada"]==1)
	{
	}
	else
	{
		if($_SESSION["entrada"] != 1 || strcmp($_SESSION["rol"],$rol) != 0)
		
		{
			header("Location: ../login/Session.html");
			
		}
		
	}
		

       
        
 
    }
    
function logOut($entrada)
    {
        //true
        if($_SESSION["entrada"] == $entrada)
        {
            header("Location: Session.html");
            $_SESSION["entrada"] = false;
            $_SESSION["rol"] = "";
            $_SESSION["user"] = "";
            $_SESSION["nombre"] = "";
            
        }
        
    }
   
?>


