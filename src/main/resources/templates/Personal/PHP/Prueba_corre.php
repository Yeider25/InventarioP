<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../Composer/vendor/phpmailer/phpmailer/src/Exception.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/SMTP.php';


$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'yeidervera42@gmail.com'; // Cambia esto por tu correo
    $mail->Password = 'pojb hoyi vlhv ulnc'; // Contraseña de aplicación generada
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Destinatarios
    $mail->setFrom('almacencenigraf@gmail.com', 'Almacen CENIGRAF');
    $mail->addAddress('yeidervera42@gmail.com', 'Nombre del Destinatario');

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Prueba de correo';
    $mail->Body = '<h1>Este es un correo de prueba</h1><p>Si ves este mensaje, el correo fue enviado correctamente.</p>';

    $mail->send();
    echo 'Correo enviado correctamente.';
} catch (Exception $e) {
    echo "Error al enviar el correo: {$mail->ErrorInfo}";
}
?>