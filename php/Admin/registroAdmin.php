<?php
// Iniciar la sesión
session_start();

// Verificar si el administrador está logueado y si tiene permisos de administrador
if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para registrar usuarios.");
    exit;
}


if (!isset($_SESSION['admin_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: loginAdmin.php?error=No tienes permisos para registrar usuarios.");
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


            <form action="registro.php" method="POST">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required><br><br>

                <label for="correo">Correo:</label>
                <input type="email" id="correo" name="correo" required><br><br>

                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" name="contrasena" required><br><br>

                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="staff">Staff</option>
                    <option value="admin">Administrador</option>
                </select><br><br>

                <input type="submit" value="Registrar">
            </form>
        </div>
    </div>

    <script src="../../js/navbarAdmin.js"></script>
    <script src="../../js/sidebar.js"></script>  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        fetch('userData.php')
        .then(response => response.json())
        .then(data => {
            console.log("Respuesta del servidor:", data);
            if (data.error) {
                console.error("Error:", data.error);
            } else {
                document.getElementById('user-name').textContent = data.nombre || "Usuario desconocido";
                document.getElementById('user-email').textContent = data.correo || "Correo no disponible";
            }
        })
        .catch(error => console.error('Error al obtener los datos del usuario:', error));
    </script>

<script>
        // Mostrar el mensaje de error o éxito utilizando SweetAlert2
        <?php
            // Mostrar error si existe
            if (isset($_SESSION['error'])) {
                echo "Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '" . $_SESSION['error'] . "'
                });";
                unset($_SESSION['error']); // Eliminar el mensaje después de mostrarlo
            }

            // Mostrar éxito si existe
            if (isset($_SESSION['success'])) {
                echo "Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '" . $_SESSION['success'] . "'
                });";
                unset($_SESSION['success']); // Eliminar el mensaje después de mostrarlo
            }
        ?>
    </script>

<script>
   document.addEventListener("DOMContentLoaded", function() {
       const dropdownToggle = document.querySelector(".sidebar__dropdown-toggle");
       const dropdownMenu = document.querySelector(".sidebar__dropdown");
   
       dropdownToggle.addEventListener("click", function() {
           dropdownMenu.style.display = (dropdownMenu.style.display === "flex") ? "none" : "flex";
           dropdownToggle.classList.toggle("active");
       });
   });
   </script>    
</body>
</html>
