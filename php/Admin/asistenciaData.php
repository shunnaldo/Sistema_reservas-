<?php
include_once __DIR__ . "/../conexion.php";

$fechaFiltro = isset($_GET['fecha']) && $_GET['fecha'] !== '' ? $_GET['fecha'] : null;

$talleres = $conn->query("SELECT * FROM talleres ORDER BY nombre");

while ($taller = $talleres->fetch_assoc()):
    $idTaller = $taller['id'];
    ?>
    <details class="taller-box">
        <summary><?= htmlspecialchars($taller['nombre']) ?></summary>
        <?php
        $queryFechas = "
            SELECT DISTINCT DATE(fecha_hora) as fecha 
            FROM asistencia_talleres 
            WHERE id_taller = $idTaller " . ($fechaFiltro ? "AND DATE(fecha_hora) = '$fechaFiltro'" : "") . "
            ORDER BY fecha DESC
        ";
        $asistencias = $conn->query($queryFechas);

        while ($filaFecha = $asistencias->fetch_assoc()):
            $fecha = $filaFecha['fecha'];
            $personas = $conn->query("
                SELECT nombre, apellido, rut, correo 
                FROM asistencia_talleres 
                WHERE id_taller = $idTaller AND DATE(fecha_hora) = '$fecha'
                ORDER BY apellido, nombre
            ");
            ?>
            <div class="fecha-label"><?= date("d-m-Y", strtotime($fecha)) ?></div>
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>RUT</th>
                    <th>Correo</th>
                </tr>
                <?php while ($p = $personas->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['apellido']) ?></td>
                        <td><?= htmlspecialchars($p['rut']) ?></td>
                        <td><?= htmlspecialchars($p['correo']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endwhile; ?>
    </details>
<?php endwhile; ?>
