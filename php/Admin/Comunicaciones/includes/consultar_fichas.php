<?php
// Asegúrate de que la sesión esté iniciada
// Verifica si el usuario está logueado y tiene el rol adecuado
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol'] !== 'proponente' || $_SESSION['area'] !== 'Comunicaciones') {
    // Si no está logueado o no tiene el rol adecuado, redirige al login
    header("Location: login.php?error=no_autorizado");
    exit();  // Asegúrate de llamar a exit() para que no se siga ejecutando el script
}

// Obtener id_usuario desde la sesión
$id_usuario = $_SESSION['id_usuario'];  // Obtiene el id_usuario desde la sesión

// Incluir archivo de conexión
require_once __DIR__ . '/../bd/conexion_test.php';

// Inicializar la variable $fichas como un array vacío
$fichas = [];

// Paginación
$limit = 10; // Número de fichas por página
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Página actual
$offset = ($page - 1) * $limit; // Offset para la consulta

// Filtrado
$where = "";
$params = [$id_usuario]; // Filtro por id_usuario

// Filtros (por ejemplo, filtrar por estado)
if (isset($_GET['estado']) && !empty($_GET['estado'])) {
    $where .= " AND es.id_estado = ?";  // Filtro por estado
    $params[] = $_GET['estado'];
}

// Filtro por fecha (si se desea, por ejemplo, filtrar por fecha de presentación)
if (isset($_GET['fecha_inicio']) && !empty($_GET['fecha_inicio']) && isset($_GET['fecha_fin']) && !empty($_GET['fecha_fin'])) {
    $where .= " AND p.fecha_presentacion BETWEEN ? AND ?";  // Filtro por rango de fechas
    $params[] = $_GET['fecha_inicio'];
    $params[] = $_GET['fecha_fin'];
}

// Consulta para obtener el número total de fichas sin paginación (para el cálculo de páginas)
$sql_count = "SELECT COUNT(*) AS total
              FROM proyecto p
              LEFT JOIN estado_fip es ON p.id_estado_actual = es.id_estado
              WHERE p.id_usuario = ? $where";  // Contamos el total de registros

$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param(str_repeat('s', count($params)), ...$params); // Vinculamos los parámetros
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$total_records = $result_count->fetch_assoc()['total']; // Total de registros
$stmt_count->close();

// Calcular el número total de páginas
$total_pages = ceil($total_records / $limit);

// Consulta para obtener las fichas del usuario, con paginación y filtros
$sql = "SELECT 
            p.id_proyecto,
            p.numero_fip,
            p.nombre AS nombre_proyecto,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,
            u.nombre AS nombre_usuario,
            u.apellido AS apellido_usuario,
            u.correo AS correo_usuario,
            u.rol AS cargo,
            u.area AS organizacion,
            es.nombre_estado AS estado_proyecto,
            es.id_estado AS id_estado
        FROM proyecto p
        LEFT JOIN usuarios u ON p.id_usuario = u.id_usuario
        LEFT JOIN estado_fip es ON p.id_estado_actual = es.id_estado 
        WHERE p.id_usuario = ? $where
        LIMIT ? OFFSET ?";  // Filtro por id_usuario, paginación y filtros adicionales

// Preparar la consulta
$stmt = $conn->prepare($sql);
$params[] = $limit;  // Agregar límite a los parámetros
$params[] = $offset; // Agregar offset a los parámetros
$stmt->bind_param(str_repeat('s', count($params)), ...$params); // Vinculamos todos los parámetros
$stmt->execute();
$result = $stmt->get_result();

// Agregar los resultados a la variable $fichas
while ($row = $result->fetch_assoc()) {
    $fichas[] = $row;
}

// Cerrar el statement
$stmt->close();

// No cerramos la conexión aquí, porque la conexión debe permanecer abierta hasta que hayas terminado de procesar todo.
?>
