<?php
include ("functions.php");
$search = $_POST['query'];
$search = htmlspecialchars($search);
$fail = $search;
$search= strtoupper($search);

$query = "SELECT COUNT(*) as count FROM tbclientes WHERE nombre LIKE '%$search%' OR rfc LIKE '%$search%'";

$check = execResultSet($query);
if($check[0]['count']==0)
{
echo "No se ha encontrado cliente '$fail'";	
}
else
{

echo "<thead>
      <tr>
        <th>Nombre</th>
        <th>RFC</th>
		<th>Opciones</th>
      </tr>
    </thead>
    <tbody>";

	$query = "SELECT nombre,rfc,idcliente FROM tbclientes WHERE nombre LIKE '%$search%' OR rfc LIKE '%$search%'";
	$result = execResultSet($query);
	foreach($result as $value)
	{
		$nombre = $value['nombre'];
		$rfc = $value['rfc'];
		$id = $value['idcliente'];
		
		echo "<tr>";
		echo "<td>$nombre</td>";
		echo "<td>$rfc</td>";
		echo "<td> <button class=\"btn btn-primary\" onclick='fillFormClient($id)'>Seleccionar</button>  
		
		<button class=\"btn btn-danger\" onclick='eliminarCliente($id)'>Eliminar</button>
		 </td>";
		
		echo "</tr>";
		
		
	}
	echo "</tbody>";


}



?>