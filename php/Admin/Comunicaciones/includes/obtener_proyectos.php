<?php
// Incluir la conexión a la base de datos
require_once(__DIR__ . '/../bd/conexion_test.php');  // Asegúrate de que la ruta sea correcta

// Verificar si el usuario está logueado y tiene un id_proponente
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Por favor inicie sesión para acceder a los proyectos.");
}

$id_usuario = $_SESSION['id_usuario']; // Obtener el id del usuario logueado

// Inicializar contadores
$en_proceso = 0;
$aprobadas = 0;
$rechazadas = 0;

// Consulta para obtener la cantidad de proyectos por estado
$sql = "
    SELECT 
        COUNT(*) AS total_proyectos,
        es.nombre_estado AS estado
    FROM 
        proyecto p
    JOIN 
        estado_fip es ON p.id_estado_actual = es.id_estado
    WHERE 
        p.id_usuario = ?
    GROUP BY 
        es.nombre_estado
";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario); // Vincula el id_usuario al parámetro
$stmt->execute();
$result = $stmt->get_result();

// Asignar las cantidades a las variables correspondientes según el estado
while ($row = $result->fetch_assoc()) {
    if ($row['estado'] == 'En Proceso') {
        $en_proceso = $row['total_proyectos'];
    } elseif ($row['estado'] == 'Aprobada') {
        $aprobadas = $row['total_proyectos'];
    } elseif ($row['estado'] == 'Rechazada') {
        $rechazadas = $row['total_proyectos'];
    }elseif ($row['estado'] == 'Realizado') {
        $rechazadas = $row['total_proyectos'];
    }
}

// Cerrar la conexión
$stmt->close();
$conn->close();

// Devolver los valores para usarlos en el archivo principal
return [
    'en_proceso' => $en_proceso,
    'aprobadas' => $aprobadas,
    'rechazadas' => $rechazadas,
    'realizadas' => $realizadas
];
?>
