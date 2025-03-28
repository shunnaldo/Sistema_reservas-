<?php

// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Administrador</title>
    <link rel="stylesheet" href="../../css/registroAdmin.css">
</head>
<body>

    <div id="navbarAdmin-container"></div>

    <div class="main-container">
        <div class="container-center">
            <h2>Formulario de Registro</h2>

            <!-- Mostrar mensajes de error o éxito -->
            <?php
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case 'correo_invalido':
                        echo "<p style='color: red;'>El correo electrónico no es válido.</p>";
                        break;
                    case 'correo_temporal':
                        echo "<p style='color: red;'>No se permiten correos electrónicos de dominios temporales.</p>";
                        break;
                    case 'contrasena_corta':
                        echo "<p style='color: red;'>La contraseña debe tener al menos 8 caracteres.</p>";
                        break;
                    case 'contrasena_mayuscula':
                        echo "<p style='color: red;'>La contraseña debe contener al menos una letra mayúscula.</p>";
                        break;
                    case 'contrasena_numero':
                        echo "<p style='color: red;'>La contraseña debe contener al menos un número.</p>";
                        break;
                    case 'contrasena_simbolo':
                        echo "<p style='color: red;'>La contraseña debe contener al menos un símbolo (como !, @, #, $, etc.).</p>";
                        break;
                    case 'correo_existente':
                        echo "<p style='color: red;'>El correo ya está registrado. Por favor, use otro correo.</p>";
                        break;
                    case 'registro_error':
                        echo "<p style='color: red;'>Hubo un error en el registro. Intenta nuevamente.</p>";
                        break;
                }
            } elseif (isset($_GET['success'])) {
                echo "<p style='color: green;'>Registro exitoso.</p>";
            }
            ?>

            <form action="registro.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required><br><br>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required><br><br>

                <input type="submit" value="Registrar">
            </form>
        </div>
    </div>

    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  
</body>
</html>
