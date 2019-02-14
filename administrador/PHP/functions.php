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

	$mysqli = new mysqli("localhost", "dbRestaurantUsr", "restUsr111@", "dbbbq");
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


	$mysqli = new mysqli("localhost", "dbRestaurantUsr", "restUsr111@", "dbbbq");

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

	$query = 'SELECT nombre,idplatillo AS id FROM tbplatillos';
	$result = execResultSet($query);
    return json_encode($result,JSON_UNESCAPED_UNICODE|JSON_HEX_APOS|JSON_HEX_QUOT);



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

        while($obj = mysqli_fetch_assoc($resultset)) {

            $var[] = $obj;

        }


        return $var;

    }
}
function obtenerTotalCuenta($idOrder)
{

    $query = "SELECT nomplatillo FROM tbpedidos WHERE idorden=$idOrder";  // buscar los ids de los pedidos en esa cuenta
    $result = execResultSet($query);
    $total = 0;
    foreach($result as $platillo)
    {

        $plat = $platillo['nomplatillo'];
        $query = "SELECT precio FROM tbplatillos WHERE nombre='$plat'";
        $res = execResultSet($query);
        foreach($res as $price)  // iterar precios de platillos
        {
            $total+=$price['precio'];




        }

    }
    return $total;
}

function obtenerTotalPorFecha($date)
{
    $query = "SELECT SUM(total) AS total FROM tbventaplatillos WHERE fecha='$date'";
    $total = execResultSet($query);

    return $total[0]['total'];



}
function obtenerCostoTotalPorFecha($date)
{
    $query = "SELECT SUM(costo) AS costo FROM tbventaplatillos WHERE fecha='$date'";
    $costo = execResultSet($query);

    return $costo[0]['costo'];



}
function obtenerGananciaTotalPorFecha($date)
{
    $query = "SELECT SUM(ganancia) AS ganancias FROM tbventaplatillos WHERE fecha='$date'";
    $ganancias = execResultSet($query);

    return $ganancias[0]['ganancias'];



}
function actualizarVentasPlatillos($idCuenta)
{
    $query ="SELECT platillos,fecha FROM tbcuenta WHERE idcuenta=$idCuenta";

    $results = execResultSet($query);
    $fecha = $results[0]['fecha'];

    $platillos = explode(":",$results[0]['platillos']);
    $a = array_count_values($platillos);

    foreach ($a  as $platillo => $cantidad)
    {

        $platillo= trim($platillo);  // id platillo
        $cantidad = trim($cantidad); // numero de veces que se vendio ese platillo
        $count  =execResultSet("SELECT COUNT(`idplatillo`) AS res FROM tbventaplatillos WHERE `fecha`='$fecha' AND `idplatillo`=$platillo");


        if($count[0]['res']>0) // ya se han vendido platillo por lo tanto actualiza la tabla de tbventaplatillos
        {

            $query = "SELECT cantidad FROM tbventaplatillos WHERE idplatillo=$platillo AND fecha='$fecha'";

            $prev_cant = execResultSet($query);
            $cant = $prev_cant[0]['cantidad'];
            $cant+=$cantidad; // nueva cantidad

            $query ="SELECT precio,costo FROM tbplatillos WHERE idplatillo=$platillo";
            $results =execResultSet($query);
            $precio = $results[0]['precio'];
            $costo = $results[0]['costo'];
            $total = $precio*$cant;
            $ganancias= $total - ($costo*$cant);
            $costoTotal = $costo*$cant;
            $query ="UPDATE tbventaplatillos SET cantidad=$cant,total=$total,costo=$costoTotal,ganancia=$ganancias WHERE idplatillo=$platillo AND fecha='$fecha'";
            ejecutarSQLCommand($query);




        }
        else //no se ha vendido el platillo
        {



            $query ="SELECT precio,costo FROM tbplatillos WHERE idplatillo=$platillo";
            $results =execResultSet($query);
            $precio = $results[0]['precio'];
            $costo = $results[0]['costo'];
            $total = $precio*$cantidad;
            $ganancias= $total - ($costo*$cantidad);
            $costoTotal = $costo*$cantidad;
            $query ="INSERT INTO `dbbbq`.`tbventaplatillos` (`idplatillo`, `cantidad`, `total`, `costo`, `ganancia`, `fecha`) VALUES ('$platillo', '$cantidad', '$total', '$costoTotal', '$ganancias', '$fecha');";

            ejecutarSQLCommand($query);

        }



    }


}

function updatetbventaplatillos($idplatillo,$cantidad,$fecha)
{
    $raw = execResultSet("SELECT precio,costo FROM tbplatillos WHERE idplatillo=$idplatillo");
    $precio = $raw[0]['precio'];
    $costo = $raw[0]['costo'];

    $total = $precio*$cantidad;
    $costo = $costo*$cantidad;
    $ganancia = $total-$costo;

    $query  ="SELECT COUNT(`idplatillo`) AS res FROM tbventaplatillos WHERE `fecha`='$fecha' AND `idplatillo`=$idplatillo";

    $result = execResultSet($query);
    $count = $result[0]['res'];
    if($count>0)//actualizar tablar de tbventarplatillos
    {
        $query ="UPDATE tbventaplatillos SET total=total + $total, costo = costo+$costo,ganancia=ganancia+$ganancia,cantidad=cantidad+$cantidad WHERE idplatillo=$idplatillo";
        echo $query;
        ejecutarSQLCommand($query);
    }
    else
    {
        $query = "INSERT INTO `dbbbq`.`tbventaplatillos` (`idplatillo`, `cantidad`, `total`, `costo`, `ganancia`, `fecha`) VALUES ('$idplatillo', '$cantidad', '$total', '$costo', '$ganancia', '$fecha');";
    }


}


function combinarCuentas($idCuenta1,$idCuenta2)
{

    $query ="SELECT pedidos FROM tbcuenta WHERE idorden=$idCuenta1";
    $raw = execResultSet($query);
    $pedidos1 = $raw[0]['pedidos'];
    // traer pedidos de la cuenta 2
    $query ="SELECT pedidos,mesa FROM tbcuenta WHERE idorden=$idCuenta2";
    $raw = execResultSet($query);
    $pedidos2 = $raw[0]['pedidos'];
    $mesa = $raw[0]['mesa'];
    // obtenemos los pedidos finales
    $pedidos = $pedidos1.":".$pedidos2;
    // iteramos los pedidos finales para actualizar el nuemro de cuenta de casa uno
    $peds = explode(":",$pedidos);
    $pedidosFinal = '';
    foreach($peds as $pedido)
    {
        $pedido = trim($pedido);
        $pedidosFinal = $pedidosFinal.$pedido.":";
        if($pedido!='')
        {
            $query  = "UPDATE tbpedidos SET idorden=$idCuenta2, mesa = $mesa WHERE idpedido=$pedido";
            ejecutarSQLCommand($query);
        }
    }
    // obtener total de la cuenta
    $total = obtenerTotalCuenta($idCuenta2);
    // eliminar cuenta 1
    $query  ="DELETE FROM tbcuenta WHERE idorden=$idCuenta1";
    ejecutarSQLCommand($query);
    // actualizar cuenta 1
    $query = "UPDATE tbcuenta SET pedidos='$pedidosFinal',total=$total WHERE idorden=$idCuenta2";
    ejecutarSQLCommand($query);


}
function agregarPlatilloOrden($idOrder,$idPlatillo,$comentario,$mesa)
{
    $comentario= trim($comentario);
    //OBTENER NOMBRE Y PRECIO DEL PLATILLO
    $query = "SELECT nombre,precio FROM tbplatillos WHERE idplatillo=$idPlatillo";
    $result = execResultSet($query);
    //obtenemos el nombre y precio del platillo
    $nombre_platillo = $result[0]['nombre'];
    $precio_platillo = $result[0]['precio'];
    // insetamos a tabla de pedidos
    $query ="INSERT INTO tbpedidos (`nomplatillo`, `mesa`, `comentario`, `despachado`, `entregado`, `idorden`) VALUES ('$nombre_platillo','$mesa','$comentario','0','0','$idOrder')";
    ejecutarSQLCommand($query);
    // obtenemos la clave que se genero de pedidos
    $query = "SELECT idpedido FROM tbpedidos WHERE nomplatillo='$nombre_platillo' AND idorden='$idOrder' AND comentario ='$comentario'";
    $raw = execResultSet($query);
    $idPedido = $raw[0]['idpedido'];
    //Actualizar la orden con el nuevo platillo ordenado
    $query = "SELECT pedidos FROM tbcuenta WHERE idorden=$idOrder";
    $rawpedidos = execResultSet($query);
    $pedidos = $rawpedidos[0]['pedidos'];
    $pedidos=trim($pedidos);
    if($pedidos=='')
    {
        $newpedidos = $idPedido;

    }
    else
    {
        $newpedidos = $pedidos.":".$idPedido;
    }
    $query = "UPDATE tbcuenta SET pedidos='$newpedidos', despachado=0 WHERE idorden=$idOrder";
    ejecutarSQLCommand($query);
    // actualizar total de la cuenta

    $query = "SELECT nomplatillo FROM tbpedidos WHERE idorden=$idOrder";  // buscar los ids de los pedidos en esa cuenta
    $result = execResultSet($query);
    $total = 0;
    foreach($result as $platillo)
    {

        $plat = $platillo['nomplatillo'];
        $query = "SELECT precio FROM tbplatillos WHERE nombre='$plat'";
        $res = execResultSet($query);
        foreach($res as $price)  // iterar precios de platillos
        {
            $total+=$price['precio'];




        }

    }

    $query ="UPDATE tbcuenta SET total = $total WHERE idorden=$idOrder";
    ejecutarSQLCommand($query);

}
function eliminarPlatillo($idpedido)
{
    $query  ="SELECT idorden FROM tbpedidos WHERE idpedido=$idpedido";
    $raworden = execResultSet($query);
    $idOrder  = $raworden[0]['idorden'];

    //elimnar de tabla pedidos
    $query  ="DELETE FROM tbpedidos WHERE idpedido=$idpedido";
    ejecutarSQLCommand($query);
    // obtener pedidos de esa cuenta
    $query ="SELECT pedidos FROM tbcuenta WHERE idorden=$idOrder";
    $res = execResultSet($query);
    $pedidos = explode(":",$res[0]['pedidos']);
    $newpedidos ="";

    foreach($pedidos as $pedido)
    {


        if(strcmp($pedido,$idpedido)==0)// si el pedido a eliminar es igual a algun pedido dentro de los pedidos de la cuenta
        {


        }
        else
        {
            $newpedidos=$newpedidos.$pedido.":";
        }



    }
    // actualizar pedidos de la cuenta
    // y total

    $query ="UPDATE tbcuenta SET pedidos='$newpedidos' WHERE idorden=$idOrder";

    ejecutarSQLCommand($query);
    $totalCuenta = obtenerTotalCuenta($idOrder);
    $query ="UPDATE tbcuenta SET total=$totalCuenta WHERE idorden=$idOrder";
    ejecutarSQLCommand($query);
}
function editarComentario($idpedido,$nuevoComentario)
{
    $query ="UPDATE tbpedidos SET comentario='$nuevoComentario' WHERE idpedido=$idpedido";
    ejecutarSQLCommand($query);



}
function editarMesa($idcuenta,$nuevaMesa)
{
    // actualizar en tbcuenta
    $query = "UPDATE tbcuenta SET mesa=$nuevaMesa WHERE idorden=$idcuenta";
    ejecutarSQLCommand($query);

    //actualizar pedidos
    $query = "UPDATE tbpedidos SET mesa=$nuevaMesa WHERE idorden=$idcuenta";
    ejecutarSQLCommand($query);


}
function agregarPlatillo($nombre,$precio,$costo,$descripcion,$categoria,$imagepath)
{
    $query ="INSERT INTO `dbbbq`.`tbplatillos` (`idplatillo`, `nombre`, `precio`, `costo`, `descripcion`, `categoria`, `imagepath`) VALUE (NULL,'$nombre','$precio','$costo','$descripcion','$categoria','$imagepath')";
    ejecutarSQLCommand($query);


}
function crearNuevaCuenta($mesa)
{
    $dt = new DateTime();
    $datetime =  $dt->format('Y-m-d H:i:s');
    $query = "INSERT INTO tbcuenta  (`fecha`, `mesa`) VALUES ('$datetime','$mesa')";
    ejecutarSQLCommand($query);

}


function getPoints($fechaFrom,$fechaTo,$platillo,$opcion)
{// opcion = 1 ->ganancias
    // opcion = 0 -> total
    //obtenerTotalPorFecha
    $query = "SELECT total,ganancia FROM `tbcuenta` WHERE `fecha` BETWEEN '$fechaFrom 00:00:00' AND '$fechaTo 00:00:00'";
    $result = execResultSet($query);
    $output="";
    foreach($result as $value)
    {
        if($opcion==1)
        {
            $output=$output.$value['ganancia'].":";

        }
        if($opcion==0)
        {
            $output=$output.$value['total'].":";

        }


    }
    echo $output;



}

function getTotalOrderByPlatillo($idOrder,$platilloNom)  //devuelve el total de un solo platillo en una orden
{
    $total = 0;
    $query = "SELECT pedidos FROM tbcuenta WHERE idorden=$idOrder";
    $result = execResultSet($query);
    $RAWpedidos = $result[0]['pedidos'];
    $pedidos = explode(":",$RAWpedidos);
    foreach($pedidos as $pedido)
    {
        $pedido = trim($pedido);
        if($pedido!='')
        {
            $query = "SELECT nomplatillo FROM tbpedidos WHERE idpedido=$pedido";
            $result = execResultSet($query);
            $platillo = $result[0]['nomplatillo'];
            if($platillo==$platilloNom)
            {
                $query = "SELECT precio,costo FROM tbplatillos WHERE nombre='$platillo'";
                $result = execResultSet($query);
                $precio = $result[0]['precio'];
                $total+=$precio;
            }

        }


    }
    return $total;


}
function getPointsPlatilloTotales($dateFrom,$dateTo,$platillo)
{

    $query ="SELECT total FROM tbventaplatillos WHERE idplatillo=$platillo AND fecha BETWEEN '$dateFrom' AND '$dateTo'";
    $result = execResultSet($query);
    $output = '';
    foreach($result as $total)
    {
        $output=$output.$total['total'].":";


    }

    return $output;
}
function getPointsPlatilloCostos($dateFrom,$dateTo,$platillo)
{

    $query ="SELECT costo FROM tbventaplatillos WHERE idplatillo=$platillo AND fecha BETWEEN '$dateFrom' AND '$dateTo'";
    $result = execResultSet($query);
    $output = '';
    foreach($result as $total)
    {
        $output=$output.$total['costo'].":";


    }

    return $output;
}
function getPointsPlatillGanancias($dateFrom,$dateTo,$platillo)
{

    $query ="SELECT ganancia FROM tbventaplatillos WHERE idplatillo=$platillo AND fecha BETWEEN '$dateFrom' AND '$dateTo'";
    $result = execResultSet($query);
    $output = '';
    foreach($result as $total)
    {
        $output=$output.$total['ganancia'].":";


    }

    return $output;
}



function getPointsTotales($dateFrom,$dateTo)
{

    $query ="SELECT `idplatillo`, SUM(`total`) AS total
FROM tbventaplatillos
WHERE fecha BETWEEN '$dateFrom' AND '$dateTo'
GROUP BY fecha;";
    $result = execResultSet($query);
    $output = '';
    foreach($result as $total)
    {
        $output=$output.$total['total'].":";


    }

    return $output;
}
function getPointsCostos($dateFrom,$dateTo)
{

    $query ="SELECT `idplatillo`, SUM(`costo`) AS costo
FROM tbventaplatillos
WHERE fecha BETWEEN '$dateFrom' AND '$dateTo'
GROUP BY fecha;";
    $result = execResultSet($query);
    $output = '';
    foreach($result as $total)
    {
        $output=$output.$total['costo'].":";


    }

    return $output;
}
function getPointsGanancias($dateFrom,$dateTo)
{

    $query ="SELECT `idplatillo`, SUM(`ganancia`) AS ganancia
FROM tbventaplatillos
WHERE fecha BETWEEN '$dateFrom' AND '$dateTo'
GROUP BY fecha;";
    $result = execResultSet($query);
    $output = '';
    foreach($result as $total)
    {
        $output=$output.$total['ganancia'].":";


    }

    return $output;
}
function updateCuentasFacturas($idCliente)
{

	$check = execResultSet("SELECT COUNT(*) AS count FROM `tbcuenta` INNER JOIN tbfacturas ON tbcuenta.idcuenta = tbfacturas.idcuenta WHERE tbfacturas.idcliente=$idCliente AND tbfacturas.facturado=0");
	if($check[0]['count']==0)
	{
		echo "";
	}
	else
	{


		$query ="SELECT * FROM `tbcuenta` INNER JOIN tbfacturas ON tbcuenta.idcuenta = tbfacturas.idcuenta WHERE tbfacturas.idcliente=$idCliente AND tbfacturas.facturado=0";



		$result = execResultSet($query);

		echo "
    <thead>
      <tr>
        <th>Mesa</th>
        <th>Platillos</th>
		<th>Descuento</th>
			<th>Total</th>
			<th>Opciones</th>

      </tr>
    </thead>";
		echo "<tbody>";
		$total_totales = 0;

		foreach($result as $register)
		{

			$id = $register['idcuenta'];
			$mesa = $register['idmesa'];
			$raw = $register['platillos'];
			$descuento =  $register['descuento'];
			$total_cuenta = $register['total'];


			$platillos = explode(":",$raw);
			$out_platillos = '';
			$total =0;
			$costo = 0;
			$ganancia = 0;

			foreach($platillos as $platillo)
			{

				if(trim($platillo)!='')
				{
					$query = "SELECT nombre,precio,costo FROM tbplatillos WHERE idplatillo=$platillo";
					$result = execResultSet($query);
					$out_platillos = $out_platillos.$result[0]['nombre'].":";
					$total+=$result[0]['precio'];
					$costo+=$result[0]['costo'];
				}
			}
			$keyPairPlatillos = array_count_values(explode(":",$out_platillos));
			$PlatillosFinal ='';
			foreach($keyPairPlatillos as $nombre => $cantidad)
			{
				if(trim($nombre)!=''&&trim($cantidad)!='')
                    $PlatillosFinal=$PlatillosFinal.$nombre." x".$cantidad."<br>";
			}

			$extra = $total_cuenta - $total + $descuento;
			if($extra>0)
			{
				$PlatillosFinal= $PlatillosFinal."Extra: $$extra";
			}


			echo "<tr>";
			echo "<td>$mesa</td>";
			echo "<td>$PlatillosFinal</td>";
			echo "<td>$$descuento</td>";
			echo "<td>$$total_cuenta</td>";
			echo "<td><button class=\"btn btn-primary\" onclick='eliminarCuentaListed($id)'>Eliminar</button></td>";

			$total_totales +=$total_cuenta;








			echo "</tr>";

		}
		echo "
	 <thead>
      <tr>
        <th></th>
        <th></th>
			<th>Total</th>
			<th></th>

      </tr>
    </thead>";
		echo "<tr><td></td> <td></td> <td>$$total_totales</td> </td></td> </tr>";


		echo "</tbody>";


	}
}


?>