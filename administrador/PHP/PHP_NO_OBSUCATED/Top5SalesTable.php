<?php
include ("functions.php");
$query = "SELECT tbplatillos.nombre,SUM(cantidad) as cantidad,SUM(tbventaplatillos.total) AS total FROM tbventaplatillos 
INNER JOIN tbplatillos ON tbplatillos.idplatillo=tbventaplatillos.idplatillo GROUP BY
tbplatillos.nombre ORDER BY cantidad DESC LIMIT 5";
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
echo "</tbody>*";


$cout=1;
$query = "SELECT tbplatillos.nombre,SUM(cantidad) as cantidad,SUM(tbventaplatillos.total) AS total FROM tbventaplatillos 
INNER JOIN tbplatillos ON tbplatillos.idplatillo=tbventaplatillos.idplatillo GROUP BY
tbplatillos.nombre ORDER BY cantidad ASC LIMIT 5";
$results = execResultSet($query);
echo "<thead>
      <tr>
	<th>Numero</th>
        <th>Producto</th>
        <th>Cantidad</th>
				<th>Total Vendido</th>

      </tr>
    </thead> <tbody>";
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