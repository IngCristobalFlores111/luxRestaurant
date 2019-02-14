<?php

require '../../..//phpMailer/mailer.php';
$mail = new PHPMailer;
$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
//Set the encryption system to use - ssl (deprecated) or tls
$mail->SMTPSecure = 'tls';
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication - use full email address for gmail
$mail->Username = "luxline.development@gmail.com";
//Password to use for SMTP authentication
$mail->Password = "352P_rjQ";
$mail->setFrom('luxline.development@gmail.com', 'Luxline Support ticket');
//Set an alternative reply-to address
$mail->addReplyTo('luxline.development@gmail.com', 'Ticket');
//Set who the message is to be sent to
$mail->addAddress('luxline.development@gmail.com', 'Support');
//Set the subject line
$mail->Subject = 'Soporte restaurante BBQ Addiction';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$html ="<label style='color:#e74c3c;font-weight:bolder'>Tipo</label> ".$request->tipo."<br>";
$html.="<label style='color:#e74c3c;font-weight:bolder'>Asunto</label> ".$request->asunto."<br>";
$html.="<label style='color:#e74c3c;font-weight:bolder'>Mensaje</label> ".$request->mensaje."<br>";

$mail->msgHTML($html);
$mail->AltBody = 'Ticket de soporte '.$request->asunto;

$respuesta= array();
if (!$mail->send()) {
    $respuesta['error'] =$mail->ErrorInfo;
    $respuesta['success']=false;
} else {
    $respuesta['success']=true;
}
print_r(json_encode($respuesta,JSON_UNESCAPED_UNICODE));

?>