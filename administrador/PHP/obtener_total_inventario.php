<?php
include ("functions.php");
$query = "SELECT ROUND(SUM(i.cantidad*i.costo),3) AS total,ROUND(SUM(i.cantidad*i.costo)/1.16,3) AS subtotal, ROUND((SUM(i.cantidad*i.costo)/1.16)*0.16,3) AS iva FROM ingrediente i";


echo getJSONResultSQL($query);





?>