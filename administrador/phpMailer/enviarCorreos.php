
    <?php

//error_reporting(E_ALL);

date_default_timezone_set('America/Toronto');

require_once('class.phpmailer.php');
include("../PHP/new/functions.php");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$sql = createMysqliConnection();
$emisor = $sql->executeQuery("SELECT nombre,email,emailPass FROM `tbfactura_emisor` WHERE idEmisor = 1");
$emisor = $emisor[0];


$mail             = new PHPMailer();

$body             = $request->mensaje;
//$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = $emisor['email'];  // GMAIL username
$mail->Password   = $emisor['emailPass'];            // GMAIL password

$mail->SetFrom($emisor['email'], $emisor['nombre']);

foreach($request->contactos as $c){
    if($c->selected){
        $mail->AddReplyTo($c->email,$c->nombre);
        $mail->AddAddress($c->email,$c->nombre);
    }
}

//$mail->AddReplyTo("tobalin01@hotmail.com","Cliente");

$mail->Subject    = $request->asunto;

$mail->AltBody    = $request->mensaje;

$mail->MsgHTML($body);



//$address = "tobalin01@hotmail.com";
//$mail->AddAddress($address, "John Doe");

if($request->xml){
    $xml_path ="../Facturacion/timbrados/cfdi_".$request->folio.".xml";
    $mail->AddAttachment( $xml_path,"cfdi_".$request->folio.".xml");
}
if($request->pdf){
    $xml_path ="../Facturacion/timbrados/cfdi_".$request->folio.".pdf";
    $mail->AddAttachment( $xml_path,"cfdi_".$request->folio.".pdf");
}

//$mail->AddAttachment( "aboutus.html", 'aboutus.html' );

if(!$mail->Send()) {
    echo 0;
} else {
    echo 1;
}

?>

