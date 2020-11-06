<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
 
require_once "vendor/autoload.php";
//require_once "constants.php";
 
$mail = new PHPMailer(true);
 
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.googlemail.com';  //gmail SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'dagongon@gmail.com';   //username
    $mail->Password = 'lanueva1978';   //password
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;                    //smtp port
  
    $mail->setFrom('webamedidaasturias@gmail.com', 'FROM_NAME');
    $mail->addAddress('dagongon@gmail.com', 'RECEPIENT_NAME');
 
    //$mail->addAttachment(__DIR__ . '/attachment1.png');
    //$mail->addAttachment(__DIR__ . '/attachment2.png');
 
    $mail->isHTML(true);
    $mail->Subject = 'Email Subject';
    $mail->Body    = '<b>Email Body</b>';
 
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
}
?>