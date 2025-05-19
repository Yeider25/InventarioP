<?php
session_start();

if (!isset($_SESSION['id'])) {
    die("No se ha iniciado sesión.");
}

if (!isset($_POST['vista']) || $_POST['vista'] !== 'solicitud_anual') {
    die("Acceso no autorizado.");
}

$vista = $_POST['vista'];

include('../../PHP/Conexion.php');
require '../../Composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$plantilla = '../../Composer/vendor/Excel/solicitud_anual.xlsx';

if (!file_exists($plantilla)) {
    die("La plantilla no se encuentra en la ruta especificada.");
}

$spreadsheet = IOFactory::load($plantilla);
$sheet = $spreadsheet->getActiveSheet();

if (!isset($_POST['id_anual'])) {
    echo "No se recibió el ID de la solicitud.";
    exit;
}

$id_anual = $_POST['id_anual'];

$sql_info = "SELECT fecha_soli, nombre_solici, documento, ficha_soli, programa_soli, cantidad_soli FROM solicitud_anual WHERE id_anual = ?";
$stmt_info = $conexion->prepare($sql_info);
$stmt_info->bind_param("i", $id_anual);
$stmt_info->execute();
$result_info = $stmt_info->get_result();

if ($result_info && $result_info->num_rows > 0) {
    $row_info = $result_info->fetch_assoc();

    $fecha_solicitud     = $row_info['fecha_soli'];
    $nombre_solicitante  = $row_info['nombre_solici'];
    $documento           = $row_info['documento'];
    $ficha               = $row_info['ficha_soli'];
    $programa            = $row_info['programa_soli'];
    $cantidad_solicitada = $row_info['cantidad_soli'];
} else {
    echo "⚠️ No se encontró la solicitud con el ID: $id_anual.";
    exit;
}

// Rellenar la hoja Excel}
$sheet->setCellValue('A1', 'Fecha de Solicitud');
$sheet->setCellValue('B1', $fecha_solicitud);
$sheet->setCellValue('A2', 'Nombre del Solicitante');
$sheet->setCellValue('B2', $nombre_solicitante);
$sheet->setCellValue('A3', 'Documento');
$sheet->setCellValue('B3', $documento);
$sheet->setCellValue('A4', 'Ficha');
$sheet->setCellValue('B4', $ficha);
$sheet->setCellValue('A5', 'Programa');
$sheet->setCellValue('B5', $programa);

$sql = "SELECT * FROM solicitud_anual WHERE id_anual = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_anual);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sheet->setCellValue('C5', $row['fecha_soli']);
    $sheet->setCellValue('C6', strtoupper($row['nombre_solici']));
    $sheet->setCellValue('B28', strtoupper($row['nombre_solici']));
    $sheet->setCellValue('H6', strtoupper($row['documento']));
    $sheet->setCellValue('E9', strtoupper($row['ficha_soli']));
    $sheet->setCellValue('G12', strtoupper($row['cantidad_soli']));

    $programa_id = $row['programa_soli'];
    $sql_programa = "SELECT nombre_programa FROM programa WHERE id_programa = ?";
    $stmt_programa = $conexion->prepare($sql_programa);
    $stmt_programa->bind_param("i", $programa_id);
    $stmt_programa->execute();
    $result_programa = $stmt_programa->get_result();

    if ($result_programa->num_rows > 0) {
        $row_programa = $result_programa->fetch_assoc();
        $sheet->setCellValue('C8', strtoupper($row_programa['nombre_programa']));
    } else {
        $sheet->setCellValue('C8', "Programa no encontrado");
    }

    $sql_elemento = "
        SELECT e.nombre, e.und_medida, e.cantidad
FROM elemento e
INNER JOIN elemento_solicitud_anual ea ON e.id_elemento = ea.id_elemento
WHERE ea.id_solicitud = ?";
    $stmt_elemento = $conexion->prepare($sql_elemento);
    $stmt_elemento->bind_param('i', $id_anual);
    $stmt_elemento->execute();
    $result_elemento = $stmt_elemento->get_result();

    $row_number = 12;
    while ($row_elemento = $result_elemento->fetch_assoc()) {
        $sheet->setCellValue('B' . $row_number, strtoupper($row_elemento['nombre']));
        $sheet->setCellValue('C' . $row_number, strtoupper($row_elemento['und_medida']));
        $row_number++;
    }
} else {
    echo "No se encontraron solicitudes.";
    exit;
}

$stmt->close();
$conexion->close();

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="solicitud_anual.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>