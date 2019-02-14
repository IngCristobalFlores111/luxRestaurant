<?php


include('functions.php');
$id = $_POST["id"];
$query="SELECT * FROM tbplatillos WHERE idplatillo=$id";
if ($resultset = getSQLResultSet($query)) {
	
    while($obj = mysqli_fetch_object($resultset)) {

$var[] = $obj;

}

echo '{"platillos":'.json_encode($var).'}';

        
        
	
	
	
	
	
	
    
   }


?>