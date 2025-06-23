<?php

if (!isset($_SESSION['id_usuario'])) {
    echo "Usuario no autenticado.";
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

require_once __DIR__ . '/../bd/conexion_test.php';

// Consulta: contar proyectos por estado para el usuario actual
$sql = "SELECT ef.nombre_estado, COUNT(p.id_proyecto) AS cantidad
        FROM estado_fip ef
        LEFT JOIN proyecto p ON ef.id_estado = p.id_estado_actual AND p.id_usuario = ?
        WHERE ef.id_estado IN (1, 2, 3, 4, 5, 6, 7) -- Filtramos solo los estados relevantes
        GROUP BY ef.id_estado, ef.nombre_estado
        ORDER BY ef.id_estado";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$estado_proyectos = [];
while ($row = $result->fetch_assoc()) {
    $estado_proyectos[$row['nombre_estado']] = $row['cantidad'];
}

$stmt->close();
$conn->close();

// Devolver los resultados
return $estado_proyectos;
?>
