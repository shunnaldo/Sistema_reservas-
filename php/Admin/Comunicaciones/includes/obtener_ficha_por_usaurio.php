<?php
header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

// Inicia la sesión para poder verificar el id_usuario
session_start();

// Verifica si el id_usuario está disponible en la sesión
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['error' => 'No autorizado']);
    exit(); // Detiene la ejecución si el usuario no está autenticado
}

// Recupera el id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario']; 

// Conexión a la base de datos
require_once __DIR__ . '/../bd/conexion_test.php';

// Verificación de que el id_usuario esté correctamente guardado en la base de datos
// Realizar una consulta rápida para ver si el id_usuario está en la base de datos
$sqlCheckUsuario = "SELECT id_usuario FROM usuarios WHERE id_usuario = ?";
$stmtCheck = $conn->prepare($sqlCheckUsuario);
$stmtCheck->bind_param("i", $id_usuario); // Enlazamos el id_usuario
$stmtCheck->execute();
$resultCheck = $stmtCheck->get_result();

if ($resultCheck->num_rows === 0) {
    echo json_encode(['error' => 'No se encuentra el usuario en la base de datos.']);
    exit(); // Si no encontramos el usuario, salimos del script
}

// Consulta SQL para obtener todas las fichas asociadas al usuario
$sql = "SELECT 
            p.id_proyecto,
            p.numero_fip,
            p.nombre AS nombre_proyecto,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            u.correo AS correo_usuario
        FROM proyecto p
        LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
        WHERE p.id_usuario = ?"; // Filtro por id_usuario

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario); // Enlazamos el id_usuario
$stmt->execute();
$result = $stmt->get_result();

// Verificamos si se obtuvo algún resultado
$fichas = [];
while ($row = $result->fetch_assoc()) {
    $fichas[] = $row; // Agregamos cada ficha al array
}

// Si no hay resultados
if (empty($fichas)) {
    echo json_encode(['error' => 'No se encontraron fichas para este usuario']);
} else {
    echo json_encode($fichas); // Devolvemos todas las fichas en formato JSON
}

$stmt->close();
$conn->close();
