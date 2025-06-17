<?php
// Incluir la conexión a la base de datos
require_once(__DIR__ . '/../bd/conexion_test.php');  // Asegúrate de que la ruta sea correcta

// Verificar si el usuario está logueado y tiene un id_proponente
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Por favor inicie sesión para acceder a los proyectos.");
}
?>
<?php
// Función para obtener las fichas con filtros opcionales
function obtenerFichas($filtros = []) {
    global $conn;  // Aseguramos que la conexión sea accesible

    // Comenzamos la consulta básica
    $sql = "SELECT * FROM proyecto WHERE 1";  // El '1' es un placeholder para que podamos agregar condiciones fácilmente

    // Filtramos por id_proponente si el usuario está logueado
    if (isset($_SESSION['id_proponente'])) {
        $sql .= " AND id_proponente = " . $_SESSION['id_proponente'];
    }

    // Aplicamos filtros adicionales si se proporcionan
    if (!empty($filtros['numero_fip'])) {
        $sql .= " AND numero_fip LIKE '%" . $filtros['numero_fip'] . "%'";
    }
    if (!empty($filtros['nombre_proyecto'])) {
        $sql .= " AND nombre_proyecto LIKE '%" . $filtros['nombre_proyecto'] . "%'";
    }
    if (!empty($filtros['proponente'])) {
        $sql .= " AND proponente LIKE '%" . $filtros['proponente'] . "%'";
    }
    if (!empty($filtros['cargo'])) {
        $sql .= " AND cargo LIKE '%" . $filtros['cargo'] . "%'";
    }
    if (!empty($filtros['organizacion'])) {
        $sql .= " AND organizacion LIKE '%" . $filtros['organizacion'] . "%'";
    }
    if (!empty($filtros['fecha_desde'])) {
        $sql .= " AND fecha_presentacion >= '" . $filtros['fecha_desde'] . "'";
    }
    if (!empty($filtros['fecha_hasta'])) {
        $sql .= " AND fecha_presentacion <= '" . $filtros['fecha_hasta'] . "'";
    }

    // Ejecutamos la consulta
    $result = $conn->query($sql);
    
    // Si hay resultados, los almacenamos en un array
    $fichas = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $fichas[] = $row;
        }
    }

    return $fichas;
}
?>


