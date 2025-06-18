<?php
session_start(); // Habilita sesiones

// Conexión a la base de datos
require_once(__DIR__ . '/../bd/conexion_test.php');

// Obtener datos del formulario
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

// Inicializamos una variable de error
$error = '';

// Validar campos
if (empty($correo) || empty($contrasena)) {
    $error = "Campos vacíos: correo = $correo, contraseña = $contrasena";
    error_log($error);  // Log de error
    $_SESSION['error'] = $error;
    header("Location: login.php?error=campos_vacios");
    exit();
}

error_log("Campos recibidos: correo = $correo, contraseña = $contrasena");

// Buscar el usuario
$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    error_log("Usuario encontrado: " . json_encode($usuario));  // Log de usuario encontrado

    if (password_verify($contrasena, $usuario['contrasena'])) {
        // Guardar datos en sesión
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre'] = $usuario['nombre'];
        $_SESSION['apellido'] = $usuario['apellido'];
        $_SESSION['rol'] = $usuario['rol'];
        $_SESSION['area'] = $usuario['area'];

        // Aquí agregamos el id_proponente si el usuario tiene rol de proponente
        if ($usuario['rol'] === 'proponente') {
            // Obtener el id_proponente relacionado
            $sql_proponente = "SELECT id_proponente FROM proponente WHERE id_proponente = ?";
            $stmt_proponente = $conn->prepare($sql_proponente);
            $stmt_proponente->bind_param("i", $usuario['id_usuario']); // Asumimos que id_usuario es igual a id_proponente
            $stmt_proponente->execute();
            $result_proponente = $stmt_proponente->get_result();

            if ($result_proponente->num_rows === 1) {
                $proponente = $result_proponente->fetch_assoc();
                $_SESSION['id_proponente'] = $proponente['id_proponente'];  // Guardamos el id_proponente en la sesión
                error_log("Proponente encontrado: " . json_encode($proponente));  // Log del proponente encontrado
            }
            $stmt_proponente->close();
        }

        // Redirección según rol y área
        if ($usuario['rol'] === 'director') {
            error_log("Redirigiendo a director.php");  // Log de redirección
            header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Director/home_director.php");
        } elseif ($usuario['rol'] === 'proponente') {
            if ($usuario['area'] === 'Comunicaciones') {
                error_log("Redirigiendo a home_comunicaciones.php");  // Log de redirección
                header("Location: /CasaEmprender/Sistema_reservas-/PHP/Admin/Comunicaciones/home_comunicaciones.php");
            } elseif ($usuario['area'] === 'FIT') {
                error_log("Redirigiendo a proponente_fit.php");  // Log de redirección
                header("Location: ../dashboard/proponente_fit.php");
            } elseif ($usuario['area'] === 'DAF') {
                error_log("Redirigiendo a proponente_daf.php");  // Log de redirección
                header("Location: /sistema_reservas/Sistema_reservas-/PHP/Admin/Daf/dasboard_daf.php");
            } else {
                error_log("Redirigiendo a proponente.php");  // Log de redirección
                header("Location: ../dashboard/proponente.php"); // Genérico
            }
        } else {
            error_log("Rol desconocido");  // Log si el rol es desconocido
            header("Location: login.php?error=rol_desconocido");
        }
    } else {
        error_log("Contraseña incorrecta");  // Log de contraseña incorrecta
        $_SESSION['error'] = "Contraseña incorrecta";
        header("Location: login.php?error=contrasena_incorrecta");
    }
} else {
    error_log("Usuario no encontrado: $correo");  // Log si el usuario no es encontrado
    $_SESSION['error'] = "Usuario no encontrado";
    header("Location: login.php?error=usuario_no_encontrado");
}

$conn->close();
