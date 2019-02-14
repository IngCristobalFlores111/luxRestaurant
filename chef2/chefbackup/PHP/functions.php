<?php 


function SegundosLeido($seconds)
{
	/*** return value ***/
	$ret = "";

	/*** get the hours ***/
	$hours = intval(intval($seconds) / 3600);
	if($hours > 0)
	{
		$ret .= "$hours Horas ";
	}
	/*** get the minutes ***/
	$minutes = bcmod((intval($seconds) / 60),60);
	if($hours > 0 || $minutes > 0)
	{
		$ret .= "$minutes Minutos ";
	}
	
	/*** get the seconds ***/
	$seconds = bcmod(intval($seconds),60);
	$ret .= "$seconds Segundos";

	return $ret;
}
function ejecutarSQLCommand($commando){
 
	$mysqli = new mysqli("localhost", "restaurantUser", "RD47yBh..j", "dbrestaurante");
$mysqli->set_charset("utf8");
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if ( $mysqli->multi_query($commando)) {
     if ($resultset = $mysqli->store_result()) {
    	while ($row = $resultset->fetch_array(MYSQLI_BOTH)) {
    		
    	}
    	$resultset->free();
     }
    
   
}



$mysqli->close();
}

function getSQLResultSet($commando){
 
 
	$mysqli = new mysqli("localhost", "restaurantUser", "RD47yBh..j", "dbrestaurante");
$mysqli->set_charset("utf8");
/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if ( $mysqli->multi_query($commando)) {
	return $mysqli->store_result();
	
     
    
   
}



$mysqli->close();
}


function execResultSet($query)
{
	$var = null;
	if ($resultset = getSQLResultSet($query)) {
		
		while($obj = mysqli_fetch_object($resultset)) {

			$var[] = $obj;

		}
		
			$l = json_encode($var);
			$regs = json_decode($l,true);
			return $regs;
		
		
	}
}

?>
