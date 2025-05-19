<?php
// Conectar a la base de datos
include('../../PHP/Conexion.php');
include('../../PHP/Funciones.php');

InicioSesion();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../Composer/vendor/phpmailer/phpmailer/src/Exception.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../../Composer/vendor/phpmailer/phpmailer/src/SMTP.php';

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Habilitar errores de MySQL para depuración
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Recibir los datos del formulario
$nombre = $_POST['nombre_solicitante'] ?? null;
$documento_s = $_POST['docu'] ?? null;
$fecha_solicitud = $_POST['f_solicitud'] ?? null;
$cod_regional = $_POST['cod_regional'] ?? null;
$cod_costos = $_POST['cod_costos'] ?? null;
$nombre_coor = $_POST['nombre_coor'] ?? null;
$cargo = $_POST['cargo'] ?? null;
$id_coordinador = $_POST['id_coordinador'] ?? null;
$area_solicitud = $_POST['area_solicitud'] ?? null;
$id_area = $_POST['id_area'] ?? null;
$nom_regional = $_POST['nom_regional'] ?? null;
$nom_centro_costos = $_POST['nom_centro_costos'] ?? null;
$destino = $_POST['destino'] ?? null;
$ficha = $_POST['ficha'] ?? null;
$numero_ficha = $_POST['numero_ficha'] ?? null;
$id_tipo_cuentadante = $_POST['id_tipo_cuentadante'] ?? null;
$canti_elem = $_POST['canti_elem'] ?? [];
$obser_elem = $_POST['obser_elem'] ?? [];

// Validar campos obligatorios
if (!$nombre || !$documento_s || !$fecha_solicitud || !$cod_regional || !$cod_costos || !$nombre_coor || !$cargo || !$id_area || !$destino || empty($canti_elem)) {
    die("⚠️ Faltan datos obligatorios en el formulario.");
}

// Generar un código único para la solicitud
$cod_peri = "SOL-" . date("YmdHis");

// 🔹 **PASO 1: Insertar la solicitud en `solicitud_periodica`**
try {
    $stmt_solicitud = $conexion->prepare("INSERT INTO solicitud_periodica 
        (cod_peri, fecha_soli, area, cargo, cod_regi, nom_regi, cod_costo, nom_costo, nom_jefe, tipo_cuentadante, dest_bien, num_fich, nombre_solici, documento_s) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt_solicitud->bind_param(
        "ssississssisss",
        $cod_peri, $fecha_solicitud, $id_area, $cargo, $cod_regional, $nom_regional,
        $cod_costos, $nom_centro_costos, $nombre_coor, $id_tipo_cuentadante,
        $destino, $numero_ficha, $nombre, $documento_s
    );

    $stmt_solicitud->execute();
    $id_solicitud = $conexion->insert_id;
    $stmt_solicitud->close();

    if (!$id_solicitud) {
        throw new Exception("No se generó un ID de solicitud. Revise la inserción en solicitud_periodica.");
    }
} catch (Exception $e) {
    die("Error al insertar la solicitud: " . $e->getMessage());
}

// 🔹 **PASO 2: Insertar los elementos en `elementos_solicitud_periodica`**
try {
    if (!empty($_POST['cod_elem']) && is_array($_POST['cod_elem'])) {
        $stmt_elemento = $conexion->prepare("INSERT INTO elementos_solicitud_periodica 
            (id_solicitud, id_elemento, cantidad_solicitada, observaciones) 
            VALUES (?, ?, ?, ?)");

        foreach ($_POST['cod_elem'] as $i => $cod_elem) {
            $id_elemento = $_POST['id_elemento'][$i];
            $cantidad = $_POST['canti_elem'][$i];
            $observacion = $_POST['obser_elem'][$i] ?? "Sin observaciones";

            $stmt_elemento->bind_param("iiis", $id_solicitud, $id_elemento, $cantidad, $observacion);
            $stmt_elemento->execute();
        }

        $stmt_elemento->close();
    } else {
        throw new Exception("No se recibieron elementos para la solicitud.");
    }
} catch (Exception $e) {
    die("Error al insertar los elementos: " . $e->getMessage());
}

// 🔹 **PASO 3: Manejar los cuentadantes**
try {
    if ($id_tipo_cuentadante == 1) {
        // Unipersonal
        $nombre_unipersonal = $_POST['nombre_unipersonal'];
        $cedula_unipersonal = $_POST['cedula_unipersonal'];

        $stmt_cuentadante = $conexion->prepare("INSERT INTO cuentadante (nombre, documento, tipo) VALUES (?, ?, ?)");
        $stmt_cuentadante->bind_param("ssi", $nombre_unipersonal, $cedula_unipersonal, $id_tipo_cuentadante);
        $stmt_cuentadante->execute();
        $cuentadante_id = $conexion->insert_id;
        $stmt_cuentadante->close();

        $stmt_relacion = $conexion->prepare("INSERT INTO cuentadante_solicitud (id_cuentadante, id_solicitud) VALUES (?, ?)");
        $stmt_relacion->bind_param("ii", $cuentadante_id, $id_solicitud);
        $stmt_relacion->execute();
        $stmt_relacion->close();
    } elseif ($id_tipo_cuentadante == 2) {
        // Múltiple
        foreach ($_POST['nom_cuentadante1'] as $i => $nombre_cuentadante) {
            $documento_cuentadante = $_POST['doc_cuenta1'][$i];

            $stmt_cuentadante = $conexion->prepare("INSERT INTO cuentadante (nombre, documento, tipo) VALUES (?, ?, ?)");
            $stmt_cuentadante->bind_param("ssi", $nombre_cuentadante, $documento_cuentadante, $id_tipo_cuentadante);
            $stmt_cuentadante->execute();
            $cuentadante_id = $conexion->insert_id;
            $stmt_cuentadante->close();

            $stmt_relacion = $conexion->prepare("INSERT INTO cuentadante_solicitud (id_cuentadante, id_solicitud) VALUES (?, ?)");
            $stmt_relacion->bind_param("ii", $cuentadante_id, $id_solicitud);
            $stmt_relacion->execute();
            $stmt_relacion->close();
        }
    }
} catch (Exception $e) {
    die("Error al manejar los cuentadantes: " . $e->getMessage());
}

// 🔹 **PASO 4: Enviar correo electrónico**
 // Enviar correo electrónico usando PHPMailer
 $mail = new PHPMailer(true);
 try {
     // Configuración del servidor SMTP
     $mail->isSMTP();
     $mail->Host = 'smtp.gmail.com';
     $mail->SMTPAuth = true;
     $mail->Username = 'almacencenigraf@gmail.com';
     $mail->Password = 'xyivyvgjkzqueaeg';
     $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
     $mail->Port = 587;


     // Destinatarios
     $mail->setFrom('almacencenigraf@gmail.com', 'Almacen CENIGRAF');
     $mail->addAddress('maickgutierrez13@gmail.com',$correo_coordinador, $nombre_coor);

      // Adjuntar una imagen
 $imagePath = 'C:\xampp\htdocs\InventarioPHP\src\main\resources\templates\images\cenigraf.png';
 if (file_exists($imagePath)) {
     $mail->addEmbeddedImage($imagePath, 'logo_cenigraf');
 } else {
     throw new Exception("No se pudo acceder al archivo: $imagePath");
 }
     // Contenido del correo
     $mail->isHTML(true);
     $mail->Subject = 'Nueva Solicitud Periodica Enviada';
     $mail->Body = "
     <html>
     <head>
         <style>
             .container{
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
             <h2>Solicitud Periodica Generada</h2>
         </div>
         <div class='content'>
         <p>Se ha enviado una nueva solicitud periodica con la siguiente informacion:<br> </p>
                   <p><b>Fecha de Solicitud:</b> $fecha_solicitud<br></p>
                   <p><b>Codigo Regional:</b> $cod_regional<br></p>
                   <p><b>Codigo de Costos:</b> $cod_costos<br></p>
                   <p><b>Nombre del Coordinador:</b> $nombre_coor<br></p>
                   <p><b>Cargo:</b> $cargo<br></p>
                   <p><b>Area de Solicitud:</b> $area_solicitud<br></p>
                   <p><b>Nombre Regional:</b> $nom_regional<br></p>
                   <p><b>Nombre Centro de Costos:</b> $nom_centro_costos<br></p>
                   <p><b>Tipo de Cuentadante:</b> $id_tipo_cuentadante<br></p>
                   <p><b>Destino de los Bienes:</b> $destino<br></p>
                   <p><b>Codigo de grupo o numero de ficha:</b> $numero_ficha<br></p>
                   <p><b>Ingrese desde el siguiente link:<br></p>
                   <p><b>http://localhost/InventarioPHP/src/main/resources/templates<br>
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
     $_SESSION['correo_enviado'] = true;
 } catch (Exception $e) {
     echo "El correo no pudo ser enviado. Error: {$mail->ErrorInfo}";
 }

 // Redireccionar o realizar otras acciones después de la inserción exitosa
 header('Location:../Solicitud_periodica.php');
 exit();
// Manejar errores en la inserción de la solicitud
echo "Error en la inserción de solicitud_periodica: " . $stmt->error;

// Cerrar la consulta para la solicitud y la conexión
$stmt->close();
$conexion->close();
?>
