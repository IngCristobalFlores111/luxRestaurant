<?php
include ("functions.php");

$query = '';
$en_almacen = $_POST['en_almacen'];

if (isset($_POST['query']))
{
	
	$search = $_POST['query'];
	$search = str_replace("'",'"',$search);
	$search = htmlspecialchars($search,ENT_COMPAT);
	$search = strtoupper($search);
	
	$query = "SELECT i.id_ingrediente , i.nombre,i.cantidad,i.unidad,i.costo FROM `ingrediente` i INNER JOIN proveedor_ingrediente pi ON pi.`id_ingrediente` = i.id_ingrediente WHERE UCASE(i.nombre) LIKE '%$search%' AND i.`en_almacen`=$en_almacen GROUP BY i.id_ingrediente LIMIT 50";
	
	$result1  = execResultSet($query);
	//$query = "SELECT p.nombre FROM `proveedor` p INNER JOIN proveedor_ingrediente pi ON pi.`id_proveedor`=p.`id_proveedor`";
	//$result2 = execResultSet($query);
	$out = '';
	for($i = 0;$i<count($result1);$i++)
	{
		$nombre = $result1[$i]['nombre'];
		$cantidad = $result1[$i]['cantidad'];
		$unidad = $result1[$i]['unidad'];
		$costo = $result1[$i]['costo'];
		//$id_proveedor = $result1[$i]['id_proveedor'];
		$id_ingrediente = $result1[$i]['id_ingrediente'];
		//$proveedor_nombre = $result2[$i]['nombre'];
		
		$out = $out."<tr id='ing_inventario_$id_ingrediente'>";
		$out = $out."<td>$nombre</td>";
		$out = $out."<td>$cantidad</td>";
		$out = $out."<td>$unidad</td>";
		$out = $out."<td>$$costo</td>";
		
		
		// listado de proveedores
		$r = execResultSet("SELECT p.nombre FROM proveedor p INNER JOIN proveedor_ingrediente pi ON p.`id_proveedor`=pi.`id_proveedor` WHERE `id_ingrediente` = $id_ingrediente  LIMIT 50");
		$out = $out."<td><select id='provedores_ing_$id_ingrediente' class='form-control'>";
		foreach($r as $proveedor)
		{
			$prov = $proveedor['nombre'];
			$out = $out."<option>$prov</option>";
			
		}
		$out = $out."</select></td>";
		
		
		
		//
		$out = $out."<td><p> <button onclick = 'act_modal($id_ingrediente)' class='btn btn-default' >Actualizar</button> <button onclick='eliminar_ingrediente($id_ingrediente)' class='btn btn-danger'>Eliminar</button>  </p></td>";
		$out = $out."</tr>";
		
		
		
		
	}
	echo $out;
	
	
	
	
}
else
{
	
	$query = "SELECT i.id_ingrediente , i.nombre,i.cantidad,i.unidad,i.costo FROM `ingrediente` i INNER JOIN proveedor_ingrediente pi ON pi.`id_ingrediente` = i.id_ingrediente AND i.`en_almacen`=$en_almacen GROUP BY i.id_ingrediente LIMIT 50";
	$result1  = execResultSet($query);
	//$query = "SELECT p.nombre FROM `proveedor` p INNER JOIN proveedor_ingrediente pi ON pi.`id_proveedor`=p.`id_proveedor`";
	//$result2 = execResultSet($query);
	$out = '';
	for($i = 0;$i<count($result1);$i++)
    {
		$nombre = $result1[$i]['nombre'];
		$cantidad = $result1[$i]['cantidad'];
		$unidad = $result1[$i]['unidad'];
		$costo = $result1[$i]['costo'];
		//$id_proveedor = $result1[$i]['id_proveedor'];
		$id_ingrediente = $result1[$i]['id_ingrediente'];
		//$proveedor_nombre = $result2[$i]['nombre'];
		
		$out = $out."<tr id='ing_inventario_$id_ingrediente'>";
		$out = $out."<td>$nombre</td>";
		$out = $out."<td>$cantidad</td>";
		$out = $out."<td>$unidad</td>";
		$out = $out."<td>$$costo</td>";
		
		
		// listado de proveedores
		$r = execResultSet("SELECT p.nombre FROM proveedor p INNER JOIN proveedor_ingrediente pi ON p.`id_proveedor`=pi.`id_proveedor` WHERE `id_ingrediente` = $id_ingrediente LIMIT 50");
		if(!is_null($r))
		{
			
			$out = $out."<td><select id='provedores_ing_$id_ingrediente' class='form-control'>";
			foreach($r as $proveedor)
			{
				$prov = $proveedor['nombre'];
				$out = $out."<option>$prov</option>";
				
			}
			$out = $out."</select></td>";
			
			
			
			//
			$out = $out."<td><p> <button onclick = 'act_modal($id_ingrediente)' class='btn btn-default' >Actualizar</button> <button onclick='eliminar_ingrediente($id_ingrediente)' class='btn btn-danger'>Eliminar</button> </p> </td>";
			$out = $out."</tr>";
			
		}
	
	
	}
	echo $out;
	
	
	
	
	
}



?>