<?php

include ('functions.php');
error_reporting(0);

$fecha = $_POST['fecha'];


$query ="SELECT * FROM `tbcuenta` WHERE `idcuenta` NOT IN (SELECT idcuenta FROM tbfacturas) AND despachado=2 AND fecha='$fecha'";
$result = execResultSet($query);
$test = trim($result[0]['fecha']);
if($test=='')
{
	echo "No se encontraron cuentas disponibles en esta fecha: $fecha";

}
else
{
	
	echo "
    <thead>
      <tr>
        <th>Mesa</th>
        <th>Platillos</th>
			<th>Total</th>
			<th>Descuento</th>
			<th>Opciones</th>
           
      </tr>
    </thead>";
	echo "<tbody>";
	$total_totales = 0;
	$total_costos = 0;
	$total_ganancias = 0;

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
		
		foreach($platillos as $platillo)
		{
			
			if(trim($platillo)!='')
			{
				$query = "SELECT nombre,precio,costo FROM tbplatillos WHERE idplatillo=$platillo";
				$result = execResultSet($query);
				$out_platillos = $out_platillos.$result[0]['nombre'].":";
				$total+=$result[0]['precio'];
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
	
		echo "<td><button class=\"btn btn-primary\" onclick='addToFactura($id)'>Agregar</button></td>";
		
		
		
		
		
		




		echo "</tr>";

	}

}
echo "</tbody>";
?>

