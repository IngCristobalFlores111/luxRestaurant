<?php

include ('functions.php');
$idCuenta = $_POST['idCuenta'];
$idCliente =$_POST['idCliente'];

$query ="DELETE FROM tbfacturas WHERE idcuenta=$idCuenta AND idcliente=$idCliente";
ejecutarSQLCommand($query);


$check = execResultSet("SELECT COUNT(*) as count FROM `tbcuenta` INNER JOIN tbfacturas ON tbcuenta.idcuenta = tbfacturas.idcuenta WHERE tbfacturas.idcliente=$idCliente");

if($check[0]['count']==0)
{
	echo "";
	
}
else
{
	
	$query ="SELECT * FROM `tbcuenta` INNER JOIN tbfacturas ON tbcuenta.idcuenta = tbfacturas.idcuenta WHERE tbfacturas.idcliente=$idCliente";



	$result = execResultSet($query);

	echo "
    <thead>
      <tr>
        <th>Mesa</th>
        <th>Platillos</th>
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
		
		echo "<tr>";
		echo "<td>$mesa</td>";
		echo "<td>$PlatillosFinal</td>";
		echo "<td>$$total</td>";
		echo "<td><button class=\"btn btn-primary\" onclick='eliminarCuentaListed($id)'>Eliminar</button></td>";
		
		$total_totales +=$total;
		
		
		
		




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
?>