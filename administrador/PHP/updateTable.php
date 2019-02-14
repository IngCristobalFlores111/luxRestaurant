<?php
include ("functions.php");
$Search = $_POST['platillo'];
$Search = htmlspecialchars($Search);
$fail = $Search;
$Search = strtoupper ($Search);
$Search = str_replace('"',"'",$Search);
// check
$check = execResultSet("SELECT COUNT(*) AS count FROM `tbplatillos` WHERE UCASE(nombre) LIKE \"%$Search%\"");
if($check[0]['count']==0)
{
echo "No hay resultados para \"$fail\"";
}
else
{


	$results = execResultSet("SELECT * FROM `tbplatillos` WHERE UCASE(nombre) LIKE '%$Search%'");

	echo "<table class=\"table table-condensed\">
    <thead>
      <tr>
        <th><label class=\"glyphicon glyphicon-tag\"></label>Nombre</th>
        <th><label class=\"glyphicon glyphicon-usd\"></label>Precio</th>
        <th><label class=\"glyphicon glyphicon-usd\"></label>Costo</th>
         <th><label class=\"glyphicon glyphicon-barcode\"></label>Descripcion</th>
          <th><label class=\"glyphicon glyphicon-list\"></label>Categoria</th>
           <th><label class=\"glyphicon glyphicon-picture\"></label>Imagen</th>
            <th><label class=\"glyphicon glyphicon-pencil\"></label>Opciones</th>
      </tr>
    </thead>";
	echo "<tbody>";
	foreach($results as $value)
	{
		$nombre = $value['nombre'];
		$precio = $value['precio'];
		$costo = $value['costo'];
		$descripcion = $value['descripcion'];
		$categoria = $value['categoria'];
		$imagepath = $value['imagepath'];
		//$hotkeys = $value['hotkeys'];
		$activado = $value['activado'];
		$idplatillo = $value['idplatillo'];

		echo "<tr>
        <td>$nombre</td>
        <td>$$precio</td>
        <td>$$costo</td>
        <td>$descripcion</td>
        <td>$categoria</td>
        <td><img src=\"../images/$imagepath\" style=\"width:80px;height:50px;\"/></td>
         <td> <button onclick=\"eliminarPlatillo('$idplatillo')\" class=\"btn btn-danger\"><label class='glyphicon glyphicon-remove'><strong>Eliminar</strong></label></button>  
              &nbsp&nbsp
              <button onclick=\"editarPlatillo('$idplatillo')\" class=\"btn btn-info\"><label class='glyphicon glyphicon-edit'><strong>Editar</strong></label></button>
              <br>
			  <br>";
		if($activado==1)
		{
			echo "<input type='checkbox' value='Activado' id='check$idplatillo' onchange=\"activePlatillo('$idplatillo')\" checked><label class=\"glyphicon glyphicon-ok-circle\">Activado</label> ";

		}
		else
		{
			echo "<input type='checkbox' value='Activado' id='check$idplatillo' onchange=\"activePlatillo('$idplatillo')\"><label class=\"glyphicon glyphicon-ban-circle\">Desactivado</label>";

		}
		
		echo "
 </td>

      </tr>";
		
		

	}
	echo  "</tbody>
  </table>";

}



?>