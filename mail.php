<?php
include("class.phpmailer.php");
include("class.smtp.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth = true;
$mail->SMTPSecure = "ssl";
$mail->Host = "smtp.gmail.com";
$mail->Port = 465;
$mail->Username = "webamedidaasturias@gmail.com";
$mail->Password = "trilerismo2018";

$mail->From = "webamedidaasturias@gmail.com";
$mail->FromName = "User Name";
$mail->Subject = "Subject del Email";
$mail->AltBody = "Hola, te doy mi nuevo numero\nxxxx.";
$mail->MsgHTML("Hola, te doy mi nuevo numeroxxxx.");

$mail->AddAddress("dagongon@gmail.com", "Destinatario");
$mail->IsHTML(true);

//$sent = @mail("dagongon@gmail.com", "Testing", "PRUEBA", "From: webamedidaasturias@gmail.com");//@mail($to, $subject, $message, $headers);

//Enviamos el correo
if(!$mail->Send()) {
  echo "Error: " . $mail->ErrorInfo;
} else {
  echo "Enviado!";
}

?>