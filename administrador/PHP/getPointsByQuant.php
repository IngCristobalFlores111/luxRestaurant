<?php

include('functions.php');
$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];

$platillo = $_POST["platillo"];

$query ="SELECT idplatillo FROM tbplatillos WHERE nombre='$platillo'";
$ID = execResultSet($query);
$idplatillo = $ID[0]['idplatillo'];

$query ="SELECT cantidad FROM tbventaplatillos WHERE idplatillo=$idplatillo AND fecha BETWEEN '$dateFrom' AND '$dateTo'";

$result = execResultSet($query);
$output = '';
foreach($result as $cantidad)
{

$output=$output.$cantidad['cantidad'].":";

}
echo $output;




?>