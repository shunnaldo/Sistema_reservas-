<?php
// Incluir el archivo de conexión
include('../conexion.php');

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];

    // 1. Verificar si los campos no están vacíos
    if (empty($correo) || empty($contrasena)) {
        header("Location: loginAdmin.php?error=campos_vacios");
        exit;
    }

    // 2. Consultar si el correo existe en la base de datos
    $sql = "SELECT * FROM Administradores WHERE correo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si el correo existe
    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();

        // 3. Verificar si la contraseña ingresada es correcta
        if (password_verify($contrasena, $admin['contrasena'])) {
            // Iniciar sesión
            session_start();

            // Guardar datos del administrador en la sesión
            $_SESSION['admin_id'] = $admin['id_usuario'];  // Cambiar 'id' a 'id_usuario'
            $_SESSION['nombre'] = $admin['nombre'];
            $_SESSION['correo'] = $admin['correo'];

            // Si el usuario selecciona "Mantener sesión"
            if (isset($_POST['recordar'])) {
                setcookie('usuario', $admin['correo'], time() + (86400 * 30), "/");  // Cookie válida por 30 días
                setcookie('nombre', $admin['nombre'], time() + (86400 * 30), "/");
            }

            // Redirigir al panel de administración
            header("Location: visualizacionReservas.php");
            exit;
        } else {
            // Si la contraseña es incorrecta
            header("Location: loginAdmin.php?error=credenciales_incorrectas");
            exit;
        }
    } else {
        // Si el correo no existe
        header("Location: loginAdmin.php?error=credenciales_incorrectas");
        exit;
    }

    // Cerrar la declaración y la conexión
    $stmt->close();
}

// Cerrar la conexión a la base de datos
$conexion->close();
?>
