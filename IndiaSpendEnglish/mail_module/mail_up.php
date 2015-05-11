<?php
require_once('/home/content/35/7939735/html/mail_module/Master/class.phpmailer.php');
include("/home/content/35/7939735/html/mail_module/Master/class.smtp.php"); 
// optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = file_get_contents('/home/content/35/7939735/html/mail_module/Master/examples/contents.html');
//$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "mail.indiaspend.com"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = "shaibaz.webaholic@gmail.com";  // GMAIL username
$mail->Password   = "webaholic786";            // GMAIL password

$mail->SetFrom('shaibaz_m@hotmail.com', 'Shaibaz Mulla');

$mail->AddReplyTo("shaibaz_m@hotmail.com","Shaibaz Mulla");

$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "iamshaibaz@gmail.com";
$mail->AddAddress($address, "Shaibaz Mulla");

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
 ?>   