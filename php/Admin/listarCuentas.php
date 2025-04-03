<?php
// Iniciar sesión
session_start();

// Verificar si el administrador está logueado y tiene permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para ver las cuentas.");
    exit;
}

// Incluir el archivo de conexión 
include('../conexion.php');

// Consultar las cuentas de los administradores y staff
$sql = "SELECT id_usuario, nombre, correo, rol FROM Administradores";
$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Cuentas</title>
    <link rel="stylesheet" href="../../css/listarCuentas.css">
</head>
<body>

<div id="navbarAdmin-container"></div>

<div class="main-container">
    <div class="container-center">
        <h2>Lista de Cuentas</h2>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($row['correo']); ?></td>
                        <td><?php echo htmlspecialchars($row['rol']); ?></td>
                        <td class="action-buttons">
                            <a href="modificarCuenta.php?id=<?php echo $row['id_usuario']; ?>" class="edit-btn">
                                <i class="ri-edit-line"></i> Modificar
                            </a>
                            <a href="borrarCuenta.php?id=<?php echo $row['id_usuario']; ?>" class="delete-btn" onclick="return confirm('¿Estás seguro de eliminar esta cuenta?')">
                                <i class="ri-delete-bin-line"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<script src="../../js/navbarAdmin.js"></script>
<script src="../../js/sidebar.js"></script>

</body>
</html>
