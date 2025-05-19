<?php
include ('../../PHP/Conexion.php');
include ('../../PHP/Funciones.php');

InicioSesion();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../Composer/vendor/phpmailer/phpmailer/src/Exception.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/SMTP.php';

header('Content-Type: application/json');
ob_start();

$response = "";
$status = "error";

if ($conexion->connect_error) {
    ob_end_clean();
    echo json_encode(["status" => "error", "message" => "Conexión fallida: " . $conexion->connect_error]);
    exit;
}
//Medir tiempo de inicio
$start_time = microtime(true);

//Manejo de insercion de datos y aceptacion de datos del formulario
if (isset($_POST['f_solicitud'])) {
    $fecha_solicitud = $_POST['f_solicitud'];
    $nombre = $_POST['nombre_solicitante'];
    $documento = $_POST['docu'];
    $ficha = $_POST['fi_anu'];
    $programa_id = $_POST['pro_anu'];
    $elementos = $_POST['nom_elemento'];
    $unidades = $_POST['unidad'];
    $cantidades = $_POST['cantidad'];
    $solicitadas = $_POST['solicitada'];
    $id_solicitud = $conexion->insert_id;

    $sql_insert_solicitud = "INSERT INTO solicitud_anual (fecha_soli, nombre_solici, documento, ficha_soli, programa_soli, cantidad_soli)
                             VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql_insert_solicitud);
    $cantidad_total = array_sum($solicitadas);
    $stmt->bind_param("sssisi", $fecha_solicitud, $nombre, $documento, $ficha, $programa_id, $cantidad_total);

    if (!$stmt->execute()) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Error al registrar la solicitud: " . $stmt->error]);
        exit;
    }

    $id_solicitud = $conexion->insert_id;
    $detalles_elementos = "";

    
    }
//__________________ENVIO DE CORREO ELECTRONICO CON LA SOLICITUD___________________________________________________________
     
for ($i = 0; $i < count($elementos); $i++) {
    $id_elemento = $elementos[$i];
    $cantidad_solicitada = $solicitadas[$i];
    $unidad = $unidades[$i];
    $cantidad = $cantidades[$i];

    // Insertar en la BD
    $sql_insert_elemento = "INSERT INTO elemento_solicitud_anual (id_solicitud, id_elemento, cantidad, fecha_soli)
                            VALUES (?, ?, ?, ?)";
    $stmt_elemento = $conexion->prepare($sql_insert_elemento);
    $stmt_elemento->bind_param("iiis", $id_solicitud, $id_elemento, $cantidad_solicitada, $fecha_solicitud);
    $stmt_elemento->execute();

    // Agregar al correo
    $detalles_elementos .= "<p><strong>Elemento ID:</strong> $id_elemento</p>";
    $detalles_elementos .= "<p><strong>Unidad de Medida:</strong> $unidad</p>";
    $detalles_elementos .= "<p><strong>Cantidad Disponible:</strong> $cantidad</p>";
    $detalles_elementos .= "<p><strong>Cantidad Solicitada:</strong> $cantidad_solicitada</p><br>";
}


    $insert_time = microtime(true);

    $mail = new PHPMailer(true);
    try {
        //Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'maickgutierrez13@gmail.com';
        $mail->Password = 'pojb hoyi vlhv ulnc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Destinatario y remitente
        $mail->setFrom('almacencenigraf@gmail.com', 'Almacen CENIGRAF');
        $mail->addAddress('maickgutierrez13@gmail.com', 'Nombre del Destinatario');

        $imagePath = '/htdocs/InventarioPHP/src/main/resources/templates/images/cenigraf.png';
        if (file_exists($imagePath)) {
            $mail->addEmbeddedImage($imagePath, 'logo_cenigraf');
        }

        $mail->isHTML(true);
        $mail->Subject = 'Se ha generado Nueva Solicitud Anual';
        // Cuerpo del correo
        $mail->Body = "
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
            <div class='container'>
                <div class='header'>
                    <img src='cid:logo_cenigraf' alt='CENIGRAF'>
                    <h2>Solicitud Anual Generada</h2>
                </div>
                <div class='content'>
                    <p>Buen dia, Se ha recibido una nueva solicitud anual con los siguientes detalles:</p>
                    <p><strong>Fecha de Solicitud:</strong> $fecha_solicitud</p>
                    <p><strong>Nombre del Solicitante:</strong> $nombre</p>
                    <p><strong>Documento:</strong> $documento</p>
                    <p><strong>Ficha:</strong> $ficha</p>
                    <p><strong>Programa:</strong> $programa</p>
                    <br>
                    <h3>Detalles de los Elementos Solicitados:</h3>
                    $detalles_elementos
                </div>
                <div class='footer'>
                    <p>Este es un mensaje automático, por favor no responda a este correo.</p>
                    <p>&copy; 2024 CENIGRAF. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        $mail->send();
        $response = 'Correo enviado correctamente';
        $status = 'success';

    } catch (Exception $e) {
        $response = "Error al enviar el correo: {$mail->ErrorInfo}";
        $status = 'error';
    }

    $end_time = microtime(true);
    $insert_duration = $insert_time - $start_time;
    $mail_duration = $end_time - $insert_time;
    $total_duration = $end_time - $start_time;

    ob_end_clean();
    echo json_encode([
        "status" => $status,
        "id_solicitud" => $id_solicitud,
        "message" => $response,
        "tiempos" => [
            "insercion" => $insert_duration,
            "correo" => $mail_duration,
            "total" => $total_duration
        ]
    ]);
    exit;

foreach ($elementos as $i => $id_elemento) {
    $cantidad_solicitada = $solicitadas[$i];

    // Verificar si hay suficientes unidades disponibles
    $sql_disponible = "SELECT cantidad FROM elemento WHERE id_elemento = ?";
    $stmt_disponible = $conexion->prepare($sql_disponible);
    $stmt_disponible->bind_param("i", $id_elemento);
    $stmt_disponible->execute();
    $result_disponible = $stmt_disponible->get_result();
    $row_disponible = $result_disponible->fetch_assoc();

    if ($row_disponible['cantidad'] < $cantidad_solicitada) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "No hay suficientes unidades disponibles para el elemento con ID: $id_elemento"]);
        exit;
    }

    // Insertar en la tabla elemento_solicitud_anual
    $sql_insert_elemento = "INSERT INTO elemento_solicitud_anual (id_solicitud, id_elemento, cantidad, fecha_soli)
                            VALUES (?, ?, ?, ?)";
    $stmt_elemento = $conexion->prepare($sql_insert_elemento);
    $stmt_elemento->bind_param("iiis", $id_solicitud, $id_elemento, $cantidad_solicitada, $fecha_solicitud);

    if (!$stmt_elemento->execute()) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Error al insertar elementos: " . $stmt_elemento->error]);
        exit;
    }

    // Actualizar las unidades disponibles en la tabla elemento
    $sql_actualizar = "UPDATE elemento SET cantidad = cantidad - ? WHERE id_elemento = ?";
    $stmt_actualizar = $conexion->prepare($sql_actualizar);
    $stmt_actualizar->bind_param("ii", $cantidad_solicitada, $id_elemento);

    if (!$stmt_actualizar->execute()) {
        ob_end_clean();
        echo json_encode(["status" => "error", "message" => "Error al actualizar las unidades: " . $stmt_actualizar->error]);
        exit;
    }
}
?>
