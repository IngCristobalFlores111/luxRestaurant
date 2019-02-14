<?php
include('functions.php');
$target_dir = "../../images/";
$id = $_POST["id"];
$operation = $_POST["op"];
$active = '';
if(isset($_POST['activado']))
{
$active = '1';
	}
	else
	{
		$active='0';
		}

$nommbre = $_POST["nombre"];
$nommbre = trim($nommbre);
$nommbre = str_replace('"',"'",$nommbre);
$nommbre = htmlspecialchars($nommbre,ENT_COMPAT);


$precio = $_POST["precio"];
$precio = trim($precio);
$precio = str_replace('"',"'",$precio);
$precio = htmlspecialchars($precio,ENT_COMPAT);

$costo = $_POST["costo"];
$costo = trim($costo);
$costo = str_replace('"',"'",$costo);
$costo = htmlspecialchars($costo,ENT_COMPAT);


$desc = $_POST["desc"];
$desc = trim($desc);
$desc = str_replace('"',"'",$desc);
$desc = htmlspecialchars($desc,ENT_COMPAT);


$categoria = $_POST["categoria"];
$categoria = trim($categoria);
$categoria = str_replace('"',"'",$categoria);
$categoria = htmlspecialchars($categoria,ENT_COMPAT);


$hotkeys='';


$oldpath = $_POST["oldpath"];

if (empty($_FILES['fileToUpload']['name']))
{

	$query ="UPDATE tbplatillos SET imagepath='$oldpath',nombre=\"$nommbre\",precio='$precio',costo='$costo',descripcion=\"$desc\",categoria='$categoria',hotkeys='' ,activado='$active' WHERE idplatillo=$id";
ejecutarSQLCommand($query);
}
else
{

$fname =basename($_FILES["fileToUpload"]["name"]);
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
// actualizar imagen

if($operation==0)
{
		$query ="UPDATE tbplatillos SET imagepath='$fname',nombre=\"$nommbre\",precio='$precio',costo='$costo',descripcion=\"$desc\",categoria='$categoria',hotkeys='$hotkeys',activado='$active' WHERE idplatillo=$id";
ejecutarSQLCommand($query);

}
if($operation==1)
{
		$query = "INSERT INTO `dbrestaurante`.`tbplatillos` (`nombre`, `precio`, `costo`, `descripcion`, `categoria`, `imagepath`, `hotkeys`,`activado`) VALUES (\"$nommbre\",'$precio','$costo',\"$desc\",'$categoria','$fname','$hotkeys','$active')";

ejecutarSQLCommand($query);

}
}
echo "<script>close();</script>";
//echo $query;

?>