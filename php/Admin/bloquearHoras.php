<?php
session_start();

// Verificar permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para registrar usuarios.");
    exit;
}

require_once '../conexion.php'; // Asegúrate de que esta ruta sea correcta

// Eliminar un día si se envió el ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
    $eliminarId = $_POST['eliminar_id'];

    $stmt = $conexion->prepare("DELETE FROM diasBloqueados WHERE id = ?");
    $stmt->bind_param("i", $eliminarId);
    $stmt->execute();
    $stmt->close();

    header("Location: bloquearHoras.php"); // Redirigir para evitar reenvío
    exit;
}

// Guardar nuevo día bloqueado si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fecha']) && !isset($_POST['eliminar_id'])) {
    $fecha = $_POST['fecha'];
    $motivo = $_POST['motivo'] ?? null;

    $stmt = $conexion->prepare("INSERT INTO diasBloqueados (fecha, motivo) VALUES (?, ?)");
    $stmt->bind_param("ss", $fecha, $motivo);
    $stmt->execute();
    $stmt->close();
}

// Obtener días bloqueados
$diasBloqueados = $conexion->query("SELECT * FROM diasBloqueados ORDER BY fecha ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bloquear días</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/bloquearHoras.css">
</head>
<body>

<div id="navbarAdmin-container"></div>

<div class="container-center">
    <div class="bloqueo-card">
        <h2 class="text-center">Bloquear un día específico</h2>

        <form method="POST" class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Fecha a bloquear:</label>
                <input type="date" name="fecha" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label class="form-label">Motivo (opcional):</label>
                <input type="text" name="motivo" class="form-control" placeholder="Ej: Mantención, feriado...">
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary">Bloquear Día</button>
            </div>
        </form>

        <h4>Días bloqueados</h4>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Motivo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $diasBloqueados->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fecha']) ?></td>
                        <td><?= $row['motivo'] ? htmlspecialchars($row['motivo']) : 'Sin motivo' ?></td>
                        <td class="text-end">
                            <form method="POST" style="display:inline;" onsubmit="return confirm('¿Estás seguro de eliminar este día?');">
                                <input type="hidden" name="eliminar_id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../../js/navbarAdmin.js"></script>
<script src="../../js/sidebar.js"></script>

</body>
</html>
