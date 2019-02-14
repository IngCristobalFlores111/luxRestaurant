<?php

    session_start();

$link = new PDO("mysql:host=localhost; dbname=dbrestaurante","restaurantUser","RD47yBh..j");
    $link->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    try
    
    {
       
    
        $sql = "SELECT user,pass,rol,nombre FROM tbusuarios";
        $stmt = $link->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_NUM);
        
        $Loginfo = $stmt->fetchAll();
    
        $_SESSION["entrada"] = false;
        
        
        if(isset($_POST['user']) && isset($_POST['pass']))
        {
            
            for($i=0; $i < sizeof($Loginfo); $i++)
            {
                if($_POST['user'] == $Loginfo[$i][0] && $_POST['pass'] == $Loginfo[$i][1])
                {
                    
                    $_SESSION["entrada"] = true;
            
                    
                    $_SESSION["rol"] = $Loginfo[$i][2];
            
                    
                    $_SESSION["nombre"] =$Loginfo[$i][3];
                    
                    
                }
                

               
            }//fin de for
            
            if($_SESSION["entrada"] == true && $_SESSION["rol"] == "mesero")
            {
             
                
                echo "Mesero:".$_SESSION["nombre"];
            }
            
            
            else if($_SESSION["entrada"] == true && $_SESSION["rol"] == "chef")
                
            {
              
               echo "Chef:".$_SESSION["nombre"];
            }
            
            else if($_SESSION["entrada"] == true && $_SESSION["rol"] == "administrador")
                
            {

                echo "Administrador:".$_SESSION["nombre"];
            }
            
            else
            {
                
                echo "Invalid";
            }
            
            

        }//fin del if isset de los post
        
        
       
    } //fin del try
    
    
    
    catch(PDOException $e)
    {
        echo 'Message: ' .$e->getMessage();
    }
?>