<?php
include "mail_connect.php";

$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$email = $_POST['email'];
$message = $_POST['message'];
$reason = $_POST["reason"];

echo $email_subject = "Tonality contact form";

echo $email_body = "You have received a new message from the user $fname $lname. Reason for contact: $reason . Here is the message: \" $message \". You can return their message here: $email";


$mail->isSMTP();
$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = 'mail.gotonality.com;';
$mail->SMTPAuth = true;
$mail->Username = 'no-reply@gotonality.com';
$mail->Password = 'VRSPassword1!';
$mail->SMTPSecure = 'ssl';
$mail->Port = 290;

$mail->setFrom('no-reply@gotonality.com', 'Tonality');
$mail->addAddress('waje5wdxka3o@gmail.com');
$mail->addAddress('eric.love02@yahoo.com');
$mail->addAddress('contact@gotonality.com');

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';
$mail->Encoding = 'base64';
$mail->Subject = $email_subject;
$mail->Body    = $email_body;
$mail->AltBody = $email_body;

$mail->send();

header('Location: /contact-recieved');

?>
