<?php
include ("functions.php");
$query ="SELECT DISTINCT `categoria` FROM tbplatillos";
$results = execResultSet($query);
// <li><a href="#">1</a></li>
foreach($results as $category)
{
	$categoria = $category['categoria'];
	echo "<li><a style='cursor:pointer' onclick=\"updateTable('$categoria')\">$categoria</a></li>";	
	
}


?>