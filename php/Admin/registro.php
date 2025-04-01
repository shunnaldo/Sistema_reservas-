<?php
// Incluir el archivo de conexión
include('../conexion.php');

// Iniciar sesión
session_start();

// Verificar si el usuario tiene permisos para registrar (solo admins)
if (!isset($_SESSION['admin_id']) || empty($_SESSION['rol']) || trim(strtolower($_SESSION['rol'])) !== 'admin') {
    header("Location: registroAdmin.php?error=" . urlencode('No tienes permisos para registrar usuarios.'));
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
        header("Location: registroAdmin.php?error=" . urlencode('Correo inválido.'));
        exit;
    }

    // 2. Evitar dominios de correos temporales
    $dominios_temporales = ['mailinator.com', 'tempmail.com', '10minutemail.com'];
    $correo_dominio = substr(strrchr($correo, "@"), 1);
    if (in_array($correo_dominio, $dominios_temporales)) {
        header("Location: registroAdmin.php?error=" . urlencode('No se permiten correos temporales.'));
        exit;
    }

    // 3. Validar la contraseña (mínimo 8 caracteres, al menos una mayúscula, un número y un símbolo)
    if (strlen($contrasena) < 8 ||
        !preg_match("/[A-Z]/", $contrasena) ||
        !preg_match("/[0-9]/", $contrasena) ||
        !preg_match("/[\W_]/", $contrasena)) {
        header("Location: registroAdmin.php?error=" . urlencode('La contraseña no cumple los requisitos.'));
        exit;
    }

    // 4. Validar si el correo ya existe
    $sql = "SELECT id_usuario FROM Administradores WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: registroAdmin.php?error=" . urlencode('El correo ya está registrado.'));
        exit;
    }

    // 5. Hashear la contraseña antes de guardarla
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    // 6. Insertar los datos en la base de datos
    $sql = "INSERT INTO Administradores (nombre, correo, contrasena, rol) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $correo, $contrasena_hash, $rol);

    if ($stmt->execute()) {
        header("Location: registroAdmin.php?success=" . urlencode('Registro exitoso.'));
    } else {
        header("Location: registroAdmin.php?error=" . urlencode('Error al registrar usuario.'));
    }

    $stmt->close();
}

$conexion->close();
?>
