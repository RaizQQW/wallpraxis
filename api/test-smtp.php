<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

try {
    $mail = new PHPMailer(true);

    //Server settings
    $mail->SMTPDebug = 2;                      // Enable verbose debug output
    $mail->isSMTP();                           // Send using SMTP
    $mail->Host       = 'smtp.ionos.de';       // IONOS SMTP server
    $mail->SMTPAuth   = true;                  // Enable SMTP authentication
    $mail->Username   = 'social@wallpraxis-winsen.de';  // SMTP username
    $mail->Password   = 'YOUR_PASSWORD';        // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Enable TLS encryption
    $mail->Port       = 587;                   // TCP port to connect to

    //Recipients
    $mail->setFrom('social@wallpraxis-winsen.de', 'SMTP Test');
    $mail->addAddress('social@wallpraxis-winsen.de');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'SMTP Test';
    $mail->Body    = 'This is a test email to verify SMTP configuration.';

    $mail->send();
    echo 'Test email has been sent successfully';
} catch (Exception $e) {
    echo "Test failed. Error: {$mail->ErrorInfo}";
} 