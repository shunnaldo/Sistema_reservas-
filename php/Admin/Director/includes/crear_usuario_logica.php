<?php
require_once(__DIR__ . '/../../Comunicaciones/bd/conexion_test.php'); // Ajusta ruta según tu estructura

// Verificar la conexión a la base de datos
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


// Verificar si se han enviado los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recuperar y sanitizar los datos del formulario
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
    $contrasena = password_hash($_POST['clave'], PASSWORD_DEFAULT); // Encriptar la contraseña
    $rol = $_POST['rol'];
    $area = $_POST['area'];
    $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    // Verificar que todos los campos requeridos están llenos
    if (empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena) || empty($rol) || empty($area) || empty($direccion) || empty($telefono)) {
        echo "Todos los campos son obligatorios.";
        exit();
    }

    // Asignar el id_usuario de forma automática (usando AUTO_INCREMENT en la base de datos)
    // La base de datos debería tener un campo id_usuario con auto_increment para este propósito
    $sql = "INSERT INTO usuarios (nombre, apellido, correo, contrasena, rol, area, direccion, telefono) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Verificar si la consulta se prepara correctamente
    echo "Preparando la consulta SQL...<br>";
    if ($stmt = $conn->prepare($sql)) {
        echo "Consulta preparada correctamente...<br>";
        $stmt->bind_param("ssssssss", $nombre, $apellido, $correo, $contrasena, $rol, $area, $direccion, $telefono);

        // Ejecutar la consulta
        echo "Ejecutando la consulta...<br>";
        if ($stmt->execute()) {
            echo "Usuario creado exitosamente...<br>"; // Verificar si el usuario se crea
            header("Location: gestionar_usuarios.php?success=Usuario creado exitosamente.");
            exit();
        } else {
            echo "Error al ejecutar la consulta: " . $stmt->error . "<br>"; // Mostrar el error si la consulta falla
        }
    } else {
        echo "Error al preparar la consulta: " . $conn->error . "<br>"; // Mostrar si hay un error al preparar la consulta
    }
}

$conn->close();
