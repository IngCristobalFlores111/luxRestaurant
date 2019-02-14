<?php
include ('functions.php');

$idPedido = $_POST['id'];

$query = "SELECT despachado,horallegada,nomplatillo FROM tbpedidos WHERE idpedido=$idPedido";
$d = execResultSet($query);
if($d[0]['despachado']==0)
{


	$query ="UPDATE tbpedidos SET despachado=1 WHERE idpedido=$idPedido";

	ejecutarSQLCommand($query);	
}
if($d[0]['despachado']==-1)
{
  
	$nombrePlatillo = $d[0]['nomplatillo'];
	$horallegada = $d[0]['horallegada'];
	$query = "DELETE FROM tbpedidos WHERE nomplatillo='$nombrePlatillo' AND $horallegada='$horallegada' AND despachado=-1";
	
	
	ejecutarSQLCommand($query);	

	
}

$query ="SELECT COUNT(`idpedido`) AS count FROM tbpedidos WHERE nomplatillo IN ( SELECT `nombre` FROM tbplatillos) AND `despachado`=0 OR despachado=-1";
$check = execResultSet($query);
if($check[0]['count']==0)
{
	echo "<div class='row'";
	echo "<div class='col-xs-12' style='text-align:center;color:white'><h4>No Hay pedidos pendientes por el momento</h4></div>";
}
else
{



	$query = "SELECT tbplatillos.categoria, horallegada,nomplatillo,comentario,despachado,idpedido,tbplatillos.imagepath,COUNT(`nomplatillo`) AS count FROM `tbpedidos` INNER JOIN tbplatillos ON tbplatillos.nombre=tbpedidos.nomplatillo WHERE tbpedidos.despachado=0 
	OR tbpedidos.despachado=-1 GROUP BY `nomplatillo`,`comentario`,`horallegada`,`despachado` 
	ORDER BY tbpedidos.`horallegada` ASC";
	
	$result = execResultSet($query);
	$default = 0;
	if($result!=null)
	{
		$i = 1;
		$fail = false;




		foreach($result as $pedido)
		{
			
			$stampAnterior = $pedido['horallegada'];
			$segundos = time()-$stampAnterior;
			$elapsed = SegundosLeido($segundos);
			
			$nombre = $pedido['nomplatillo'];
			$comentario = $pedido['comentario'];
			$comentario= trim($comentario);
			$despachado = $pedido['despachado'];
			$count = $pedido['count'];
			if(strlen($comentario)>115)
			{		$comentario=substr($comentario,0,115);
				$comentario= $comentario."...";
			}	
			$id = $pedido['idpedido'];
			$image = $pedido['imagepath'];
			$categoria = $pedido['categoria'];
			$categoria = trim($categoria);
			if($despachado==0)
			{
				if($categoria=='platillo')
				{
					
					echo "<div class='row well' style='background-color:rgb(92,184,92)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				if($categoria=='entrada')
				{
					$default=1;

					echo "<div class='row well' style='background-color:rgb(51,122,183)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				if($categoria=='postres')
				{
					

					echo "<div class='row well' style='background-color:rgb(240,173,78)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				if($categoria=='bebidas')
				{					

					echo "<div class='row well' style='background-color:rgb(91,192,222)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
					
				}
				if($categoria=='guarniciones')
				{
					echo "<div class='row well' style='background-color:rgb(217,83,79)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				
				if($categoria=='menu_ninos')
				{
					echo "<div class='row well' style='background-color:rgb(0,204,0)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				if($categoria=='gyro')
				{
					echo "<div class='row well' style='background-color:rgb(255,153,0)'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				
				
				if( $categoria!='gyro'&& $categoria!='menu_ninos'&& $categoria!='guarniciones'&&$categoria!='platillo'&&$categoria!='postres'&&$categoria!='entrada'&&$categoria!='bebidas')
				{
					echo "<div class='row well' style='background-color:#5CB85C'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>";
				}
				
				
				echo "
                            

                </div>
                <div class='col-xs-4'><p class='platillo'>$nombre <span style=\"color:yellow\">x$count</span>&nbsp&nbsp&nbsp<img onclick='ShowImageModal($id)' src='../images/$image' width='125' height='100' style='border-radius: 15%'  /></p></div>
                <div class='col-xs-6'>
                    <div onclick=\"showModal('$id')\" class='well' style='background-color:#FFFFCC;color:#0099CC;font-size:22px'>$comentario</div>
                           <h4 class=\"Elapsed\">$elapsed</h4>
                </div>

                <div class='col-xs-1'>
                    <button class='btn-success btnDone' onclick='dispatchedOrder($id)'><span class='glyphicon glyphicon-ok'></span> </button>

                </div>


            </div>
	";
			}
			if($despachado==-1)
			{
				echo "<div class='row well' style='background-color:red'>
                <div class='col-xs-1'>
                    <div class='well numberPlatillo'>$i</div>


                </div>
                <div class='col-xs-4'><p class='platillo'>$nombre <span style=\"color:yellow\">x$count</span>&nbsp&nbsp&nbsp<img onclick='ShowImageModal($id)' src='../images/$image' width='125' height='100'  style='border-radius: 15%'  /></p></div>
                <div class='col-xs-6'>
                    <div onclick=\"showModal('$id')\" class='well' style='background-color:#FFFFCC;color:#0099CC;font-size:22px'>$comentario</div>
				
   <h4 style='color:yellow'> <strong>Platillo Cancelado&#33;</strong></h4>

                </div>

                <div class='col-xs-1'>
                    <button class='btn btn-danger btnDone' onclick='dispatchedOrder($id)'><span class='glyphicon glyphicon-remove'></span> </button>

                </div>


            </div>
	";
			}

			
			$i++;
		}
	}
	else
	{
		echo "<div class='row'";
		echo "<div class='col-xs-12' style='text-align:center;color:white'><h4>No Hay pedidos pendientes por el momento</h4></div>";
	}
}



?>