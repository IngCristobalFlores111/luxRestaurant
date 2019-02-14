<?php
error_reporting(0);

include ("functions.php");
$search = $_POST['query'];
$search = str_replace('"',"'",$search);
$search = htmlspecialchars($search,ENT_COMPAT);
$fail = $search;
$search =strtoupper($search);
$query ="";
$check = execResultSet($query);



$query ="SELECT tbplatillos.nombre,SUM(tbventaplatillos.cantidad) AS cantidad,SUM(tbventaplatillos.total) as total FROM tbventaplatillos INNER JOIN tbplatillos ON tbplatillos.idplatillo = tbventaplatillos.idplatillo WHERE MATCH(tbplatillos.nombre) AGAINST('*".$search."*' IN BOOLEAN MODE) GROUP BY tbventaplatillos.idplatillo";
	$results = execResultSet($query);
	echo "<table class=\"table table-hover table-striped table-bordered\">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Cantidad</th>
		<th>Total Vendido</th>
      </tr>
    </thead>
    <tbody>";
	$count =1;
	foreach($results as $top)
	{
		$nombre =$top['nombre'];
		$cantidad = $top['cantidad'];
		$total = $top['total'];
		echo "<tr>
        <td>$nombre</td>
        <td>$cantidad</td>
		<td>$$total</td>
      </tr>";
	}
	echo "</tbody>
  </table>";



?>