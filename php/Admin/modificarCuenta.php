<?php
// Iniciar sesión
session_start();

// Verificar si el administrador está logueado y tiene permisos
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para modificar cuentas.");
    exit;
}

// Incluir el archivo de conexión
include('../conexion.php');

// Verificar si se pasa un ID
if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Obtener la información de la cuenta a modificar
    $sql = "SELECT * FROM Administradores WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No se encontró la cuenta.";
        exit;
    }

    $user = $result->fetch_assoc();
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Validar y actualizar el correo
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Correo inválido.";
        header("Location: modificarCuenta.php?id=$id_usuario");
        exit;
    }

    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Actualizar la cuenta
    $sql = "UPDATE Administradores SET correo = ?, contrasena = ? WHERE id_usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssi", $correo, $contrasena_hash, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Cuenta modificada con éxito.";
    } else {
        $_SESSION['error'] = "Error al modificar la cuenta.";
    }

    header("Location: listarCuentas.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Cuenta</title>
    <link rel="stylesheet" href="../../css/registroAdmin.css">
</head>
<body>

<div id="navbarAdmin-container"></div>

<div class="main-container">
    <div class="container-center">
        <h2>Modificar Cuenta</h2>

        <?php if (isset($_SESSION['error'])) { echo "<p style='color: red;'>".$_SESSION['error']."</p>"; unset($_SESSION['error']); } ?>
        <?php if (isset($_SESSION['success'])) { echo "<p style='color: green;'>".$_SESSION['success']."</p>"; unset($_SESSION['success']); } ?>

        <form action="modificarCuenta.php?id=<?php echo $user['id_usuario']; ?>" method="POST">
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required><br><br>

            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required><br><br>

            <input type="submit" value="Modificar Cuenta">
        </form>
    </div>
</div>

<script src="../../js/navbarAdmin.js"></script>
<script src="../../js/sidebar.js"></script>

</body>
</html>
