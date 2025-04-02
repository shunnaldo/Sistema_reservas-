<?php
require_once '../conexion.php';

// Actualizar estados de las reservas
$updateQuery = "
    UPDATE Reservas 
    SET estado =  
        CASE 
            WHEN NOW() < CONCAT(fecha, ' ', hora_inicio) AND estado != 'pendiente' THEN 'pendiente'
            WHEN NOW() BETWEEN CONCAT(fecha, ' ', hora_inicio) AND CONCAT(fecha, ' ', hora_fin) AND estado != 'lista' THEN 'lista'
            WHEN NOW() > CONCAT(fecha, ' ', hora_fin) AND estado != 'finalizada' THEN 'finalizada'
            ELSE estado
        END;
";
$conexion->query($updateQuery);

// Obtener reservas actualizadas
$sql = "SELECT * FROM Reservas ORDER BY FIELD(estado, 'lista', 'pendiente', 'finalizada'), fecha, hora_inicio";
$result = $conexion->query($sql);

// Construir el HTML de la tabla (solo el `<tbody>`)
$output = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $estadoClass = '';

        switch ($row['estado']) {
            case 'pendiente':
                $estadoClass = 'table-warning';
                break;
            case 'lista':
                $estadoClass = 'table-success';
                break;
            case 'finalizada':
                $estadoClass = 'table-danger';
                break;
        }

        $output .= "<tr class='$estadoClass'>
                        <td>{$row['nombre_vecino']}</td>
                        <td>{$row['apellido_vecino']}</td>
                        <td>{$row['rut']}</td>
                        <td>{$row['correo_vecino']}</td>
                        <td>{$row['fecha']}</td>
                        <td>{$row['hora_inicio']}</td>
                        <td>{$row['hora_fin']}</td>
                        <td>{$row['fecha_creacion']}</td>
                        <td>{$row['cowork']}</td>
                        <td>{$row['estado']}</td>
                        <td>
                            <input type='checkbox' class='check-asistencia' data-id='{$row['id']}' ".($row['check_asistencia'] ? "checked" : "").">
                        </td>
                    </tr>";
    }
} else {
    $output .= "<tr><td colspan='11'>No hay reservas disponibles</td></tr>";
}

echo $output;
?>
