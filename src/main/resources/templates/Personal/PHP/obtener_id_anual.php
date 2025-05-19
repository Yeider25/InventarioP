<?php
header('Content-Type: application/json');
include ('../../PHP/Conexion.php');

$id_anual = null;
$consulta_id_anual = "SELECT id_anual FROM solicitud_anual ORDER BY id_anual DESC LIMIT 1";
$resultado_id_anual = mysqli_query($conexion, $consulta_id_anual);

if ($resultado_id_anual && mysqli_num_rows($resultado_id_anual) > 0) {
    $fila_id_anual = mysqli_fetch_assoc($resultado_id_anual);
    $id_anual = $fila_id_anual['id_anual'];
    echo json_encode(["status" => "success", "id_anual" => $id_anual]);
} else {
    echo json_encode(["status" => "error", "message" => "No se encontró ningún ID Anual."]);
}
?>