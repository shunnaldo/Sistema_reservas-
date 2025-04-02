<?php
// Incluir el archivo de conexión
include('../conexion.php');

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // 1. Validar el correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header("Location: registroAdmin.php?error=correo_invalido");
        exit;
    }

    // 2. Validar que el correo no esté en dominios de correos temporales (opcional)
    $dominios_temporales = ['mailinator.com', 'tempmail.com', '10minutemail.com']; // Puedes agregar más dominios
    $correo_dominio = substr(strrchr($correo, "@"), 1);

    if (in_array($correo_dominio, $dominios_temporales)) {
        header("Location: registroAdmin.php?error=correo_temporal");
        exit;
    }

    // 3. Validar la contraseña (mínimo 8 caracteres, al menos una mayúscula, un número y un símbolo)
    if (strlen($contrasena) < 8) {
        header("Location: registroAdmin.php?error=contrasena_corta");
        exit;
    }

    if (!preg_match("/[A-Z]/", $contrasena)) {
        header("Location: registroAdmin.php?error=contrasena_mayuscula");
        exit;
    }

    if (!preg_match("/[0-9]/", $contrasena)) {
        header("Location: registroAdmin.php?error=contrasena_numero");
        exit;
    }

    if (!preg_match("/[\W_]/", $contrasena)) {
        header("Location: registroAdmin.php?error=contrasena_simbolo");
        exit;
    }

    // 4. Validar si el correo ya existe
    $sql = "SELECT * FROM Administradores WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: registroAdmin.php?error=correo_existente");
        exit; // Detener el proceso si el correo ya existe
    } else {
        // Hashear la contraseña antes de guardarla
        $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Insertar los datos en la base de datos
        $sql = "INSERT INTO Administradores (nombre, correo, contrasena) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $nombre, $correo, $contrasena_hash);

        if ($stmt->execute()) {
            header("Location: registroAdmin.php?success=registro_exitoso");
        } else {
            header("Location: registroAdmin.php?error=registro_error");
        }
    }

    $stmt->close();
}

$conexion->close();
?>
