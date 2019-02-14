<?php
include('functions.php');


$query = $_POST['query'];
if ($resultset = getSQLResultSet($query)) {
	
    while($obj = mysqli_fetch_object($resultset)) {

        $var[] = $obj;

    }

    echo '{"response":'.json_encode($var).'}';

    
    
	
	
	
	
	
	
    
}

?>