<?php
  session_start();
  $idUsr = $_SESSION['usr'];
  print_r($idUsr['caja']);

?>