<?php

error_reporting(0);

include ('functions.php');
$fecha = date("Y-m-d");
$query ="SELECT * FROM tbcuenta WHERE fecha='$fecha' AND despachado=2 OR despachado=3";
$result = execResultSet($query);
echo "<table id=\"tableCuentas\" class=\"table table-hover\">";
if(trim($result[0]['fecha'])=='')
{
	echo "<script> $('#headerFecha').html('No se han Registrado cuentas el dia de hoy $fecha'); </script> ";
}
	else
{
	echo "<script> $('#headerFecha').html('Cuentas del dia de hoy $fecha'); </script> ";

	
	echo "
    <thead>
      <tr>
        <th>Mesa</th>
        <th>Platillos</th>
        <th>Total</th>
         <th>Costo</th>
          <th>Ganancia</th>
           
      </tr>
    </thead>";
	echo "<tbody>";
	$total_totales = 0;
	$total_costos = 0;
	$total_ganancias = 0;

	foreach($result as $register)
	{
		
		$mesa = $register['idmesa'];
		$raw = $register['platillos'];
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
		
		
		$ganancia = $total-$costo;
		echo "<td>$mesa</td>";
		echo "<td>$PlatillosFinal</td>";
		echo "<td>$$total</td>";
		echo "<td>$$costo</td>";

		echo "<td>$$ganancia</td>";
		$total_totales+=$total;
		$total_costos+=$costo;
		$total_ganancias+=$ganancia;
		
		
		
		



		echo "</td>";

		echo "</tr>";

	}


	echo "<thead>
      <tr>
	<th></th>
		<th></th>

        <th>Suma de Totales</th>
        <th>Suma de Costos</th>
        <th>Suma de Ganancias</th>
        
           
      </tr>
    </thead>";
	
	
	
	
	
	echo "<tr>
	<td></td>
	
	<td></td>
	<td><label style='color:red'>$$total_totales</label></td>
		<td><label style='color:red'>$$total_costos</label></td>
	<td><label style='color:red'>$$total_ganancias</label></td>

	</tr>";
	echo  "</tbody>
  ";
}
echo "</table>";
?>