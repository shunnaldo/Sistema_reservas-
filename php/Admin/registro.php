<?php
// Incluir el archivo de conexión
include('../conexion.php');

// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Roles permitidos para registrar usuarios (por ejemplo: solo admin o proyecto)
$rolesPermitidos = ['admin', 'proyecto'];

if (!isset($_SESSION['admin_id']) || !in_array($_SESSION['rol'], $rolesPermitidos)) {
    $_SESSION['error'] = "No tienes permisos para registrar usuarios.";
    header("Location: registroAdmin.php");
    exit;
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanitizar los valores del formulario
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    // Validar el rol recibido
    $rolesValidos = ['admin', 'staff', 'proyecto'];
    $rol = (isset($_POST['rol']) && in_array($_POST['rol'], $rolesValidos)) ? $_POST['rol'] : 'staff';

    // Validar el correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Correo inválido.";
        header("Location: registroAdmin.php");
        exit;
    }

    // Bloquear correos temporales
    $dominios_temporales = ['mailinator.com', 'tempmail.com', '10minutemail.com'];
    $correo_dominio = substr(strrchr($correo, "@"), 1);
    if (in_array($correo_dominio, $dominios_temporales)) {
        $_SESSION['error'] = "No se permiten correos temporales.";
        header("Location: registroAdmin.php");
        exit;
    }

    // Validar la contraseña
    if (strlen($contrasena) < 8 ||
        !preg_match("/[A-Z]/", $contrasena) ||
        !preg_match("/[0-9]/", $contrasena) ||
        !preg_match("/[\W_]/", $contrasena)) {
        $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.";
        header("Location: registroAdmin.php");
        exit;
    }

    // Verificar si el correo ya existe
    $sql = "SELECT id_usuario FROM Administradores WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "El correo ya está registrado.";
        header("Location: registroAdmin.php");
        exit;
    }

    // Hashear la contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario
    $sql = "INSERT INTO Administradores (nombre, correo, contrasena, fecha_creacion, rol)
            VALUES (?, ?, ?, NOW(), ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $rol);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Usuario registrado exitosamente.";
    } else {
        $_SESSION['error'] = "Error al registrar usuario.";
    }

    // Cerrar conexiones
    $stmt->close();
    $conexion->close();

    header("Location: registroAdmin.php");
    exit;
}
?>
