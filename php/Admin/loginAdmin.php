<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador</title>
    <link rel="stylesheet" href="../../css/registroAdmin.css">
</head>
<body>

    <div class="main-container">
        <div class="container-center">
            <h2>Login de Administrador</h2>

            <!-- Mostrar mensajes de error o éxito -->
            <?php
            if (isset($_GET['error'])) {
                switch ($_GET['error']) {
                    case 'credenciales_incorrectas':
                        echo "<p style='color: red;'>Las credenciales son incorrectas. Intenta nuevamente.</p>";
                        break;
                    case 'campos_vacios':
                        echo "<p style='color: red;'>Por favor, llena todos los campos.</p>";
                        break;
                }
            }
            ?>

            <form action="verificarLogin.php" method="POST">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required><br><br>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required><br><br>

                <!-- Casilla para recordar la sesión -->
                <label for="recordar">Mantener sesión:</label>
                <input type="checkbox" id="recordar" name="recordar"><br><br>

                <input type="submit" value="Ingresar">
            </form>
        </div>
    </div>

</body>
</html>
