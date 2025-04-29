<?php
session_start();

if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'proyecto') {
    header("Location: loginAdmin.php?error=No tienes permisos para entrar a este apartado.");
    exit;
}

include_once __DIR__ . "/../conexion.php";

// Insertar taller
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    if (!empty($nombre)) {
        $stmt = $conn->prepare("INSERT INTO talleres (nombre, activo) VALUES (?, 1)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        header("Location: talleresProyectos.php");
        exit;
    }
}

// Eliminar taller
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $stmt = $conn->prepare("DELETE FROM talleres WHERE id = ?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();
    header("Location: talleresProyectos.php");
    exit;
}

// Cambiar estado activo/inactivo
if (isset($_GET['toggle'])) {
    $idToggle = intval($_GET['toggle']);
    $stmt = $conn->prepare("UPDATE talleres SET activo = NOT activo WHERE id = ?");
    $stmt->bind_param("i", $idToggle);
    $stmt->execute();
    header("Location: talleresProyectos.php");
    exit;
}

// Obtener lista de talleres
$talleres = $conn->query("SELECT * FROM talleres ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Talleres</title>
    <link rel="stylesheet" href="../../css/talleres.css">
</head>
<body>

<div id="navbarproyecto-container"></div>

<div class="container-fluid">
    <div class="container-center">

        <h1>Administrar Talleres</h1>

        <!-- Formulario para agregar taller -->
        <form action="" method="POST">
            <input type="text" name="nombre" placeholder="Nombre del taller" required>
            <button type="submit" name="agregar">Agregar Taller</button>
        </form>

        <hr>

        <!-- Mostrar lista de talleres -->
        <h2>Lista de Talleres</h2>
        <table border="1" cellpadding="10">
            <tr>
                <th>Nombre</th>
                <th>Activo</th>
                <th>Eliminar</th>
            </tr>
            <?php while ($fila = $talleres->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($fila['nombre']) ?></td>
                    <td>
                        <form method="GET" style="display:inline;">
                            <input type="hidden" name="toggle" value="<?= $fila['id'] ?>">
                            <input type="checkbox" onchange="this.form.submit()" <?= $fila['activo'] ? 'checked' : '' ?>>
                        </form>
                    </td>
                    <td>
                        <a href="?eliminar=<?= $fila['id'] ?>" onclick="return confirm('¿Estás seguro de eliminar este taller?');">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

    </div>
</div>

<script src="../../js/sidebarproyecto.js"></script>
<script src="../../js/sidebar.js"></script>  

</body>
</html>
