<?php
error_reporting(0);

include ("functions.php");
$search = $_POST['query'];
$search = str_replace('"',"'",$search);
$search = htmlspecialchars($search,ENT_COMPAT);
$fail = $search;
$search =strtoupper($search);
$query ="SELECT COUNT(*) AS count FROM tbventaplatillos INNER JOIN tbplatillos ON tbplatillos.idplatillo=tbventaplatillos.idplatillo WHERE UCASE(tbplatillos.nombre) LIKE \"%$search%\" ORDER BY cantidad DESC";
$check = execResultSet($query);
if($check[0]['count']==0)
{
	echo "No se encontraron resultados para \"$fail\"";
	
}
else
{


	$query ="SELECT tbplatillos.nombre,SUM(cantidad) as cantidad,SUM(tbventaplatillos.total) AS total FROM tbventaplatillos
 INNER JOIN tbplatillos ON tbplatillos.idplatillo=tbventaplatillos.idplatillo
 WHERE UCASE(tbplatillos.nombre) LIKE \"%$search%\" ORDER BY cantidad DESC";
	$results = execResultSet($query);
	echo "<table class=\"table table-hover\">
    <thead>
      <tr>
        <th>Numero</th>
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
        <td>$count</td>
        <td>$nombre</td>
        <td>$cantidad</td>
		<td>$$total</td>
      </tr>";
		
		$count++;	
	}
	echo "</tbody>
  </table>";


}
?>