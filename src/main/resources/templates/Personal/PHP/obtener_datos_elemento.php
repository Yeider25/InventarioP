<?php
/*
Este archivo obtiene los datos de un elemento específico de la base de datos 
para mostrarlos en un modal de edición. 

Recibe el ID del elemento mediante el método POST y devuelve la unidad de medida 
y la cantidad en formato JSON.
*/

include ('../../PHP/Conexion.php');

if (isset($_POST['id_elemento'])) {
    $id_elemento = $_POST['id_elemento'];

    //Verificacion de el envio de los datos atravez de la solicitud POST
    $consulta = "SELECT und_medida, cantidad FROM elemento WHERE id_elemento = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("i", $id_elemento);
    $stmt->execute();
    $resultado = $stmt->get_result();

    //Consulta para seleccionar los datos und_medida y cantidad de la tabla elemento
    if ($resultado->num_rows > 0) {
        $fila = $resultado->fetch_assoc();
        echo json_encode([
            'success' => true,
            'unidad_medida' => $fila['und_medida'],
            'cantidad' => $fila['cantidad'],
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontraron datos para el elemento.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID del elemento no proporcionado.']);
}

$conexion->close();
?>
