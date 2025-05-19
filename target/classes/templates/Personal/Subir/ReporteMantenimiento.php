<?php
session_start();

// Verificar sesión
if (!isset($_SESSION['id'])) {
    die("No se ha iniciado sesión.");
}

// Verificar parámetro 'vista'
if (!isset($_POST['vista']) || $_POST['vista'] !== 'solicitud_mantenimiento') {
    die("Acceso no autorizado.");
}

// Incluir conexión y PhpSpreadsheet
include('../../PHP/Conexion.php');
require '../../Composer/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

// Ruta de la plantilla
$plantilla = '../../Composer/vendor/Excel/solicitud_mantenimiento.xlsx';
if (!file_exists($plantilla)) {
    die("La plantilla no se encuentra en la ruta especificada.");
}

// Cargar plantilla
$spreadsheet = IOFactory::load($plantilla);
$sheet = $spreadsheet->getActiveSheet();

// Consulta SQL (última solicitud)
$sql = "SELECT sm.*, a.nombre_ambiente AS nombre_ambiente, tm.nombre AS tipo_mantenimiento, 
               i.nombre_instructor AS solicitante, i.cedula AS cedula, r.nombre AS cargo, m.*
        FROM solicitud_mantenimiento sm
        INNER JOIN tipo_mantenimiento tm ON sm.solicitud = tm.id
        INNER JOIN instructor i ON sm.id_instructor = i.id
        INNER JOIN ambiente a ON sm.id_ambiente = a.id_ambiente
        INNER JOIN rol r ON sm.id_rol = r.id_rol
        INNER JOIN maquina m ON sm.maquina = m.id
        ORDER BY sm.id DESC LIMIT 1";

$result = $conexion->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Llenar los campos en la hoja de Excel
    $sheet->setCellValue('C5', $row['fecha_soli']); // Fecha
    $sheet->setCellValue('C6', strtoupper($row['necesidad']));
    $sheet->setCellValue('H6', strtoupper($row['nombre_ambiente']));
    $sheet->setCellValue('C7', strtoupper($row['tipo_mantenimiento']));
    $sheet->setCellValue('C8', strtoupper($row['solicitante']));
    $sheet->setCellValue('I8', strtoupper($row['cedula']));
    $sheet->setCellValue('H16', strtoupper($row['cargo']));
    $sheet->setCellValue('C16', strtoupper($row['solicitante']));

    // Datos de la máquina
    $sheet->setCellValue('B14', strtoupper($row['nombre_maquina']));
    $sheet->setCellValue('C14', strtoupper($row['marca']));
    $sheet->setCellValue('D14', strtoupper($row['modelo']));
    $sheet->setCellValue('E14', strtoupper($row['placa']));
    $sheet->setCellValue('F14', strtoupper($row['serial']));
    $sheet->setCellValue('G14', strtoupper($row['cantidad']));
    $sheet->setCellValue('H14', strtoupper($row['tipo']));
    $sheet->setCellValue('I13', strtoupper($row['suministro']));

    // Observaciones
$observacionesTexto = "OBSERVACIONES:\n" . strtoupper($row['observaciones']);
$sheet->setCellValue('H15', $observacionesTexto);
$sheet->getStyle('H15')->getAlignment()->setWrapText(true);
$sheet->getRowDimension(15)->setRowHeight(80);
} else {
    echo "No se encontraron solicitudes de mantenimiento.";
    exit;
}

$conexion->close();

// Encabezados para la descarga
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="solicitud_de_mantenimiento.xlsx"');
header('Cache-Control: max-age=0');

// Descargar el archivo
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
?>