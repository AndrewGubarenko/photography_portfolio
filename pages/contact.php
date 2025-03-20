<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../phpmailer/src/Exception.php';
require __DIR__ . '/../phpmailer/src/PHPMailer.php';
require __DIR__ . '/../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$userName  = $_POST['name'] ?? '';
$userEmail = $_POST['email'] ?? '';
$subject   = $_POST['subject'] ?? 'No Subject';
$message   = $_POST['message'] ?? '';

$userName  = filter_var($userName, FILTER_SANITIZE_STRING);
$userEmail = filter_var($userEmail, FILTER_VALIDATE_EMAIL);
$message   = htmlspecialchars($message);

if (!$userEmail) {
    exit('Invalid email address.');
}

$mail = new PHPMailer(true);

try {
    // 4. SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';     // For Gmail
    $mail->SMTPAuth   = true;
    $mail->Username   = 'oshustudio.ch@gmail.com';  // Your Gmail
    $mail->Password   = 'mypassword';         // Your Gmail password or App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // "From" must be your authorized email
    $mail->setFrom('mygmail@gmail.com', 'My Website Contact Form');

    // Email receiver
    $mail->addAddress('oshustudio.ch@gmail.com');

    $mail->addReplyTo($userEmail, $userName);

    $mail->isHTML(false);  // Used plain text for simplicity
    $mail->Subject = $subject;

    $body = "You have a new message from your website contact form:\n\n";
    $body .= "Name: $userName\n";
    $body .= "Email: $userEmail\n";
    $body .= "Subject: $subject\n";
    $body .= "Message:\n$message\n";

    $mail->Body = $body;

    $mail->send();
    echo 'Thank You! Your message has been sent.';
} catch (Exception $e) {
    echo "Message could not be sent. Error: {$mail->ErrorInfo}";
}
