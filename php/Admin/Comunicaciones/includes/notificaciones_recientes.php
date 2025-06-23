<?php
// Conectar a la base de datos
require_once __DIR__ . '/../bd/conexion_test.php';

// Verificar si la conexión está activa
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener el ID del usuario logueado
$id_usuario = $_SESSION['id_usuario'];

// Consulta para obtener las últimas 5 notificaciones del historial de estado de los proyectos
$sql = "SELECT hp.fecha_cambio, hp.observaciones
        FROM historial_estado_proyecto hp
        JOIN proyecto p ON hp.id_proyecto = p.id_proyecto
        WHERE p.id_usuario = ?
        ORDER BY hp.fecha_cambio DESC
        LIMIT 5";

// Preparamos la consulta
$stmt = $conn->prepare($sql);

// Verificamos si la preparación de la consulta fue exitosa
if ($stmt === false) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$notificaciones = [];
while ($row = $result->fetch_assoc()) {
    $notificaciones[] = $row;
}

// No cerramos la conexión aún, la cerramos cuando ya no sea necesaria
// $stmt->close(); // Solo lo cerramos después de la ejecución

return $notificaciones;
?>
