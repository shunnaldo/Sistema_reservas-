<?php
require_once __DIR__ . '/../bd/conexion_test.php';

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$sql = "SELECT 
            p.numero_fip,
            p.nombre,
            p.fecha_presentacion,
            p.duracion_valor,
            p.duracion_tipo,
            pr.nombre_completo,
            pr.cargo_rol,
            pr.organizacion,
            pr.correo,
            pr.telefono
        FROM proyecto p
        INNER JOIN proponente pr ON p.id_proponente = pr.id_proponente
        ORDER BY p.fecha_presentacion DESC";

$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=fichas_enviadas.csv');

// BOM para Excel
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

// Definir delimitador personalizado
$delimiter = ';';

// Escribir cabecera
fputcsv($output, [
    'N° FIP',
    'Proyecto',
    'Fecha',
    'Duración Valor',
    'Duración Tipo',
    'Proponente',
    'Cargo',
    'Organización',
    'Correo',
    'Teléfono'
], $delimiter);

// Escribir filas
while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        '="' . $row['numero_fip'] . '"', // <- Así Excel lo interpreta como texto
        $row['nombre'],
        $row['fecha_presentacion'],
        $row['duracion_valor'],
        $row['duracion_tipo'],
        $row['nombre_completo'],
        $row['cargo_rol'],
        $row['organizacion'],
        $row['correo'],
        $row['telefono']
    ], $delimiter);
}

fclose($output);
$conn->close();
exit;
