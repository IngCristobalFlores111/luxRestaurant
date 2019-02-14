<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(isset($_GET['accion'])){
    include("../functions.php");

    switch($_GET['accion']){
        case "imprimirCuenta":
    include("../../../../wkWorks/generateTicket.php");
    $idCuenta = $_GET['idCuenta'];
    generateTicket($idCuenta,$_GET['pacial']);

        break;

    }
}



?>