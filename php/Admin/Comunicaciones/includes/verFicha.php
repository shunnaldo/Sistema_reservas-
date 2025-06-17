<?php
require_once __DIR__ . '/../bd/conexion_test.php';

if (!isset($_GET['id'])) {
    echo "No se indicó ID de ficha";
    exit;
}

$id = $_GET['id'];  // Suponiendo que el ID es numérico o alfanumérico seguro, si no usar sanitización

// Consulta para obtener la información general y datos relacionados
$sql = "SELECT 
            p.nombre_proyecto,
            p.fecha_presentacion,
            p.duracion,
            prop.nombre_completo,
            prop.cargo,
            prop.organizacion,
            prop.direccion,
            prop.telefono,
            prop.email,
            s.sectores,  -- asumir que tienes esta info concatenada o en tabla aparte
            p.ubicacion,
            p.region,
            p.municipio,
            p.sector_comuna,
            p.ambito_territorial
        FROM proyecto p
        LEFT JOIN proponente prop ON p.id_proponente = prop.id
        LEFT JOIN sectores s ON p.id = s.id_proyecto  -- ejemplo, adapta según estructura
        WHERE p.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);  // ajustar tipo si no es int
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Ficha no encontrada.";
    exit;
}

$data = $result->fetch_assoc();
?>
