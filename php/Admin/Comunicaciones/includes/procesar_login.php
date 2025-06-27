<?php
session_start(); // Habilita sesiones

// Conexión a la base de datos
require_once(__DIR__ . '/../bd/conexion_test.php');

// Obtener datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Inicializamos una variable de error
$error = '';

// Validar campos vacíos
if (empty($correo) || empty($contrasena)) {
    // Si los campos están vacíos, asignamos un mensaje de error
    $error = "Por favor, ingresa tanto el correo como la contraseña.";
    $_SESSION['error'] = $error;
    header("Location: login.php"); // Redirigir de nuevo al login
    exit();
}

// Buscar el usuario en la base de datos
$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si el usuario existe
if ($result->num_rows === 1) {
    // Si el usuario existe, verificar la contraseña
    $usuario = $result->fetch_assoc();

    // Verificar si la contraseña es correcta
    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Si la contraseña es correcta, guardar los datos del usuario en la sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['apellido'] = $usuario['apellido'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['area'] = $usuario['area'];

        // Si el usuario tiene el rol 'proponente', guardar el id_usuario como id_proponente
        if ($usuario['rol'] === 'proponente') {
            $_SESSION['id_proponente'] = $usuario['id_usuario'];  // Asignar el id_usuario como id_proponente
        }

        // Redirigir según el rol y área del usuario
        if ($usuario['rol'] === 'director') {
            header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Director/home_director.php");

        } elseif ($usuario['rol'] === 'proponente') {
            // Verificar si el área es 'DAF'
            if ($usuario['area'] === 'DAF') {
                header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/DAF/home_daf.php"); // Redirigir a la página de DAF
            } 
            elseif ($usuario['area'] === 'Comunicaciones') {
                header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Comunicaciones/home_comunicaciones.php");

            } 
            elseif ($usuario['area'] === 'FIT') {
                header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Comunicaciones/home_comunicaciones.php");

            } 
            else {
                header("Location: ../dashboard/proponente.php"); // Redirigir a la página genérica para proponentes
            }
        } else {
            // Si el rol es desconocido, redirigir al login
            $error = "Rol desconocido. No se puede acceder al sistema.";
            $_SESSION['error'] = $error;
            header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Public/login.php");
        }
    } else {
        // Si la contraseña es incorrecta
        $error = "Credenciales incorrectas. Inténtalo de nuevo.";
        $_SESSION['error'] = $error;
        header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Public/login.php"); // Redirigir de nuevo al login
    }
} else {
    // Si el usuario no es encontrado
    $error = "Usuario no encontrado. Por favor, verifica el correo ingresado.";
    $_SESSION['error'] = $error;
    header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Public/login.php"); // Redirigir de nuevo al login
}

$conn->close(); // Cerrar la conexión a la base de datos
?>
