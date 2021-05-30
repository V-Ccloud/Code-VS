<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
	//get smtp infos from data.json
	$data = file_get_contents('phpmailer/contactSMTP.json');
	$json = json_decode($data, true);

	//Server settings
	$mail->SMTPDebug = 0; //0 = off (for production use, No debug messages) debugging: 1 = errors and messages, 2 = messages only
	//$mail->SMTPDebug = SMTP::DEBUG_SERVER;                // Enable verbose debug output
    $mail->CharSet = 'UTF-8';
	$mail->isSMTP();                                      // Send using SMTP
	$mail->Host       = $json['host'];                    // Set the SMTP server to send through
	$mail->SMTPAuth   = true;                             // Enable SMTP authentication
	$mail->Username   = $json['user'];                    // SMTP username
	$mail->Password   = $json['password'];                // SMTP password
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;   // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
	$mail->Port       = $json['port'];                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

	//Recipients
	$mail->setFrom($json['user'], explode('@', $json['user'])[0]);
	$toMail = str_replace(' ', '', $to);
	$toMail = explode(';', $toMail);
	foreach ($toMail as $a){
		$mail->addAddress($a);     		              // Add a recipient
	}
	//$mail->addAddress('ellen@example.com');             // Name is optional
	$mail->addReplyTo($json['user'], explode('@', $json['user'])[0]);
	if (strlen($cc)>5){
		$ccMail = str_replace(' ', '', $cc);
		$ccMail = explode(';', $ccMail);
		foreach ($ccMail as $c){
			$mail->addCC($c);                   //add cc
		}
	}
	if (strlen($cci)>5){
		$cciMail = str_replace(' ', '', $cci);
		$cciMail = explode(';', $cciMail);
		foreach ($cciMail as $i){
			$mail->addBCC($i);                   //add bcc
		}
	}

	// Attachments
	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    
	// Content
	$body = htmlspecialchars_decode($mess);
	$mail->isHTML(true);                                  // Set email format to HTML
	$mail->Subject = $obj;
    $mail->AddEmbeddedImage("images/icon-logo-site-noir.png", "iconOgondo", "icon-logo-site-noir.png"); //attach image on mail body
	$mail->Body    = $body.'<br><img alt="Ogondo" src="cid:iconOgondo" style="height:50px; float:left">';
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	$infos = $mail->send();
	return true;
} catch (Exception $e) {
	return false;
}