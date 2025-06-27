<?php
// notificaciones_recientes.php

require_once(__DIR__ . '/../../Comunicaciones/bd/conexion_test.php'); // Asegúrate de incluir la conexión a la base de datos

// Consulta SQL para obtener los últimos proyectos con el estado "Enviado" (id_estado = 5)
$sql = "SELECT 
            p.id_proyecto, 
            p.numero_fip,
            p.nombre as nombre_proyecto, 
            p.fecha_presentacion, 
            es.nombre_estado 
        FROM proyecto p
        JOIN estado_fip es ON p.id_estado_actual = es.id_estado
        WHERE es.id_estado = 5
        ORDER BY p.fecha_presentacion DESC
        LIMIT 5";  // Limitar a los últimos 5 proyectos

// Ejecutar la consulta
$result = $conn->query($sql);

// Verificar si hay resultados
$proyectos = [];
if ($result->num_rows > 0) {
    // Guardamos los resultados en un array
    while ($row = $result->fetch_assoc()) {
        $proyectos[] = $row;
    }
}


?>
