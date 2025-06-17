<?php
// Conexión a la base de datos
require_once __DIR__ . '/../bd/conexion_test.php';

// Obtener el id_proyecto desde la URL
if (isset($_GET['id_proyecto'])) {
    $id_proyecto = $_GET['id_proyecto'];
} else {
    echo "<p>Proyecto no encontrado.</p>";
    exit();
}
// Consultar los detalles del proyecto
$query = "SELECT p.nombre AS nombre_proyecto, p.numero_fip, p.fecha_presentacion, h.fecha_cambio, e.nombre_estado AS estado, h.observaciones, h.usuario_modificador
          FROM historial_estado_proyecto h
          INNER JOIN estado_fip e ON h.id_estado = e.id_estado
          INNER JOIN proyecto p ON h.id_proyecto = p.id_proyecto
          WHERE h.id_proyecto = ?
          ORDER BY h.fecha_cambio DESC LIMIT 1"; // Última entrada
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_proyecto);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró el proyecto
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc(); // Guardamos los resultados en $data
} else {
    echo "<p>Proyecto no encontrado o no tienes acceso a este proyecto.</p>";
    exit();
}

// Consultar el historial completo de estados
$query_historial = "SELECT h.fecha_cambio, e.nombre_estado AS estado, h.observaciones, h.usuario_modificador
                    FROM historial_estado_proyecto h
                    INNER JOIN estado_fip e ON h.id_estado = e.id_estado
                    WHERE h.id_proyecto = ?
                    ORDER BY h.fecha_cambio DESC"; // Todos los estados
$stmt_historial = $conn->prepare($query_historial);
$stmt_historial->bind_param("i", $id_proyecto);
$stmt_historial->execute();
$result_historial = $stmt_historial->get_result();

// Almacenar los resultados del historial
$data['historial'] = $result_historial;
?>