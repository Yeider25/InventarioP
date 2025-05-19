<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../Composer/vendor/phpmailer/phpmailer/src/Exception.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/SMTP.php';
require __DIR__ . '/../../Composer/vendor/autoload.php';

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar sesión
if (!isset($_SESSION['id'])) {
    echo json_encode(["status" => "error", "message" => "No se ha iniciado sesión."]);
    exit();
}

include('../../PHP/Conexion.php');
file_put_contents('debug_post.txt', print_r($_POST, true));


// Inicializar el nombre del solicitante
$nombre_solictante = $_SESSION['nombre'] ?? 'No especificado';

// Obtener el nombre del solicitante desde la base de datos
$stmt_nombre = $conexion->prepare("SELECT nombre_instructor FROM instructor WHERE id = ?");
$stmt_nombre->bind_param("i", $_SESSION['id']);
$stmt_nombre->execute();
$stmt_nombre->bind_result($nombre_solicitante);
$stmt_nombre->fetch();
$stmt_nombre->close();

// Si se obtiene un nombre válido, actualizar la variable
if (!empty($nombre_solicitante)) {
    $nombre_solictante = $nombre_solicitante;
}

file_put_contents('debug_session.txt', "ID de sesión: " . $_SESSION['id'] . PHP_EOL, FILE_APPEND);

if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo json_encode(["status" => "error", "message" => "El ID de la sesión no está definido o está vacío."]);
    exit();
}


if (empty($nombre_solicitante)) {
    $nombre_solicitante = 'No especificado';
}

function clean($data) {
    return htmlspecialchars(trim($data));
}

// Entradas
$tipo_solicitud = clean($_POST['solicitud'] ?? '');
$fecha_solicitud = clean($_POST['fecha_solicitud'] ?? '');
$necesidad = clean($_POST['necesidad'] ?? '');
$nom_maquina = clean($_POST['nom_maquina'] ?? '');
$marca = clean($_POST['marca'] ?? '');
$modelo = clean($_POST['modelo'] ?? '');
$placa = clean($_POST['placa'] ?? '');
$serial = clean($_POST['serial'] ?? '');
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;
$suministro = clean($_POST['suministro'] ?? '');
$observaciones = clean($_POST['observaciones'] ?? '');

// Validación
$errores = [];
if (empty($tipo_solicitud)) $errores[] = "El campo 'solicitud' es obligatorio.";
if (empty($fecha_solicitud)) $errores[] = "El campo 'fecha_solicitud' es obligatorio.";
if (empty($necesidad)) $errores[] = "El campo 'necesidad' es obligatorio.";
if (empty($nom_maquina)) $errores[] = "El campo 'nom_maquina' es obligatorio.";
if (empty($marca)) $errores[] = "El campo 'marca' es obligatorio.";
if (empty($modelo)) $errores[] = "El campo 'modelo' es obligatorio.";
if (empty($placa)) $errores[] = "El campo 'placa' es obligatorio.";
if (empty($serial)) $errores[] = "El campo 'serial' es obligatorio.";
if ($cantidad <= 0) $errores[] = "El campo 'cantidad' debe ser mayor a 0.";

if (!empty($errores)) {
    echo json_encode(["status" => "error", "message" => implode(", ", $errores)]);
    exit();
}

// Datos de sesión
$id_instructor = $_SESSION['id'];
$id_ambiente = 1;
$id_rol_solicitante = 1;
$tipo_mantenimiento = 1;

// Verificar/insertar máquina
$stmt_verificar = $conexion->prepare("SELECT id FROM maquina WHERE serial = ?");
$stmt_verificar->bind_param("s", $serial);
$stmt_verificar->execute();
$stmt_verificar->store_result();

if ($stmt_verificar->num_rows > 0) {
    $stmt_verificar->bind_result($id_maquina);
    $stmt_verificar->fetch();
    $stmt_verificar->close();
} else {
    $stmt_verificar->close();
    $stmt_insertar_maquina = $conexion->prepare(
        "INSERT INTO maquina (nombre_maquina, marca, modelo, placa, serial, cantidad, id_ambiente)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt_insertar_maquina->bind_param("ssssssi", $nom_maquina, $marca, $modelo, $placa, $serial, $cantidad, $id_ambiente);
    if (!$stmt_insertar_maquina->execute()) {
        echo json_encode(["status" => "error", "message" => "Error al insertar la máquina: " . $stmt_insertar_maquina->error]);
        exit();
    }
    $id_maquina = $stmt_insertar_maquina->insert_id;
    $stmt_insertar_maquina->close();
}

// Insertar solicitud
$stmt = $conexion->prepare(
    "INSERT INTO solicitud_mantenimiento (solicitud, fecha_soli, necesidad, maquina, tipo, suministro, id_instructor, id_ambiente, id_rol, observaciones)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
$stmt->bind_param("issiiisiss", $tipo_solicitud, $fecha_solicitud, $necesidad, $id_maquina, $tipo_mantenimiento, $suministro, $id_instructor, $id_ambiente, $id_rol_solicitante, $observaciones);
if (!$stmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Error al insertar la solicitud: " . $stmt->error]);
    exit();
}
$stmt->close();

// Envío de correo
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'almacencenigraf@gmail.com';
    $mail->Password = 'xyivyvgjkzqueaeg';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('almacencenigraf@gmail.com', 'Almacen CENIGRAF');
    $mail->addAddress('maickgutierrez13@gmail.com');

    $imagePath = 'C:/xampp/htdocs/InventarioPHP/src/main/resources/templates/images/cenigraf.png';
    if (file_exists($imagePath)) {
        $mail->addEmbeddedImage($imagePath, 'logo_cenigraf');
    }

    $mail->isHTML(true);
    $mail->Subject = 'Nueva Solicitud de Mantenimiento';
    $mail->Body = <<<HTML
<!DOCTYPE html>
<html>
<head>
    <style>
        .container {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="cid:logo_cenigraf" alt="CENIGRAF">
            <h2>Solicitud de Mantenimiento Generada</h2>
        </div>
        <div class="content">
            <p><b>Nombre del solicitante:</b> {$nombre_solictante}</p>
            <p>Se ha enviado una nueva solicitud de mantenimiento con la siguiente información:</p>
            <p><b>Fecha de Solicitud:</b> {$fecha_solicitud}</p>
            <p><b>Tipo de Solicitud:</b> {$tipo_solicitud}</p>
            <p><b>Necesidad:</b> {$necesidad}</p>
            <p><b>Nombre de la Máquina:</b> {$nom_maquina}</p>
            <p><b>Marca:</b> {$marca}</p>
            <p><b>Modelo:</b> {$modelo}</p>
            <p><b>Placa:</b> {$placa}</p>
            <p><b>Serial:</b> {$serial}</p>
            <p><b>Cantidad:</b> {$cantidad}</p>
            <p><b>Tipo de Suministro:</b> {$suministro}</p>
            <p><b>Observaciones:</b> {$observaciones}</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático, por favor no responda a este correo.</p>
            <p>&copy; 2024 CENIGRAF. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
HTML;

    $mail->send();
    echo json_encode(["status" => "success", "message" => "Solicitud y correo enviados correctamente."]);
    exit();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Error al enviar el correo: {$mail->ErrorInfo}"]);
    exit();
}

$conexion->close();
?>
