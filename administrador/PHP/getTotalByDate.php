<?php

include('functions.php');
$date = $_POST["date"];
$total =obtenerTotalPorFecha($date);
$ganancias = obtenerGananciaTotalPorFecha($date);
$costo = obtenerCostoTotalPorFecha($date);

echo $total.":".$costo.":".$ganancias;

?>