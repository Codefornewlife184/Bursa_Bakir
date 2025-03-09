<?php
// Debug modu kapalı
error_reporting(0);
ini_set('display_errors', 0);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

try {
    $mail = new PHPMailer(true);

    //Server settings
    $mail->isSMTP();
    $mail->Host       = ' SMTP.yandex.com';  // SMTP sunucunuz
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@bursabakir.com';  // SMTP kullanıcı adı
    $mail->Password   = 'Brs.2025@';             // SMTP şifre
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;                    // SMTP port

    //Recipients
    $mail->setFrom('info@bursabakir.com', 'Bursa Bakır İletişim Formu');
    $mail->addAddress('info@bursabakir.com');
    $mail->addReplyTo($_POST['email'], $_POST['name']);

    //Content
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Yeni İletişim Formu Mesajı: ' . $_POST['subject'];
    
    // Email içeriği
    $message = "
    <html>
    <head>
        <title>Yeni İletişim Formu Mesajı</title>
    </head>
    <body>
        <h2>Yeni bir mesaj aldınız</h2>
        <table>
            <tr>
                <th align='left'>Ad:</th>
                <td>" . htmlspecialchars($_POST['name']) . "</td>
            </tr>
            <tr>
                <th align='left'>Email:</th>
                <td>" . htmlspecialchars($_POST['email']) . "</td>
            </tr>
            <tr>
                <th align='left'>Konu:</th>
                <td>" . htmlspecialchars($_POST['subject']) . "</td>
            </tr>
            <tr>
                <th align='left'>Mesaj:</th>
                <td>" . nl2br(htmlspecialchars($_POST['message'])) . "</td>
            </tr>
        </table>
    </body>
    </html>
    ";

    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message);

    $mail->send();
    echo json_encode(['status' => 'success', 'message' => 'Mesajınız başarıyla gönderildi.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => "Mail gönderilemedi. Hata: {$mail->ErrorInfo}"]);
}