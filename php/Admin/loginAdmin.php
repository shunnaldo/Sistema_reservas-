<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Administrador - Casa Emprender</title>
    <link rel="stylesheet" href="../../css/registroAdmin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


</head>
<body>

    <div class="main-container">
        <!-- Icono en la esquina superior izquierda -->
        <div class="logo-icon">
            <img src="../../img/logoFit.png" alt="Icono Casa Emprender">
        </div>
        
        <div class="logo-section">
            <div class="logo">Casa Emprender</div>
        </div>
        
        <div class="container-center">
            <h2>Login de Administrador</h2>

            <!-- Mostrar mensajes de error o éxito -->
            <?php
            if (isset($_GET['error'])) {
                echo '<div class="error-message">';
                switch ($_GET['error']) {
                    case 'credenciales_incorrectas':
                        echo "Las credenciales son incorrectas. Intenta nuevamente.";
                        break;
                    case 'campos_vacios':
                        echo "Por favor, llena todos los campos.";
                        break;
                }
                echo '</div>';
            }
            ?>

            <form action="verificarLogin.php" method="POST">
                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required>

                <label for="contrasena">Contraseña:</label>

                <div class="password-container">
                    <input type="password" id="contrasena" name="contrasena" class="password-input" required>
                    <button type="button" class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>


                <div class="checkbox-container">
                    <input type="checkbox" id="recordar" name="recordar">
                    <label for="recordar">Mantener sesión</label>
                </div>

                <input type="submit" value="Ingresar">
            </form>
        </div>
    </div>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('contrasena');
        const toggleIcon = document.querySelector('.toggle-password i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    }
</script>


</body>
</html>