

<?php
$myfile = fopen("TextFile.txt", "r+") or die("Unable to open file!");
// Output one line until end-of-file
while(!feof($myfile)) {
    echo fgets($myfile, 8) . "<br>";
}
echo "<p1> Lest write something on this file </p1>";
fwrite($myfile,"By Hermes trimegistrus");
echo readfile("TextFile.txt");
fclose($myfile);

?>



