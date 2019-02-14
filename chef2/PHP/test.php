<?php
session_start();

date_default_timezone_set("America/Mexico_City");
print_r($_SESSION['usr']);
echo date("Y-m-d h:i:s");

?>