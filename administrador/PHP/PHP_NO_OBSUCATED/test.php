<?php

include ('functions.php');


$query ="SELECT * FROM `tbcuenta` INNER JOIN tbfacturas ON tbcuenta.idcuenta = tbfacturas.idcuenta WHERE tbfacturas.idcliente=2 AND tbfacturas.facturado=0";
$result = execResultSet($query);

$total_totales = 0;
$platillos = '';
foreach($result as $register)
{
	$a =explode(':', $register['platillos']);
	foreach($a as $producto)
	{
		if(trim($producto)!='')
		{
			$platillos=$platillos.$producto.":";
			echo $producto."<br>";
		}
		
	}

}
echo $platillos;

$a = array_count_values(explode(":",$platillos));
$subtotal = 0;
foreach($a as $idPlatillo => $cantidad)
{
	
	if(trim($idPlatillo)!=''&&trim($cantidad)!='')
	{echo "<tr>";
		$query = "SELECT nombre,precio FROM tbplatillos WHERE idplatillo=$idPlatillo";
		$result = execResultSet($query);
		$nombre = $result[0]['nombre'];
		$precio = $result[0]['precio'];
		$valor_unitario = $precio/1.16;
		$valor_unitario = number_format($valor_unitario,4);
		$importe = $cantidad*$valor_unitario;
		$importe = number_format($importe,4);
		echo "<td>$nombre</td>";
		echo "<td>$$valor_unitario</td>";
		echo "<td>$cantidad</td>";
		echo "<td>$importe</td>";
		$subtotal+=$importe;
		
		
		echo "</tr>";	
	}
	
}

// imprimir subtotal
echo "
	 <thead>
      <tr>
        <th></th>
        <th></th>
			<th></th>
			<th>Subtotal</th>
           
      </tr>
    </thead>";
echo "<tr><td></td> <td></td> <td></td> </td>$subtotal</td> </tr>";
//imprimit total
echo "
	 <thead>
      <tr>
        <th></th>
        <th></th>
			<th></th>
			<th>Total</th>
           
      </tr>
    </thead>";
echo "<tr><td></td> <td></td> <td></td> </td>".number_format($subtotal+$subtotal*0.16,4)."</td> </tr>";


echo "</tbody>";


?>