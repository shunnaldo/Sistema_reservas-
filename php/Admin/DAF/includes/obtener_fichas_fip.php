<?php
// obtener_fichas_fip.php
require_once(__DIR__ . '/../../Comunicaciones/bd/conexion_test.php'); // Asegúrate de incluir la conexión a la base de datos

// Inicializar filtro de estado
$estado = isset($_GET['estado']) ? (int)$_GET['estado'] : 5; // Por defecto, el estado es "Enviado" (id_estado = 5)

// Paginación
$limit = 10; // Número de fichas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual (por defecto es la 1)
$offset = ($page - 1) * $limit; // Calcular el OFFSET para la paginación

// Consulta para contar el total de proyectos con el estado seleccionado (sin la paginación)
$sql_count = "SELECT COUNT(*) AS total
              FROM proyecto p
              JOIN estado_fip es ON p.id_estado_actual = es.id_estado
              WHERE es.id_estado = ?"; // Filtro por estado seleccionado

$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param('i', $estado); // Vincular el parámetro de estado
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_records = $result_count->fetch_assoc()['total']; // Total de registros
$total_pages = ceil($total_records / $limit); // Calcular el número total de páginas
$stmt_count->close();

// Consulta SQL para obtener los proyectos con el estado seleccionado
$sql = "SELECT 
            p.id_proyecto, 
            p.numero_fip, 
            p.nombre AS nombre_proyecto,
            p.fecha_presentacion, 
            p.duracion_valor, 
            p.duracion_tipo, 
            u.nombre, 
            u.apellido, 
            u.rol, 
            u.area, 
            es.nombre_estado, 
            es.id_estado
        FROM proyecto p
        JOIN usuarios u ON p.id_usuario = u.id_usuario
        JOIN estado_fip es ON p.id_estado_actual = es.id_estado
        WHERE es.id_estado = ?"; // Filtro por estado seleccionado

// Agregar LIMIT y OFFSET para la paginación
$sql .= " LIMIT ? OFFSET ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param('sii', $estado, $limit, $offset); // Vincular el parámetro de estado, limit y offset
$stmt->execute();
$result = $stmt->get_result();

// Verificar si hay resultados
$fichas = [];
if ($result->num_rows > 0) {
    // Guardamos los resultados en un array
    while ($row = $result->fetch_assoc()) {
        $fichas[] = $row;
    }
}

// Cerrar el statement
$stmt->close();
$conn->close(); // Cerrar la conexión a la base de datos
?>
