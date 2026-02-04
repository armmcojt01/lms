<?php
require 'vendor/autoload.php'; // Path to autoload.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';    // SMTP server
    $mail->SMTPAuth   = true;
    $mail->Username   = 'learningmanagement576@gmail.com';  // SMTP username
    $mail->Password   = 'ahkv dpsl urcn lbmr';     // SMTP password (use App Password for Gmail)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
    $mail->Port       = 587;                    // TCP port to connect to
    
    // Sender & Recipients
    $mail->setFrom('learningmanagement576@gmail.com', 'Your Name');
    $mail->addAddress('faith2miyuki@gmail.com', 'Recipient Name');
    
    // Optional: Add CC, BCC, Reply-To
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');
    $mail->addReplyTo('reply@example.com', 'Reply To');
    
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = 'Test Email Subject';
    $mail->Body    = '<h1>Hello!</h1><p>This is an HTML email body.</p>';
    $mail->AltBody = 'This is the plain text version for non-HTML mail clients';
    
    // Attachments (optional)
    // $mail->addAttachment('/path/to/file.pdf', 'filename.pdf');
    
    // Send email
    $mail->send();
    echo 'Email has been sent successfully!';
    
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>