<?php
include ("functions.php");
$id = $_POST['id'];
$query = "SELECT * FROM tbclientes WHERE idcliente=$id";

//echo $query;
if ($resultset = getSQLResultSet($query)) {
	
	while($obj = mysqli_fetch_object($resultset)) {

		$var[] = $obj;

	}

	echo '{"cliente":'.json_encode($var).'}';

	
	
	
	
	
	
	
	
	
}

?>
