<?php
include ('functions.php');
$idCliente = $_POST['idCliente'];

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
        <th>Nombre</th>
        <th>Valor unitario</th>
	<th>Cantidad</th>
	<th>Importe</th>

           
      </tr>
    </thead>";
	echo "<tbody>";
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
			}
			
		}

	}
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
	$total = number_format($subtotal*0.16 +$subtotal,4);
	$IVA =number_format( $subtotal*0.16,4);
	echo "<thead><tr><th>Subtotal</th><th style=\"color:red\">$$subtotal</th></tr>
	
	<tr><th>IVA</th><th style=\"color:red\">$$IVA</th></tr>
	<tr><th>Total</th><th style=\"color:red\">$$total</th></tr>

	
	</thead>";

	echo "</tbody>";

	
}


?>