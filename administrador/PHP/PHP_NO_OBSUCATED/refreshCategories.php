<?php
include ("functions.php");
$query ="SELECT DISTINCT `categoria` FROM tbplatillos";
$selected = $_POST['category'];
$results = execResultSet($query);
// <li><a href="#">1</a></li>
foreach($results as $category)
{
	$categoria = $category['categoria'];
	
	if($selected==$categoria)
	{
		echo "<li class='active'><a style='cursor:pointer' onclick=\"updateTable('$categoria')\">$categoria</a></li>";	

	}
	else
	{
		echo "<li><a style='cursor:pointer' onclick=\"updateTable('$categoria')\">$categoria</a></li>";	
	}
}


?>