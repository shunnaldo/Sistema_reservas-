<?php
require_once __DIR__ . '/../../Comunicaciones/bd/conexion_test.php';

// Verificar si la conexión a la base de datos fue exitosa
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$conn->set_charset("utf8");

// Verificar que el id_proyecto esté presente en la URL
if (!isset($_GET['id_proyecto']) || empty($_GET['id_proyecto'])) {
    echo "<p>Proyecto no encontrado o no tienes acceso a este proyecto.</p>";
    exit();
}

$id_proyecto = $_GET['id_proyecto'];  // Recuperamos el id del proyecto desde la URL

// Consulta para obtener los datos de los materiales
$sql_materiales = "SELECT 
                        dm.cantidad,
                        dm.producto_servicio,
                        dm.especificaciones_tecnicas,
                        dm.unidad,
                        dm.observaciones
                    FROM detalle_materiales dm
                    LEFT JOIN ficha_informacion_general fg ON fg.id_ficha = dm.id_ficha
                    WHERE fg.id_proyecto = ?";

$stmt_materiales = $conn->prepare($sql_materiales);
$stmt_materiales->bind_param("i", $id_proyecto);
$stmt_materiales->execute();
$result_materiales = $stmt_materiales->get_result();

// Comprobar si la consulta devuelve resultados
if ($result_materiales->num_rows === 0) {
    echo "<p>No se encontraron materiales para este proyecto.</p>";
    exit();
}

// Definir encabezados para la descarga CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=materiales_proyecto_' . $id_proyecto . '.csv');

// BOM para Excel (para evitar problemas de codificación)
echo "\xEF\xBB\xBF";

// Abrir la salida CSV
$output = fopen('php://output', 'w');

// Definir delimitador
$delimiter = ';';

// Escribir la cabecera del archivo CSV
fputcsv($output, [
    'Cantidad',
    'Producto/Servicio',
    'Especificaciones Técnicas',
    'Unidad',
    'Observaciones'
], $delimiter);

// Escribir los datos de los materiales
while ($row = $result_materiales->fetch_assoc()) {
    fputcsv($output, [
        $row['cantidad'],
        $row['producto_servicio'],
        $row['especificaciones_tecnicas'],
        $row['unidad'],
        $row['observaciones']
    ], $delimiter);
}

// Cerrar el archivo CSV
fclose($output);
$conn->close();
exit;
?>