<?php
// Verificar si el id_proyecto está presente en la URL
if (!isset($_GET['id_proyecto']) || empty($_GET['id_proyecto'])) {
    echo "<p>Proyecto no encontrado o no tienes acceso a este proyecto.</p>";
    exit();
}

$id_proyecto = $_GET['id_proyecto'];  // Obtiene el id del proyecto desde la URL

// Conexión a la base de datos
require_once __DIR__ . '/../bd/conexion_test.php';

// Recuperamos los datos del proyecto
$sql = "SELECT 
            p.numero_fip,
            p.fecha_presentacion,
            fg.tipo_requerimiento,
            fg.area_requerente,
            fg.encargado_requerimiento
        FROM proyecto p
        LEFT JOIN ficha_informacion_general fg ON p.id_proyecto = fg.id_proyecto
        WHERE p.id_proyecto = ?";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_proyecto);
$stmt->execute();
$result = $stmt->get_result();

// Comprobar si la consulta devuelve resultados
if ($result->num_rows === 0) {
    echo "<p>No se encontró la ficha de información general para este proyecto.</p>";
    exit();
}

// Obtener los datos del proyecto
$data = $result->fetch_assoc();

// Depuración: Mostrar los datos del proyecto


$stmt->close();

// Ahora, recuperamos los datos de los materiales:
$sql_materiales = "SELECT 
                        dm.cantidad,
                        dm.producto_servicio,
                        dm.especificaciones_tecnicas,
                        dm.unidad,
                        dm.observaciones
                    FROM detalle_materiales dm
                    LEFT JOIN ficha_informacion_general fg ON fg.id_ficha = dm.id_ficha
                    WHERE fg.id_proyecto = ?";

// Preparar la consulta de materiales
$stmt_materiales = $conn->prepare($sql_materiales);
$stmt_materiales->bind_param("i", $id_proyecto);
$stmt_materiales->execute();
$result_materiales = $stmt_materiales->get_result();

// Comprobar si hay materiales
if ($result_materiales->num_rows === 0) {
    echo "<p>No se encontraron materiales para este proyecto.</p>";
    exit();
}

// Obtener los datos de los materiales
$materiales = [];
while ($row = $result_materiales->fetch_assoc()) {
    $materiales[] = $row; // Almacenamos todos los resultados de materiales en un array
}

// Depuración: Mostrar los materiales recuperados


$stmt_materiales->close();
$conn->close();
?>
