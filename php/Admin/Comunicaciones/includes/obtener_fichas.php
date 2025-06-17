<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../bd/conexion_test.php';

// Leer filtros enviados por GET
$numero_fip      = $_GET['numero_fip'] ?? '';
$nombre_proyecto = $_GET['nombre_proyecto'] ?? '';
$proponente      = $_GET['proponente'] ?? '';
$cargo           = $_GET['cargo'] ?? '';
$organizacion    = $_GET['organizacion'] ?? '';
$fecha_desde     = $_GET['fecha_desde'] ?? '';
$fecha_hasta     = $_GET['fecha_hasta'] ?? '';

$where  = [];
$params = [];
$tipos  = "";

// Condiciones y parámetros para filtros
if ($numero_fip !== '') {
    $where[] = 'p.numero_fip LIKE ?';
    $params[] = "%$numero_fip%";
    $tipos   .= "s";
}
if ($nombre_proyecto !== '') {
    $where[] = 'p.nombre LIKE ?';
    $params[] = "%$nombre_proyecto%";
    $tipos   .= "s";
}
if ($proponente !== '') {
    $where[] = 'pr.nombre_completo LIKE ?';
    $params[] = "%$proponente%";
    $tipos   .= "s";
}
if ($cargo !== '') {
    $where[] = 'pr.cargo_rol LIKE ?';
    $params[] = "%$cargo%";
    $tipos   .= "s";
}
if ($organizacion !== '') {
    $where[] = 'pr.organizacion LIKE ?';
    $params[] = "%$organizacion%";
    $tipos   .= "s";
}
if ($fecha_desde !== '') {
    $where[] = 'p.fecha_presentacion >= ?';
    $params[] = $fecha_desde;
    $tipos   .= "s";
}
if ($fecha_hasta !== '') {
    $where[] = 'p.fecha_presentacion <= ?';
    $params[] = $fecha_hasta;
    $tipos   .= "s";
}

// Consulta con JOIN para obtener datos completos
$sql = "SELECT 
            p.id_proyecto,
            p.numero_fip,
            p.nombre AS nombre_proyecto,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,
            pr.nombre_completo AS proponente,
            pr.cargo_rol AS cargo,
            pr.organizacion,
            pr.correo,
            pr.telefono
        FROM proyecto p
        LEFT JOIN proponente pr ON p.id_proponente = pr.id_proponente";


if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Error en la preparación de la consulta']);
    exit;
}

if (count($params) > 0) {
    $bind_names[] = $tipos;
    for ($i = 0; $i < count($params); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $params[$i];
        $bind_names[] = &$$bind_name;
    }
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}

$stmt->execute();
$result = $stmt->get_result();

$fichas = [];
while ($row = $result->fetch_assoc()) {
    $fichas[] = $row;
}

echo json_encode($fichas);

$stmt->close();
$conn->close();
