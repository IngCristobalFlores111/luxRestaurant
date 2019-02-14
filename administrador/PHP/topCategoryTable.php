<?php
error_reporting(0);

include ("functions.php");
$categoria = $_POST['category'];
if($categoria=='0')
{
	$query = "SELECT tbplatillos.nombre,SUM(cantidad) as cantidad,SUM(tbventaplatillos.total) AS total FROM tbventaplatillos 
INNER JOIN tbplatillos ON tbplatillos.idplatillo=tbventaplatillos.idplatillo GROUP BY
tbplatillos.nombre ORDER BY cantidad DESC LIMIT 20";
}
else
{

	$query = "SELECT tbplatillos.nombre,SUM(cantidad) as cantidad,SUM(tbventaplatillos.total) AS total FROM tbventaplatillos 
INNER JOIN tbplatillos ON tbplatillos.idplatillo=tbventaplatillos.idplatillo WHERE tbplatillos.categoria ='$categoria' 
GROUP BY tbplatillos.nombre ORDER BY cantidad DESC LIMIT 20";
}
$results = execResultSet($query);
echo "<thead>
      <tr>
	<th>Numero</th>
        <th>Producto</th>
        <th>Cantidad</th>
		<th>Total Vendido</th>
      </tr>
    </thead> <tbody>";
$cout = 1;
foreach($results as $top)
{
	$nombre = $top['nombre'];
	$cantidad = $top['cantidad'];
	$total = $top['total'];
	echo " <tr>
	<td>$cout</td>
        <td>$nombre</td>
        <td>$cantidad</td>
		<td>$$total</td>
      </tr>";
	
	$cout++;
}
echo "</tbody>";

?>