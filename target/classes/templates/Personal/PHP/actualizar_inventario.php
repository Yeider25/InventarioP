<?php
header('Content-Type: application/json');
require '../../PHP/Conexion.php';

if (!isset($_POST['id_solicitud'], $_POST['id_elemento'], $_POST['solicitada'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Datos incompletos.",
        "debug" => $_POST
    ]);
    exit;
}

$id_solicitud = intval($_POST['id_solicitud']);
$id_elemento = intval($_POST['id_elemento']);
$cantidad = intval($_POST['solicitada']);

if ($cantidad <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "La cantidad debe ser mayor que cero."
    ]);
    exit;
}

$sql = "UPDATE elemento_solicitud_anual SET cantidad = ? WHERE id_solicitud = ? AND id_elemento = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Error en la preparación de la consulta."
    ]);
    exit;
}

$stmt->bind_param("iii", $cantidad, $id_solicitud, $id_elemento);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Cantidad actualizada correctamente."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Error al actualizar: " . $stmt->error
    ]);
}
$stmt->close();
?>


