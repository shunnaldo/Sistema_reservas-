<?php
// Incluir el archivo de conexión
include('../conexion.php');

// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario tiene permisos para registrar (solo admins)
if (!isset($_SESSION['admin_id']) || empty($_SESSION['rol']) || trim(strtolower($_SESSION['rol'])) !== 'admin') {
    $_SESSION['error'] = "No tienes permisos para registrar usuarios.";
    header("Location: registroAdmin.php");
    exit;
}

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $contrasena = $_POST['contrasena'];
    $rol = isset($_POST['rol']) && ($_POST['rol'] == 'admin' || $_POST['rol'] == 'staff') ? $_POST['rol'] : 'staff';

    // 1. Validar el correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Correo inválido.";
        header("Location: registroAdmin.php");
        exit;
    }

    // 2. Evitar dominios de correos temporales
    $dominios_temporales = ['mailinator.com', 'tempmail.com', '10minutemail.com'];
    $correo_dominio = substr(strrchr($correo, "@"), 1);
    if (in_array($correo_dominio, $dominios_temporales)) {
        $_SESSION['error'] = "No se permiten correos temporales.";
        header("Location: registroAdmin.php");
        exit;
    }

    // 3. Validar la contraseña (mínimo 8 caracteres, al menos una mayúscula, un número y un símbolo)
    if (strlen($contrasena) < 8 ||
        !preg_match("/[A-Z]/", $contrasena) ||
        !preg_match("/[0-9]/", $contrasena) ||
        !preg_match("/[\W_]/", $contrasena)) {
        $_SESSION['error'] = "La contraseña no cumple los requisitos.";
        header("Location: registroAdmin.php");
        exit;
    }

    // 4. Validar si el correo ya existe
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

    // 5. Hashear la contraseña antes de guardarla
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // 6. Insertar los datos en la base de datos
    $sql = "INSERT INTO Administradores (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $rol);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registro exitoso.";
    } else {
        $_SESSION['error'] = "Error al registrar usuario.";
    }

    $stmt->close();
    $conexion->close();

    header("Location: registroAdmin.php");
    exit;
}
?>
