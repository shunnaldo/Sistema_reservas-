<?php
session_start();

// Verificar si el usuario está logueado y tiene los permisos necesarios
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'DAF') {
    header("Location: login.php?error=no_autorizado");
    exit();
}

// Conexión a la base de datos
require_once __DIR__ . '/../../Comunicaciones/bd/conexion_test.php';  // Ruta ajustada

// Verificar que se haya recibido el id_proyecto y el estado
if (isset($_POST['id_proyecto']) && isset($_POST['estado'])) {
    $id_proyecto = $_POST['id_proyecto'];
    $estado = $_POST['estado'];

    // Actualizar el estado en la base de datos
    $sql = "UPDATE proyecto SET id_estado_actual = (SELECT id_estado FROM estado_fip WHERE nombre_estado = ?) WHERE id_proyecto = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error en prepare SQL: " . $conn->error);
    }

    // Vinculamos los parámetros para la actualización
    $stmt->bind_param("si", $estado, $id_proyecto);

    if ($stmt->execute()) {
        // Redirigir a la página de detalles del proyecto con un mensaje de éxito
        header("Location: detalle_ficha_fip.php?id_proyecto=" . $id_proyecto . "&estado_cambiado=true");
    } else {
        // En caso de error, mostrar un mensaje
        echo "Error al cambiar el estado: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No se han recibido datos válidos.";
}

$conn->close();
?>
