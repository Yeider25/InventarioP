<?php
require __DIR__ . '/../../Composer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir conexión a la base de datos
$conexionPath = __DIR__ . '/../../PHP/Conexion.php';
if (file_exists($conexionPath)) {
    include_once($conexionPath);
} else {
    echo json_encode(['error' => 'Error interno: no se pudo cargar la conexión. Ruta usada: ' . $conexionPath]);
    exit;
}

if (!$conexion) {
    echo json_encode(['error' => 'Error al conectar con la base de datos.']);
    exit;
}

// Validar solicitud
if (isset($_POST['solicitudId']) && !empty($_POST['solicitudId'])) {
    $solicitudId = $_POST['solicitudId'];

    $consulta = "
        SELECT sp.*, 
               a.nombre_ambiente AS nombre_ambiente,
               sp.nombre_solici AS nombre_solicitante,
               sp.documento_s AS cedula,
               (SELECT SUM(e.cantidad) 
                FROM elemento e 
                INNER JOIN elementos_solicitud_periodica esp ON e.id_elemento = esp.id_elemento 
                WHERE esp.id_solicitud = sp.id) AS cantidad_elementos
        FROM solicitud_periodica sp 
        INNER JOIN ambiente a ON sp.area = a.id_ambiente
        WHERE sp.id = $solicitudId
    ";

    $resultado = mysqli_query($conexion, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $solicitud = mysqli_fetch_assoc($resultado);

        // Crear correo con PHPMailer
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Cambia si usas otro proveedor
            $mail->SMTPAuth   = true;
            $mail->Username   = 'almacencenigraf@gmail.com'; // TU CORREO GMAIL
            $mail->Password   = 'xyivyvgjkzqueaeg'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Configuración del remitente y destinatarios
            $mail->setFrom('almacencenigraf@gmail.com', 'Almacén CENIGRAF');
            $mail->addAddress('maickgutierrez13@gmail.com'); // Destinatario real

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = "Informe de Solicitud Aprobada - ID: " . $solicitudId;
            $mail->Body = "
                <h1>Solicitud Aprobada</h1>
                <p><strong>Nombre del Solicitante:</strong> {$solicitud['nombre_solicitante']}</p>
                <p><strong>Cédula:</strong> {$solicitud['cedula']}</p>
                <p><strong>Área:</strong> {$solicitud['nombre_ambiente']}</p>
                <p><strong>Cantidad de Elementos:</strong> {$solicitud['cantidad_elementos']}</p>
                <p>La solicitud ha sido aprobada y procesada por el almacén.</p>
            ";

            // Enviar el correo
            $mail->send();
            echo json_encode(['success' => 'Correo enviado exitosamente.']);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Error al enviar correo: ' . $mail->ErrorInfo]);
        }
    } else {
        echo json_encode(['error' => 'No se encontró la solicitud.']);
    }
} else {
    echo json_encode(['error' => 'ID de solicitud no proporcionado.']);
}
?>