<?php 
function validate_data($usr,$pass)
{
	$result = execResultSet("SELECT IF( (SELECT COUNT(usr) FROM usuarios WHERE usr = '$usr' AND pswd = '$pass') > 0 ,'1','0') AS access");


	if($result[0]['access']==1){
		ejecutarSQLCommand("UPDATE usuarios SET access=1,blocked=0 WHERE usr ='$usr' AND pswd='$pass'");
		 $_SESSION['trials'] = 0;
		

	}
	else{
		
		$_SESSION['trials']  = $_SESSION['trials'] + 1;
	}
	echo $result[0]['access'];

		
	
}


function ejecutarSQLCommand($commando){
 
	$mysqli = new mysqli("localhost", "root", "cuanticoemc", "dbrestaurante");
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
 
 
	$mysqli = new mysqli("localhost", "root", "cuanticoemc", "dbrestaurante");

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
function getJSONResultSQL($query)
{
	$var = null;
	if ($resultset = getSQLResultSet($query)) {
		
		while($obj = mysqli_fetch_object($resultset)) {

			$var[] = $obj;

		}

		return '{"result":'.json_encode($var).'}';

	}
	
}
function getJSONFromSql($query)
{
	$var = null;
	if ($resultset = getSQLResultSet($query)) {
		
		while($obj = mysqli_fetch_object($resultset)) {

			$var[] = $obj;

		}

		return json_encode($var);

	}
	
	
}
function obtenerNomPlatillos()
{
	
	$query = 'SELECT nombre FROM tbplatillos';
	$result = execResultSet($query);
	
	if(is_null($result))
	{
		echo "no hay productos que mostrar";
		exit();
	}
	else{
		
		$out = '';
	foreach($result as $platillo)
		{
			if(trim($platillo['nombre'])!='')
			{
				$out = $out.$platillo['nombre'].":";
			}
			
		}	
		
	}
	echo $out; 
	
	
	
}
function getSenderoData($idSendero)
{
	
	$obj1 = getJSONFromSql("SELECT * FROM `senderos` WHERE id_sendero=$idSendero");
	$obj2 = getJSONFromSql("SELECT `path` FROM `imagenes_senderos` WHERE id_sendero=$idSendero");
	$obj3 = getJSONFromSql("SELECT `url` FROM `videos_senderos` WHERE `id_sendero`=$idSendero");
	
	return '{"info":'.$obj1.',"imagenes":'.$obj2.',"videos":'.$obj3.'}';

	
	
}

function execResultSet($query)
{
	//$var[] = null;
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