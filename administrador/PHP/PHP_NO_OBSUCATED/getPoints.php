<?php

include('functions.php');
$dateFrom = $_POST["dateFrom"];
$dateTo = $_POST["dateTo"];
$opcion =  $_POST["opcion"];
echo getPoints($dateFrom,$dateTo,null,$opcion);


?>