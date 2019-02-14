<?php
    date_default_timezone_set('America/Toronto');
    require_once('class.phpmailer.php');    

    function SendMail($Sender,$SenderPass,$Receiver,$Subject,$MessageHTML,$PDFPath,$XMLPath)
    {
    $mail             = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "mail.yourdomain.com"; // SMTP server
	//$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
	$mail->Username   = $Sender;  // GMAIL username
	$mail->Password   = $SenderPass;            // GMAIL password

	$mail->SetFrom($Receiver, 'Facturacion electronica');

	$mail->AddReplyTo($Receiver,"Cliente");

	$mail->Subject = $Subject;

	$mail->MsgHTML($MessageHTML);

	$mail->AddAddress($Receiver, "Cliente");

    $larr_pdfArchiveName = explode("/",$PDFPath);
    $PDFName = $larr_pdfArchiveName[sizeof($larr_pdfArchiveName) - 1];

    $larr_xmlArchiveName = explode("/",$XMLPath);
    $XMLName = $larr_xmlArchiveName[sizeof($larr_xmlArchiveName) - 1];

	$mail->AddAttachment( $PDFPath, "RepresentacionImpresaXML_".$PDFName);

    if($XMLPath != "")
	    $mail->AddAttachment($XMLPath,$XMLName);


    if(!$mail->Send()) {
    print_r("????");
        return -1;
    } 
    else {
        return 1;
    }

   }

?>