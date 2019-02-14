<?php
include ("functions.php");
$check = execResultSet("SELECT COUNT(*) AS count FROM tbusuarios");
if($check[0]['count']==0)
{
echo "<h4>No se han registrado usuarios hasta el momento</h4>";	
}
else
{


	$query ="SELECT * FROM `tbusuarios`";
	$result = execResultSet($query);
	// llenar tablar de usuario s
	echo "<thead>
      <tr>
        <th><label class=\"glyphicon glyphicon-sunglasses\"></label>Nombre</th>
        <th><label class=\"glyphicon glyphicon-briefcase\"></label>Tipo de usuario</th>
        <th><label class=\"glyphicon glyphicon-user\"></label>Usuario</th>
		<th> <label class=\"glyphicon glyphicon-eye-open\"></label>Contrase&ntildea</th>
		<th><label class=\"glyphicon glyphicon-pencil\"></label>Opciones</th>
      </tr>
    </thead>
    <tbody>";

	foreach($result as $user)
	{
		$id = $user['iduser'];
		$nombre = $user['nombre'];
		$rol = $user['rol'];
		$usuario = $user['user'];
		$pass = $user['pass'];
		echo "<tr>
        <td>$nombre</td>
        <td>$rol</td>
        <td>$usuario</td>
		<td>$pass</td>
		<td><button onclick=\"editUsr($id)\" type=\"button\" class=\"btn btn-info\"><span class='glyphicon glyphicon-cog'></span>Editar</button>&nbsp&nbsp
		  <button onclick=\"deleteUsr($id)\" type=\"button\" class=\"btn btn-danger\"><span class='glyphicon glyphicon-remove'></span>Eliminar</button>

		
</td>
      </tr>";

		
	}
	echo"</tbody>";
}
?>